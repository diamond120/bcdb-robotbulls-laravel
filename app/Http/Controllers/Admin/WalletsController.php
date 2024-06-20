<?php

namespace App\Http\Controllers\Admin;
/**
 * Users Controller
 *
 * @package TokenLite
 * @author Softnio
 * @version 1.1.3
 */
use Auth;
use Mail;
use Validator;
use App\Models\KYC;
use App\Models\User;
use App\Models\UserMeta;
use App\Mail\EmailToUser;
use App\Models\GlobalMeta;
use Illuminate\Http\Request;
use App\Notifications\Reset2FA;
use App\Notifications\ConfirmEmail;
use App\Http\Controllers\Controller;
use App\Notifications\PasswordResetByAdmin;
use Illuminate\Support\Facades\Hash;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Transaction;
use App\Helpers\TokenCalculate as TC;
use App\Models\Setting;
use App\Models\IcoStage;
use DB;
use App\Models\ClientMessage;
use Illuminate\Support\Facades\Crypt;

use SWeb3\SWeb3;
use SWeb3\Utils;

class WalletsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @version 1.1
     * @since 1.0
     * @return void
     */
    public function index(Request $request, $role = ''){
        if(auth()->user()->role == 'admin') {

            $role_data  = '';
            $per_page   = gmvl('user_per_page', 10);
            $order_by   = (gmvl('user_order_by', 'id')=='token') ? 'tokenBalance' : gmvl('user_order_by', 'id');
            $ordered    = gmvl('user_ordered', 'DESC');
            $is_page    = (empty($role) ? 'all' : $role);

            
            if ($request->s) {
                $searchTerm = $request->s;
                
                $wallets = DB::table('wallets')
                    ->where('id', 'like', '%' . $searchTerm . '%')
                    ->orWhere('custom_wallet_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('currency', 'like', '%' . $searchTerm . '%')
                    ->orWhere('amount', 'like', '%' . $searchTerm . '%')
                    ->orWhere('user_id', 'like', '%' . $searchTerm . '%')
                    ->orWhere('wallet_address', 'like', '%' . $searchTerm . '%')
                    ->orWhere('created_at', 'like', '%' . $searchTerm . '%')
                    ->get(['id', 'custom_wallet_name', 'currency', 'amount', 'user_id', 'publickey', 'wallet_address', 'created_at']);

            } else {
                $wallets = DB::table('wallets')->get(['id', 'custom_wallet_name', 'currency', 'amount', 'user_id', 'publickey', 'wallet_address', 'created_at']); 
            }

            $users = User::whereNotIn('status', ['deleted']);
            
            
            
            //$sweb3 = new SWeb3('https://rpc.ankr.com/eth');
            //$sweb3->chainId = 0x1;

            // Assuming you have the contract addresses for USDC and USDT
            $contractAddressUSDC = '0xA0b86991c6218b36c1d19D4a2e9Eb0cE3606eB48';
            $contractAddressUSDT = '0xdAC17F958D2ee523a2206206994597C13D831ec7';

//            foreach ($wallets as $wallet) {
//                $wallet->balanceETH = $this->getBalance($sweb3, Crypt::decryptString($wallet->wallet_address));
//                $wallet->balanceUSDC = $this->getBalance($sweb3, Crypt::decryptString($wallet->wallet_address), $contractAddressUSDC);
//                $wallet->balanceUSDT = $this->getBalance($sweb3, Crypt::decryptString($wallet->wallet_address), $contractAddressUSDT);
//            }
            
            return view('admin.wallets', compact('wallets', 'role_data', 'is_page', 'users'));


        } else {
            abort(404);
        }
    }
    
    
    private function getBalance($sweb3, $address, $contractAddress = null, $decimals = 18): ?float {
        try {
            if ($contractAddress) {
//                // ERC20 Token Balance
//                $data = '0x70a08231' . str_pad(substr($address, 2), 64, '0', STR_PAD_LEFT);
//                // Assuming $sweb3->call() should receive a string method name and array parameters for contract calls
//                $result = $sweb3->call('eth_call', [[
//                    'to' => $contractAddress,
//                    'data' => $data
//                ], 'latest'])->result;
                
                
                $apiKey = '2U4KNH6YFSTKAM4CV2MBF64VK6DZI878KC'; // Your Etherscan API Key

                $url = "https://api.etherscan.io/api?module=account&action=tokenbalance&contractaddress=$contractAddress&address=$address&tag=latest&apikey=$apiKey";
                // Initialize CURL:
                $ch = curl_init($url);
                // Set options:
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                // Fetch the data:
                $json = curl_exec($ch);
                curl_close($ch);
                // Decode the JSON object:
                $data = json_decode($json, true);
                // The returned value is in the smallest unit (wei), so we need to convert it to the main unit (ether)
                $balance = bcdiv($data['result'], bcpow('10', '6', 0), 6); // USDT and USDC have 6 decimal places
                // Output the balance:
                return $balance;


            } else {
                // ETH Balance
                $result = $sweb3->call('eth_getBalance', [$address, 'latest'])->result;
            }
            return hexdec($result) / pow(10, $decimals);
        } catch (Exception $e) {
            error_log("Error getting balance: " . $e->getMessage());
            return null;
        }
    }
    
    public function resetWhitelisting($userId) {
        $user = User::find($userId);
        if ($user) {
            $user->whitelisting_comptete = 0;
            $user->save();

            // Redirect back with a success message
            return redirect()->back()->with('success', 'Whitelisting reset successfully.');
        }

        // Redirect back with an error message if user not found
        return redirect()->back()->with('error', 'User not found.');
    }

 
}
