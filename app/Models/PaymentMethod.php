<?php
/**
 * PaymentMethod Model
 *
 *  Manage the Payment Method Settings
 *
 * @package TokenLite
 * @author Softnio
 * @version 1.1.6
 */
namespace App\Models;

use App\BigChainDB\BigChainModel;
use App\Models\Setting;
use GuzzleHttp\Client;

class PaymentMethod extends BigChainModel
{
    /*
     * Table Name Specified
     */
    protected static $table = 'payment_methods';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['payment_method', 'symbol', 'title', 'description', 'data'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     * @version 1.3.1
     * @since 1.0
     */
    const Currency = [
            'usd' => 'US Dollar', 
            'eur' => 'Euro', 
            'gbp' => 'Pound Sterling',
            'cad' => 'Canadian Dollar',
            'aud' => 'Australian Dollar',
            'try' => 'Turkish Lira',
            'rub' => 'Russian Ruble',
            'inr' => 'Indian Rupee',
            'brl' => 'Brazilian Real',
            'nzd' => 'New Zealand Dollar',
            'pln' => 'Polish Złoty',
            'jpy' => 'Japanese Yen',
            'myr' => 'Malaysian Ringgit',
            'idr' => 'Indonesian Rupiah',
            'ngn' => 'Nigerian Naira',
            'mxn' => 'Mexican Peso',
            'php' => 'Philippine Peso',
            'chf' => 'Swiss franc',
            'thb' => 'Thai Baht',
            'sgd' => 'Singapore dollar',
            'czk' => 'Czech koruna',
            'nok' => 'Norwegian krone',
            'zar' => 'South African rand',
            'sek' => 'Swedish krona',
            'kes' => 'Kenyan shilling',
            'nad' => 'Namibian dollar',
            'dkk' => 'Danish krone',
            'hkd' => 'Hong Kong dollar',
            'huf' => 'Hungarian Forint',
            'pkr' => 'Pakistani Rupee',
            'egp' => 'Egyptian Pound',
            'clp' => 'Chilean Peso',
            'cop' => 'Colombian Peso',
            'jmd' => 'Jamaican Dollar',
            'eth' => 'Ethereum', 
            'btc' => 'Bitcoin', 
            'ltc' => 'Litecoin', 
            'xrp' => 'Ripple',
            'xlm' => 'Stellar',
            'bch' => 'Bitcoin Cash',
            'bnb' => 'Binance Coin',
            'usdt' => 'Tether',
            'trx' => 'TRON',
            'usdc' => 'USD Coin',
            'dash' => 'Dash',
            'waves' => 'Waves',
            'xmr' => 'Monero',
            'busd' => 'Binance USD',
            'ada' => 'Cardano',
            'doge' => 'Dogecoin',
            'sol' => 'Solana',
            'uni' => 'Uniswap',
            'link' => 'Chainlink',
            'cake' => 'PancakeSwap',
            'cdf' => 'Franc Congolais',
            'cfa' => 'Franc Congolais',
        ];

    public function __construct($attributes = null)
    {
        parent::__construct($attributes);
        $auto_check = (60 * (int) get_setting('pm_automatic_rate_time', 60)); // 1 Hour

        $this->save_default();
        $this->automatic_rate_check($auto_check);
    }
    /**
     *
     * Get the data
     *
     * @version 1.0.2
     * @since 1.0
     * @return void
     */
    public static function get_data($name = '', $everything = false)
    {
        if ($name !== '') {
            $data = self::where('payment_method', $name)->first();
            
            error_log('LOG ' . json_encode($data));
            if(! $data) return false;
            
            $result = (object) [
                'status' => $data->status,
                'title' => $data->title,
                'details' => $data->description,
                'secret' => json_decode($data->data),
            ];
            // dd($result);
            return ($everything == true ? $result : $result->secret);
        }else{
            $all = self::all();
            $result = [];
            foreach ($all as $data) {
                $result[$data->payment_method] = (object) [
                    'status' => $data->status,
                    'title' => $data->title,
                    'details' => $data->description,
                    'secret' => json_decode($data->data),
                ];
            }
            return (object) $result;
        }
    }

    /**
     *
     * Get the data
     *
     * @version 1.0.1
     * @since 1.0
     * @return void
     */
    public static function get_bank_data($name = '', $everything = false)
    {
        return self::get_single_data('bank'); //v1.1.3 removed $this->
    }

    /**
     *
     * Get single data
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public static function get_single_data($name)
    {
        $data = self::where('payment_method', $name)->first();
        $data->secret = ($data != null) ? json_decode($data->data) : null;

        return ($data != null) ? $data : null;
    }

    /**
     *
     * Save the default
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public function save_default()
    {
        foreach (self::Currency as $key => $value) {
            if (Setting::getValue('pmc_active_' . $key) == '') {
                Setting::updateValue('pmc_active_' . $key, 1);
            }
            if (Setting::getValue('pmc_rate_' . $key) == '') {
                Setting::updateValue('pmc_rate_' . $key, 1);
            }
        }
    }

    /**
     *
     * Currency Symbol
     *
     * @version 1.0.0
     * @since 1.1.1
     * @return void
     */
    public static function get_currency($output=null)
    {
        $get_currency = self::Currency;
        $all_currency_sym = array_keys($get_currency);
        $currencies = array_map('strtolower', $all_currency_sym);

        if($output=='all') return $get_currency;
        return $currencies;
    }

    /**
     *
     * Check
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public static function check($name = '')
    {
        $data = self::where('payment_method', $name)->count();
        return ($data > 0) ? false : true;
    }

    /**
     *
     * Set Exchange rates from cryptocompare api between a several time
     *
     * @version 1.1
     * @since 1.0.0
     * @return void
     */
    public function automatic_rate_check($between = 3600, $force = false)
    {
        $check_time = get_setting('pm_exchange_auto_lastcheck', now()->subMinutes(10));
        $current_time = now();
        if ( ((strtotime($check_time) + ($between)) <= strtotime($current_time)) || $force == true) {
            $rate = self::automatic_rate(base_currency(true));
            $all_currency = self::get_currency();
            $all_new_rate = [];
            foreach ($all_currency as $cur) {
                $auto_currency = strtoupper($cur);
                $new_rate = (isset($rate->$auto_currency) ? $rate->$auto_currency : 1);
                $all_new_rate[$cur] = $new_rate;
                Setting::updateValue('pmc_auto_rate_' . $cur, $new_rate);
            }
            Setting::updateValue( 'pmc_current_rate', json_encode($all_new_rate) );
            Setting::updateValue( 'token_all_price', json_encode(token_calc(1, 'price')) );
            Setting::updateValue( 'pm_exchange_auto_lastcheck', now() );
        }
    }

    /**
     *
     * Get automatic rates
     *
     * @version 1.1
     * @since 1.0.0
     * @return void
     */
    public static function automatic_rate($base = '')
    {
        $cl = new Client();
        $base_currency = (!empty($base)) ? strtoupper($base) : base_currency(true);
        $check_time = get_setting('pm_exchange_auto_lastcheck', now()->subMinutes(5));
        $current_time = now();
        if ((strtotime($check_time)) <= strtotime($current_time)) {
            $all_currency = self::get_currency();
            $currencies = array_map('strtoupper', $all_currency);
            $all = join(',', $currencies);
            $data = self::default_rate();
            try {
                $response = $cl->get('https://min-api.cryptocompare.com/data/price?fsym=' . $base_currency . '&tsyms=' . $all . '');
                $data = json_decode($response->getBody());
            } catch (\Exception $e) {
                info($e->getMessage());
            } finally {
                return $data;
            }
        }
    }

    /**
     *
     * Default Rate
     *
     * @version 1.1
     * @since 1.0.0
     * @return void
     */
    public static function default_rate()
    {
        $currencies = self::get_currency();
        $old = [];
        foreach ($currencies as $cur) {
            $cur = strtoupper($cur);
            $old[$cur] = get_setting('pmc_auto_rate_' . $cur);
        }
        $old['default'] = true;

        return (object) $old;
    }
}
