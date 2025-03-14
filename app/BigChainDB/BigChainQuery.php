<?php
/**
 * BigChain Collection : 
 *
 */
namespace App\BigChainDB;

use DateTime;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class BigChainQuery
{
    protected static $driver = null;
    protected $table;
    protected $join = null;
    protected $object;
    protected $queries = null;
    protected $orders = [];
    protected $limit = null;
    protected $page = null;
    
    public function __construct(string $table = null, string $object = null)
    {
        $this->table = $table;
        $this->object = $object;
        if(!self::$driver) {
            self::$driver = new Client([
                'base_uri' => env('BIGCHAINDB_DRIVER', 'http://localhost:2466/'),
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'verify' => false
            ]);
        }
    }

    private function getParams($encoded = true) {
        $where = json_encode($this->queries);
        return [
            'object' => $this->table,
            'join' => $this->join,
            'where' => $this->queries ? ($encoded ? rawurlencode($where) : $where): null,
            'orderBy' => $this->orders,
            'page' => $this->page,
            'limit' => $this->limit
        ];
    }

    public function paginate($pageSize) {
        return new BigChainPaginator($this, $pageSize);
    }

    public function get()
    {    
        Log::info('GET ' . $this->table . ' QUERY ' . json_encode($this->getParams(false)));

        $response = self::$driver->get('/', [ 'query' => $this->getParams() ]);

        $result = json_decode($response->getBody()->getContents());
        
        if(!is_array($result->data)) {
            throw new Exception(json_encode($result));
        }

        $items = new Collection(array_map(function($item) { return new $this->object($item); }, $result->data));

        $res =  isset($result->total) ? [
            'items' => $items,
            'total' => $result->total
        ] : $items;
        
        Log::info('RETURN TOTAL ' . ($result->total ?? 'none') . ' COUNT ' . count($items));

        return $res;
    }
    
    public function all()
    {
        return $this->get();
    }

    public function first()
    {
        return $this->limit(1)->get()->first();
    }

    public function FindOrFail($id)
    {
        $res = $this->where('id', $id)->first();
        if($res) return $res;
        throw new Exception('Invalid ID');
    }

    public function value(string $column) 
    {
        $res = $this->first();
        return $res ? $res->{$column} : null;
    }

    public function pluck(string $column)
    {
        $values = [];
        foreach($this->get() as $item)
            $values[] = $item->{$column};
        return new Collection($values);
    }

    public function select(string $column)
    {
        return array_unique($this->pluck($column)->toArray());
    }

    
 
    public function find(string $id)
    {
        return $this->where('id', $id)->first();
    }

    public function count()
    {
        Log::info('COUNT ' . $this->table . ' QUERY ' . json_encode($this->getParams(false)));
        $res = self::$driver->get('/count', [ 'query' => $this->getParams() ]);
        $count = json_decode($res->getBody()->getContents())->data;
        Log::info('RETURN COUNT ' . $count);
        return (int)$count;
    }

    public function exists()
    {
        return $this->count() > 0;
    }

    public function sum($column)
    {
        Log::info('SUM ' . $this->table . ' QUERY ' . json_encode($this->getParams(false)));
        $params = $this->getParams();
        $params['column'] = $column;
        $res = self::$driver->get('/sum', [ 'query' => $params ]);
        $sum = json_decode($res->getBody()->getContents())->data;
        Log::info('RETURN SUM ' . $sum);
        return $sum;
    }

    
    /**
     * Add JOIN clause to the query.
     * @param   array  $data
     * @return  BigChainModel
     */
    public function create($data)
    {
        $res = self::$driver->post('/', [ 'json' => [
            'data' => $data,
            'object' => $this->table
        ]]);
        $obj = new $this->object(json_decode($res->getBody()->getContents())->data);
        Log::info('CREATE ' . $this->table . ' DATA ' . json_encode($obj));
        return $obj;
    }

    public function firstOrCreate($where, $data) {
        $res = $this->where($where)->first();
        if($res) $res = $this->create($where + $data);
        return $res;
    }

    public function insert($data)
    {
        return $this->create($data);
    }

    public function insertGetId($data)
    {
        return $this->create($data)->id ?? null;
    }

    public function update($data)
    {
        $params = $this->getParams(false);
        $params['data'] = $data;
        $res = self::$driver->put('/', [ 'json' => $params ]);
        $data = json_decode($res->getBody()->getContents())->data;
        Log::info('Update ' . $this->table . ' DATA ' . json_encode($data));
        return count($data);
    }

    public function delete()
    {
        $res = self::$driver->delete('/', [ 'query' => $this->getParams() ]);
        $data = json_decode($res->getBody()->getContents())->data;
        Log::info('Delete ' . $this->table . ' ID ' . json_encode($data));
        return count($data);
    }

    public function has($join_table, $own_field, $operator, $join_field)
    {
        return $this->join($join_table, $own_field, $operator, $join_field);
    }

    /**
     * Add JOIN clause to the query.
     * @param   string  $join_table
     * @param   string  $own_field
     * @param   string  $operator
     * @param   string  $join_field
     * @return  $this
     */
    public function join($join_table, $own_field, $operator, $join_field)
    {
        $this->join = [ $join_table, $own_field, $join_field ];
        return $this;
    }

    /**
     * Add OFFSET & LIMIT clause to the query.
     * @param  int   $page
     * @param  int   $pageSize
     * @return Collection
     */
    public function pagination($page, $pageSize)
    {
        $this->page = $page;
        $this->limit = $pageSize;
        $res = $this->get();
        return $res;
    }

    /**
     * Add LIMIT clause to the query.
     * @param  int   $count
     * @return $this
     */
    public function limit($count)
    {
        $this->limit = $count;
        return $this;
    }

    /**
     * Add ORDER BY clause to the query.
     *
     * @param  string  $column
     * @param  string  $order
     * @param  string  $typecast
     * @return $this
     */
    public function orderBy($column, $order, $typecast = null)
    {
        $this->orders[] = [
            'key' => $column,
            'order' => $order,
            'typecast' => $typecast
        ];
        return $this;
    }

    public function latest()
    {
        return $this->orderBy('created_at', 'DESC');
    }

    /**
     * Apply the callback's query changes if the given "value" is true.
     *
     * @param  mixed  $value
     * @param  callable  $callback
     * @param  callable  $default
     * @return mixed|$this
     */
    public function when($value, $callback, $default = null)
    {
        if ($value) {
            return $callback($this, $value) ?: $this;
        } elseif ($default) {
            return $default($this, $value) ?: $this;
        }

        return $this;
    }
    
    /**
     * Add WHERE clause to the query.
     *
     * @param  string  $connector
     * @param  string|array|\Closure  $column
     * @param  mixed   $operator
     * @param  mixed   $value
     * @return $this
     */
    private function addQuery($connector, $column, $operator, $value)
    {
        // Build Query
        if($value) {
            $query = [
                'operator' => strtolower($operator),
                'operand' => [
                    $column => $value
                ]
            ];
        } else if($operator) {
            $query = [
                'operator' => '==',
                'operand' => [
                    $column => $operator
                ]
            ];
        } else if($column instanceof \Closure) {
            $newQuery = new BigChainQuery($this->table, $this->object);
            $column($newQuery);
            $query = $newQuery->queries;
        } else {
            $query = [
                'operator' => '==',
                'operand' => $column
            ];
        }
        // Combine Query
        if(!$this->queries) {
            $this->queries = $query;
        } else if(($this->queries['connector'] ?? null) === $connector) {
            $this->queries['queries'][] = $query;
        } else {
            $this->queries = [
                'connector' => $connector,
                'queries' => [
                    $this->queries,
                    $query
                ]
            ];
        }
        // Return Object
        return $this;
    }

    public static function timestamp($datetime) {
        if ($datetime instanceof DateTime) return $datetime->getTimestamp();
        return (new DateTime($datetime))->getTimestamp();
    }

    public function where($column, $operator = null, $value = null)
    {
        if($column == 'created_at') {
            $value = is_array($value) ? array_map('self::timestamp', $value) : self::timestamp($value);
        }
        return $this->addQuery('and', $column, $operator, $value);
    }

    public function whereIn($column, $value) {
        return $this->where($column, 'in', $value);
    }

    public function whereNotIn($column, $value) {
        return $this->where($column, '!in', $value);
    }

    public function whereNotNull($column) {
        return $this->where($column, '!=', null);
    }

    public function whereBetween($column, $value) {
        return $this->where($column, 'between', $value);
    }

    public function whereDate($column, $value) {
        return $this->whereBetween($column, [
            new DateTime($value . ' 00:00:00'),
            new DateTime($value . ' 23:59:59')
        ]);
    }

    public function orWhere($column, $operator = null, $value = null)
    {
        return $this->addQuery('or', $column, $operator, $value);
    }
    
    public function orWhereIn($column, $value) {
        return $this->orWhere($column, 'in', $value);
    }

    public function orWhereNotIn($column, $value) {
        return $this->orWhere($column, '!in', $value);
    }

    public function orWhereBetween($column, $value) {
        return $this->orWhere($column, 'between', $value);
    }
}