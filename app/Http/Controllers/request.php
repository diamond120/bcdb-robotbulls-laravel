<?php


namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

class CoinController extends Controller
{
    public function addRow() {
        
        $latestRows5 = DB::table('coin')->orderBy('date', 'desc')->take(5)->get();
        $latestRows6 = DB::table('coin')->orderBy('date', 'desc')->take(6)->get();
        $latestRows62 = DB::table('coin')->orderBy('date', 'desc')->take(6)->get();
        $latestRow = DB::table('coin')->orderBy('date', 'desc')->first();

            $params = [
                'latestRows5' => $latestRows5,
                'latestRows6' => $latestRows6,
                'latestRows62' => $latestRows62,
                'latestRow' => $latestRow
            ];
            $clients = [
                new Client(['base_uri' => 'https://eos.greymass.com:443']),
                new Client(['base_uri' => 'https://mainnet.eos.dfuse.io']),
                new Client(['base_uri' => 'https://api.eosnewyork.io']),
                new Client(['base_uri' => 'https://api.eosdetroit.io']),
            ];
            $promises = [];
            foreach ($clients as $client) {
                $promises[] = $client->requestAsync('POST', '/v1/chain/push_transaction', [
                    'json' => [
                        'actions' => [
                            [
                                'account' => 'your_account',
                                'name' => 'calculateEquity',
                                'authorization' => [
                                    [
                                        'actor' => 'your_account',
                                        'permission' => 'active',
                                    ]
                                ],
                                'data' => $params
                            ]
                        ]
                    ]
                ]);
            }
            // Wait for the promises to complete, even if some of them fail
            $results = Promise\settle($promises)->wait();
            foreach ($results as $result) {
                if ($result['state'] === 'fulfilled') {
                    $response = $result['value'];
                    $body = $response->getBody();
                    $result = json_decode($body, true);
                    if (isset($result['error'])) {
                        return response()->json(['error' => 'Smart contract interaction failed']);
                    }
                    // Update transaction with returned equity

                    DB::table('coin')->insert([
                        'date' => $result['newDate'],
                        'open' => $result['openPrice'],
                        'high' => $result['highPrice'],
                        'low' => $result['lowPrice'],
                        'close' => $result['closePrice'],
                        'id' => $result['newId'],
                    ]);
                    break;
                }
            }
    }
}


