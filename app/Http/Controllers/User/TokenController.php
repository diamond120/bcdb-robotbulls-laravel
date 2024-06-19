<?php

namespace App\Http\Controllers\User;

/**
 * Token Controller
 *
 *
 * @package TokenLite
 * @author Softnio
 * @version 1.0.5
 */
use Auth;
use DB;
use DateTime;
use Validator;
use IcoHandler;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Setting;
use App\Models\IcoStage;
use App\PayModule\Module;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Notifications\TnxStatus;
use App\Http\Controllers\Controller;
use App\Helpers\TokenCalculate as TC;
use Jenssegers\Agent\Agent;
use App\Models\Activity;

use Web3\Web3;
use Web3\ValueObjects\{Transaction2, Wei};
use Web3\Providers\HttpProvider;
use Web3\RequestManagers\HttpRequestManager;
use Web3\Methods\Eth;
use Elliptic\EC;

//eth
use Sop\CryptoTypes\Asymmetric\EC\ECPublicKey;
use Sop\CryptoTypes\Asymmetric\EC\ECPrivateKey;
use Sop\CryptoEncoding\PEM;
use kornrunner\Keccak;

use SWeb3\SWeb3;
use SWeb3\Utils;


use Web3\Contract;
use Web3\Utils as Utils2;
use Ethereum\DataType\EthereumRawTransaction;
use Ethereum\DataType\EthereumAddress;
use Ethereum\DataType\EthereumPrivateKey;

//encryption
use Illuminate\Support\Facades\Crypt;

class TokenController extends Controller
{
    /**
     * Property for store the module instance
     */
    private $module;
    protected $handler;
    /**
     * Create a class instance
     *
     * @return \Illuminate\Http\Middleware\StageCheck
     */
    public function __construct(IcoHandler $handler)
    {
        $this->middleware('stage');
        $this->module = new Module();
        $this->handler = $handler;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public function index()
    {

        include config('app.dir') . "/config_u.php";

        $trnxs_2 = Transaction::where('user', Auth::id())
                    ->where('status', '!=', 'deleted')
                    ->where('status', '!=', 'canceled')
                    ->where('status', '!=', 'pending')
                    ->where('status', '!=', 'expired') 
                    ->where('status', '!=', 'rejected')
                    ->where('status', '!=', 'inactive')
                    ->where('status', '!=', 'bull')
                    ->where('status', '!=', 'new')
                    ->where('receive_currency', '!=', 'usd')
                    ->where('receive_currency', '!=', 'eur')
                    ->where('receive_currency', '!=', 'gbp')
                    ->where('receive_currency', '!=', 'cad')
                    ->where('receive_currency', '!=', 'aud')
                    ->where('receive_currency', '!=', 'try')
                    ->where('receive_currency', '!=', 'rub')
                    ->where('receive_currency', '!=', 'inr')
                    ->where('receive_currency', '!=', 'myr')
                    ->where('receive_currency', '!=', 'idr')
                    ->where('receive_currency', '!=', 'ngn')
                    ->where('receive_currency', '!=', 'mxn')
                    ->where('receive_currency', '!=', 'php')
                    ->where('receive_currency', '!=', 'chf')
                    ->where('receive_currency', '!=', 'thb')
                    ->where('receive_currency', '!=', 'sgd')
                    ->where('receive_currency', '!=', 'czk')
                    ->where('receive_currency', '!=', 'nok')
                    ->where('receive_currency', '!=', 'zar')
                    ->where('receive_currency', '!=', 'sek')
                    ->where('receive_currency', '!=', 'kes')
                    ->where('receive_currency', '!=', 'nad')
                    ->where('receive_currency', '!=', 'dkk')
                    ->where('receive_currency', '!=', 'hkd')
                    ->where('receive_currency', '!=', 'eth')
                    ->where('receive_currency', '!=', 'btc')
                    ->where('receive_currency', '!=', 'ltc')
                    ->where('receive_currency', '!=', 'xrp')
                    ->where('receive_currency', '!=', 'xlm')
                    ->where('receive_currency', '!=', 'bch')
                    ->where('receive_currency', '!=', 'bnb')
                    ->where('receive_currency', '!=', 'usdt')
                    ->where('receive_currency', '!=', 'trx')
                    ->where('receive_currency', '!=', 'usdc')
                    ->where('receive_currency', '!=', 'dash')
                    ->where('receive_currency', '!=', 'waves')
                    ->where('receive_currency', '!=', 'xmr')
                    ->whereNotIn('tnx_type', ['withdraw'])
                    ->orderBy('created_at', 'DESC')->get();

        $balance_crypto = 0;
        $trnxs_index = count($trnxs_2);

        for($i = 0; $i < $trnxs_index; $i++){
            if($trnxs_2[$i]->receive_currency == 'rbc'){
                $balance_crypto = $balance_crypto + $trnxs_2[$i]->receive_amount;
            }
        }
        
        if($balance_crypto > 0 ) {
        
        $tc = new TC();
        $price = Setting::exchange_rate($tc->get_current_price());
        $contribution = Transaction::user_contribution();
        $has_sidebar = false;
        $content_class = 'col-lg-8';
        
        $currencies = Setting::active_currency();
        $currencies['base'] = base_currency();
        
        $t = new Datetime('19:30:00');
        $dbrow = DB::table('coin')->where('date', $t->format('U')."000")->first();   
        $current_price = $dbrow->close;
        
        //graph
        $OldDate = strtotime("2022-03-27");
        $NewDate = date('M j, Y', $OldDate);
        $diff = date_diff(date_create($NewDate),date_create(date("M j, Y")));

        $graph = array();
        
        for ($x = 1; $x <= intval($diff->format('%a')); $x++) {
            $ct = new Datetime("-" . $x . " day 19:30:00");
            $cdbrow = DB::table('coin')->where('date', $ct->format('U')."000")->first(); 
            
            array_push($graph, [$ct->format('U')."000", $cdbrow->open, $cdbrow->high, $cdbrow->low, $cdbrow->close ]);
        } 

        $min_token = ceil(1/$current_price*1000);
        $max_token = 1000000;
        
        $ico = IcoStage::findOrFail(1);
        $ico->base_price = $current_price;
        $save = $ico->save();
        
        //add to activity
        $agent = new Agent();
        $ret['activity'] = Activity::create([
            'user_id' => Auth::id(),
            'browser' => $agent->browser() . '/' . $agent->version($agent->browser()),
            'device' => $agent->device() . '/' . $agent->platform() . '-' . $agent->version($agent->platform()),
            'ip' => request()->ip(),
            'extra' => "token",
        ]);
        
        return view(
            'user.token',
            compact('price', 'currencies', 'contribution', 'current_price', 'lang', 'min_token', 'max_token', 'graph', 'has_sidebar', 'content_class')
        );
        
        } else {
            return redirect()->route('user.home');
        }
    }

    /**
     * Access the confirm and count
     *
     * @version 1.1
     * @since 1.0
     * @return void
     * @throws \Throwable
     */
    public function access(Request $request)
    {
        $tc = new TC();
        $min = 100;
        $currency = $request->input('currency');
        $token = (float) $request->input('token_amount');
        $ret['modal'] = '<a href="#" class="modal-close" data-dismiss="modal"><em class="ti ti-close"></em></a><div class="tranx-popup"><h3>' . __('messages.trnx.wrong') . '</h3></div>';
        $_data = [];
        
        $t = new Datetime('19:30:00');
        $dbrow = DB::table('coin')->where('date', $t->format('U')."000")->first();   
        $current_price = $dbrow->close;
        
        
                if (!empty($token) && $token >= $min) {
                    $_data = (object) [
                        'currency' => $currency,
                        'currency_rate' => Setting::exchange_rate($tc->get_current_price(), $currency),
                        'token' => round($token, min_decimal()),
                        'bonus_on_base' => 0,
                        'bonus_on_token' => 0,
                        'total_bonus' => 0,
                        'total_tokens' => $token,
                        'base_price' => $token,
                        'amount' => $token,
                    ];
                }
                if ($this->check($token)) {
                    if ($token < $min || $token == null) {
                        $ret['opt'] = 'true';
                        $ret['modal'] = view('modals.payment-amount', compact('currency', 'get'))->render();
                    } else {
                        $ret['opt'] = 'static';
                        $ret['ex'] = [$currency, $_data];
                        $ret['modal'] = $this->module->show_module($currency, $_data);
                    }
                } else {
                    $msg = $this->check(0, 'err');
                    $ret['modal'] = '<a href="#" class="modal-close" data-dismiss="modal"><em class="ti ti-close"></em></a><div class="popup-body"><h3 class="alert alert-danger text-center">'.$msg.'</h3></div>';
                }

        
        if ($request->ajax()) {
            return response()->json($ret);
        }
        return back()->with([$ret['msg'] => $ret['message']]);
    }

    /**
     * Make Payment
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public function payment(Request $request)
    {
        $ret['msg'] = 'info';
        $ret['message'] = __('messages.nothing');

        $user = Auth::user();
        
//        $validator = Validator::make($request->all(), [
//            'agree' => 'required',
//            'pp_token' => 'required',
//            'pp_currency' => 'required',
//            'pay_option' => 'required',
//        ], [
//            'pp_currency.required' => __('messages.trnx.require_currency'),
//            'pp_token.required' => __('messages.trnx.require_token'),
//            'pay_option.required' => __('messages.trnx.select_method'),
//            'agree.required' => __('messages.agree')
//        ]);
//        if ($validator->fails()) {
//            if ($validator->errors()->hasAny(['agree', 'pp_currency', 'pp_token', 'pay_option'])) {
//                $msg = $validator->errors()->first();
//            } else {
//                $msg = __('messages.form.wrong');
//            }
//
//            $ret['msg'] = 'warning';
//            $ret['message'] = $msg;
//        }else{
        
        $type = strtolower($request->input('pp_currency'));
        $method = strtolower($request->input('pay_option'));
        
        if ($method == "whitelisting_balance") {
            if ($user->whitelist_balance >= 5000) {
                if (isset($user->kyc_info) && isset($user->kyc_info->status)) {

                    $ret['msg'] = 'info';
                    $ret['message'] = __('messages.nothing');
                    $validator = Validator::make($request->all(), [
                        'total_tokens' => 'required|integer|min:1',
                    ], [
                        'total_tokens.required' => "Amount is required!.",
                    ]);

                        $tc = new TC();

                        $base_currency_rate = Setting::exchange_rate($tc->get_current_price(), $user->base_currency);
                        $all_currency_rate = json_encode(Setting::exchange_rate($tc->get_current_price(), 'except'));
                        $added_time = Carbon::now()->toDateTimeString();
                        $tnx_id = set_id(rand(100, 999), 'trnx');

                        $save_data = [
                            'created_at' => $added_time,
                            'tnx_id' => $tnx_id,
                            'tnx_type' => "purchase",
                            'tnx_time' => $added_time,
                            'tokens' => $request->input('pp_token'),
                            'bonus_on_base' => 0,
                            'bonus_on_token' => 0,
                            'total_bonus' => 0,
                            'total_tokens' => $request->input('pp_token'),
                            'stage' => 1,
                            'user' => $user->id,

                            'amount' => $request->input('pp_token'),
                            'receive_amount' => $request->input('pp_token'),
                            'receive_currency' => $user->base_currency,
                            'base_amount' => $request->input('pp_token'),
                            'base_currency' => $user->base_currency,
                            'base_currency_rate' => $base_currency_rate,
                            'currency' => $user->base_currency,

                            'all_currency_rate' => $all_currency_rate,
                            'payment_method' => "whitelisting_balance",
                            'payment_to' => '',
                            'payment_id' => rand(1000, 9999),
                            'details' => 'Token Purchase',
                            'status' => 'pending',
                            'duration' => $_COOKIE["duration"],
                            'plan' => $_COOKIE["plan"],
                            'percentage' => rand(931,2951)/10000,
                        ];

                        $iid = Transaction::insertGetId($save_data);

                        $user->whitelist_balance = (floatval($user->whitelist_balance) - floatval($request->input('pp_token'))); 
                        $user->save();
                    
                        if ($iid != null) {
                            $ret['msg'] = 'info';
                            $ret['message'] = __('messages.trnx.manual.success');

                            $transaction = Transaction::where('id', $iid)->first();
                            $transaction->status = "approved";
                            
                            if ($transaction->plan == "BTC Bull"){
                                $transaction->duration = "";
                            }
                            
                            $transaction->checked_time = now();
                            $transaction->save();

                            error_log( "\n Saved \n", 3, "/home/robotbq/app_rb_folder/storage/logs/php.log"); 
                            // Start adding
                            IcoStage::token_add_to_account($transaction, '', 'add');
                            $ret['link'] = route('user.home');
                            $ret['msg'] = 'success';
                            $ret['message'] = __('Transaction Successful');
                            error_log( "\n Saved2 \n", 3, "/home/robotbq/app_rb_folder/storage/logs/php.log"); 
                        } else {
                            $ret['link'] = route('user.token');
                            $ret['msg'] = 'error';
                            $ret['message'] = __('Transaction Failed');
                            Transaction::where('id', $iid)->delete();
                        }

                        if ($request->ajax()) {
                            return response()->json($ret);
                        }

                }
            }
            $ret['msg'] = 'warning';
            $ret['message'] = "An Error Has occured";
        } 
        else if ($method == "coinpayments") {
            //1. if user does not have wallet in currency create one
            //2. initiate pending transaction
            //3. return transaction->id, wallet_address, qr_code_url
            
            
            //1.
            // If user does not have a wallet create one
            $wallet_address = DB::table('wallets')
                ->where('user_id', $user->id)
                ->where('currency', "eth")
                ->first();
            if (!$wallet_address) {
                // create user wallet
                $request = new Request([
                    'currency' => "eth"
                ]);
                $wallet_address = $this->create_wallet($request);
            }
            $wallet_address = DB::table('wallets')
                ->where('user_id', $user->id)
                ->where('currency', "eth")
                ->first();
            
            if($wallet_address){
                $wallet_address = strval($wallet_address->wallet_address);
            }
            $wallet_address = Crypt::decryptString($wallet_address);
            
            $qr_code_url = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=".$wallet_address;
            
            //2. 
            // initiate pending transaction
            
            $tc = new TC();

            $base_currency_rate = Setting::exchange_rate($tc->get_current_price(), $user->base_currency);
            $all_currency_rate = json_encode(Setting::exchange_rate($tc->get_current_price(), 'except'));
            $added_time = Carbon::now()->toDateTimeString();
            $tnx_id = set_id(rand(100, 999), 'trnx');

            $tokens = $request->input('pp_token');
            $pmc_auto_rate = 'pmc_auto_rate_';
            $pmc_auto_rate .= strval($user->base_currency);
            $pmc_crypto_rate = 'pmc_auto_rate_';
            $pmc_crypto_rate .= strtolower($request->input('pp_currency'));
            
            $save_data = [
                'created_at' => $added_time,
                'tnx_id' => $tnx_id,
                'tnx_type' => "purchase",
                'tnx_time' => $added_time,
                'tokens' => $request->input('pp_token'),
                'bonus_on_base' => 0,
                'bonus_on_token' => 0,
                'total_bonus' => 0,
                'total_tokens' => $request->input('pp_token'),
                'stage' => 1,
                'user' => $user->id,
                'amount' => $tokens/floatval(get_setting($pmc_auto_rate))*floatval(get_setting($pmc_crypto_rate)),
                'receive_amount' => $request->input('pp_token'),
                'receive_currency' => strtolower($request->input('pp_currency')),
                'base_amount' => $request->input('pp_token'),
                'base_currency' => $user->base_currency,
                'base_currency_rate' => $base_currency_rate,
                'currency' => strtolower($request->input('pp_currency')),
                'all_currency_rate' => $all_currency_rate,
                'payment_method' => "crypto",
                'payment_to' => '',
                'payment_id' => rand(1000, 9999),
                'details' => 'Token Purchase',
                'status' => 'pending',
                'duration' => $_COOKIE["duration"],
                'plan' => $_COOKIE["plan"],
                'percentage' => rand(931,2951)/10000,
            ];
            
            $iid = Transaction::insertGetId($save_data);
            $trnx_id = $iid;
            
            //3. 
            // return transaction->id, wallet_address, qr_code_url
            
            include config('app.dir') . "/config_u.php";
            
            $ret['opt'] = 'true';
            $ret['modal'] = view('modals.success', compact('lang', 'trnx_id', 'wallet_address', 'qr_code_url'))->render();
            if ($request->ajax()) {
                return response()->json($ret);
            }
            return back()->with([$ret['msg'] => $ret['message']]);
                        
        } 
        else {
            error_log("\n[".date('d M Y H:i:s')."] 1. Non whitelisting balance ".json_encode($user->name)." \n", 3, "/home/robotbq/app_rb_folder/storage/logs/php.log");
            return $this->module->make_payment($method, $request);
        }
        
        
        
           
//        }
        if ($request->ajax()) {
            return response()->json($ret);
        }
        return back()->with([$ret['msg'] => $ret['message']]);
    }

    /**
     * Check the state
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    private function check($token, $extra = '')
    {
        $tc = new TC();
        $stg = active_stage();
        $min = $tc->get_current_price('min');
        $available_token = ( (double) $stg->total_tokens - ($stg->soldout + $stg->soldlock) );
        $symbol = token_symbol();

        if ($extra == 'err') {
            if ($token >= $min && $token <= $stg->max_purchase) {
                if ($token >= $min && $token > $stg->max_purchase) {
                    return __('Maximum amount reached, You can purchase maximum :amount :symbol per transaction.', ['amount' => $stg->max_purchase, 'symbol' =>$symbol]);
                } else {
                    return __('You must purchase minimum :amount :symbol.', ['amount' => $min, 'symbol' =>$symbol]);
                }
            } else {
                if($available_token < $min) {
                    return __('Our sales has been finished. Thank you very much for your interest.');
                } else {
                    if ($available_token >= $token) {
                        return __('Transaction is not available.');
                    } else {
                        return __('Available :amount :symbol only, You can purchase less than :amount :symbol Token.', ['amount' => $available_token, 'symbol' =>$symbol]);
                    }
                }
            }
        } else {
            if ($token >= $min && $token <= $stg->max_purchase) {
                if ($available_token >= $token) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }


    /**
     * Payment Cancel
     *
     * @version 1.0.0
     * @since 1.0.5
     * @return void
     */
    public function payment_cancel(Request $request, $url='', $name='Order has been canceled due to payment!')
    {
        if ($request->get('tnx_id') || $request->get('token')) {
            $id = $request->get('tnx_id');
            $pay_token = $request->get('token');
            if($pay_token != null){
                $pay_token = (starts_with($pay_token, 'EC-') ? str_replace('EC-', '', $pay_token) : $pay_token);
            }
            $apv_name = ucfirst($url);
            if(!empty($id)){
                $tnx = Transaction::where('id', $id)->first();
            }elseif(!empty($pay_token)){
                $tnx = Transaction::where('payment_id', $pay_token)->first();
                if(empty($tnx)){
                    $tnx =Transaction::where('extra', 'like', '%'.$pay_token.'%')->first();
                }
            }else{
                return redirect(route('user.token'))->with(['danger'=>"Sorry, we're unable to proceed the transaction. This transaction may deleted. Please contact with administrator.", 'modal'=>'danger']);
            }
            if($tnx){
                $_old_status = $tnx->status;
                if($_old_status == 'deleted' || $_old_status == 'canceled'){
                    $name = "Your transaction is already ".$_old_status.". Sorry, we're unable to proceed the transaction.";
                }elseif($_old_status == 'approved'){
                    $name = "Your transaction is already ".$_old_status.". Please check your account balance.";
                }elseif(!empty($tnx) && ($tnx->status == 'pending' || $tnx->status == 'onhold') && $tnx->user == auth()->id()) {
                    $tnx->status = 'canceled';
                    $tnx->checked_by = json_encode(['name'=>$apv_name, 'id'=>$pay_token]);
                    $tnx->checked_time = Carbon::now()->toDateTimeString();
                    $tnx->save();
                    IcoStage::token_add_to_account($tnx, 'sub');
                    $tnx->tnxUser->notify((new TnxStatus($tnx, 'canceled-user')));
                    if(get_emailt('order-rejected-admin', 'notify') == 1){
                        notify_admin($tnx, 'rejected-admin');
                    }
                }
            }else{
                $name = "Transaction is not found!!";
            }
        }else{
            $name = "Transaction id or key is not valid!";
        }
        return redirect(route('user.token'))->with(['danger'=>$name, 'modal'=>'danger']);
    }
    
    private function create_wallet(Request $request) {
        $user = Auth::user();
        
        $allowedCurrencies = ['eth', 'usdt', 'usdc'];

        $currency = $request->input('currency');

        if (!in_array($currency, $allowedCurrencies)) {
            throw new \InvalidArgumentException("Invalid currency provided.");
        }
        
        if ($currency == "eth" || $currency == "usdt" || $currency == "usdc") {
            // Set the configuration options for generating the EC key pair
            $config = [
                'private_key_type' => OPENSSL_KEYTYPE_EC,
                'curve_name' => 'secp256k1'
            ];
            // Generate a new EC key pair using OpenSSL
            if (!$ec_key_pair = openssl_pkey_new($config)) {
                echo 'ERROR: Failed to generate EC key pair: ' . openssl_error_string();
                exit;
            }
            // Export the private key from the key pair
            if (!openssl_pkey_export($ec_key_pair, $private_key)) {
                echo 'ERROR: Failed to export private key: ' . openssl_error_string();
                exit;
            }
            // Get the public key from the key pair
            if (!$key_details = openssl_pkey_get_details($ec_key_pair)) {
                echo 'ERROR: Failed to get key details: ' . openssl_error_string();
                exit;
            }
            $public_key = $key_details["key"];
            // Create a new PEM object from the exported private key
            if (!$private_key_pem = PEM::fromString($private_key)) {
                echo 'ERROR: Failed to create private key PEM object';
                exit;
            }
            // Convert the PEM object to an ECPrivateKey object
            if (!$ec_private_key = ECPrivateKey::fromPEM($private_key_pem)) {
                echo 'ERROR: Failed to convert private key PEM object to ECPrivateKey object';
                exit;
            }
            // Convert the ECPrivateKey object to an ASN1 structure
            if (!$ec_private_seq = $ec_private_key->toASN1()) {
                echo 'ERROR: Failed to convert ECPrivateKey object to ASN1 structure';
                exit;
            }
            // Get the private key and public key in hex format
            $private_key_hex = bin2hex($ec_private_seq->at(1)->asOctetString()->string());
            $private_key_length = strlen($private_key_hex) / 2;
            $public_key_hex = bin2hex($ec_private_seq->at(3)->asTagged()->asExplicit()->asBitString()->string());
            $public_key_length = strlen($public_key_hex) / 2;
            // Derive the RBC address from the public key
            // Every public key will always start with 0x04, so we need to remove the leading 0x04 in order to hash it correctly
            $public_key_hex_trimmed = substr($public_key_hex, 2);
            $public_key_length_trimmed = strlen($public_key_hex_trimmed) / 2;
            // Hash the public key using Keccak-256
            $hash = Keccak::hash(hex2bin($public_key_hex_trimmed), 256);
            // Get the last 20 bytes of the hash as the RBC address
            // Note: RBC address has 20 bytes length (40 hex characters long)
            $wallet_address = '0x' . substr($hash, -40);
            // Get the private key in hex format with "0x" prefix
            $wallet_private_key = '0x' . $private_key_hex;
            // Output the RBC wallet address and private key

            $privateKey = Crypt::encryptString($wallet_private_key);
            $publicKey = Crypt::encryptString($public_key_hex);
            $address = Crypt::encryptString($wallet_address);
        }
        
        
        //add to user
        DB::table('wallets')->updateOrInsert(
            ['id' => intval(DB::table('wallets')->count()+1), 'custom_wallet_name' => $user->name . "'s " . strtoupper($currency) . ' Wallet', 'currency' => $currency, 'amount' => '0', 'user_id' => $user->id, 'privatekey' => $privateKey, 'publickey' => $publicKey, 'wallet_address' => $address, 'created_at' => Carbon::now()->toDateTimeString()],
        ); 
    }
}
