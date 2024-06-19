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

class InvestmentController extends Controller
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
    public function index() {


        $user = auth()->user();
        $bc = base_currency();
        $symbol = 'RBC';
        $default_method = token_method();
        $method = strtolower($default_method);
        $base_cur = $user->base_currency;
        $has_sidebar = false;
        $content_class = 'col-lg-8';




        $currencies = Setting::active_currency();
        $currencies['base'] = base_currency();


        $contribution = Transaction::user_contribution();
        $min_token = 5000;
        $max_token = 1000000;

        // Create an array to store the symbols and bulls
        $symbol_and_bull = [
            ['symbol_gspc', 'general_bull'],
            ['symbol_btc', 'crypto_bull'],
            ['symbol_eth', 'nft_bull'],
            ['symbol_metv', 'metaverse_bull'],
            ['symbol_bgrn', 'ecological_bull'],
            ['symbol_bcx', 'commodities_bull']
        ];

        // An empty array to store all results
        $results = [];

        // Call the function for each symbol and bull
        foreach ($symbol_and_bull as $pair) {
            $results[] = $this->getVariables($pair[0], $pair[1]);
        }
        
        //add to activity
        $agent = new Agent();
        $ret['activity'] = Activity::create([
            'user_id' => Auth::id(),
            'browser' => $agent->browser() . '/' . $agent->version($agent->browser()),
            'device' => $agent->device() . '/' . $agent->platform() . '-' . $agent->version($agent->platform()),
            'ip' => request()->ip(),
            'extra' => "investment",
        ]);

        include config('app.dir') . "/config_u.php";

        return view(
            'user.investment',
            array_merge(compact('currencies', 'min_token', 'max_token', 'contribution', 'lang', 'bc', 'symbol', 'default_method', 'method', 'base_cur', 'user', 'has_sidebar', 'content_class'), ...$results)
        );
    }

public function getVariables($symbol, $bull)
{
    // Define the date of three months ago and a year ago from now
    $startDate3m = Carbon::now()->subMonths(3);
    $startDate1y = Carbon::now()->subYear();

    // Get the count of records for the last three months and the last year
    $count3m = DB::table('bulls')->where('bull', $symbol)->whereDate('date', '>=', $startDate3m)->count();
    $count1y = DB::table('bulls')->where('bull', $symbol)->whereDate('date', '>=', $startDate1y)->count();

    // Calculate interval between each selected date
    $interval3m = intval($count3m / 12);
    $interval1y = intval($count1y / 12);

    // Initialize empty arrays to hold the dates and close values
    $dates3m = [];
    $dates1y = [];
    $close3mSymbol = [];
    $close1ySymbol = [];
    $close3mBull = [];
    $close1yBull = [];

    // Populate the arrays
    for ($i = 0; $i < min($count3m, 12 * $interval3m); $i += $interval3m) {
        $currentDate = $startDate3m->copy()->addMonths($i);
        $dates3m[] = DB::table('bulls')->where('bull', $symbol)->whereDate('date', '>=', $startDate3m)->skip($i)->take(1)->pluck('date')[0];
        $close3mSymbol[] = DB::table('bulls')->where('bull', $symbol)->whereDate('date', '>=', $startDate3m)->skip($i)->take(1)->pluck('close')[0];
//        $close3mBull[] = DB::table('bulls')->where('bull', $bull)->whereDate('date', '>=', $startDate3m)->whereDate('date', '<=', $currentDate)->sum('close');
    }
    
    // Get all the bulls for the past 3 months
    $bulls3m = DB::table('bulls')
        ->where('bull', $bull)
        ->whereDate('date', '>=', $startDate3m)
        ->orderBy('date')  // Ensure they're in date order
        ->get();
    $accumulatedClose = 0;
    $close3mBull_long = [];
    // Iterate through all the bulls
    foreach ($bulls3m as $bull2) {
        // Add the current close value to the accumulated total
        $accumulatedClose += $bull2->close;

        // Store the accumulated total in the result array
        $close3mBull_long[] = $accumulatedClose;
    }
    $length = count($close3mBull_long);
    $step = floor($length / 12); // Determine the step size
    $close3mBull = [];
    for ($i = 0; $i < $length; $i += $step) {
        $close3mBull[] = $close3mBull_long[$i];
    }
    // Make sure that we have exactly 12 points
    while(count($close3mBull) > 12) {
        array_pop($close3mBull);
    }
    

    for ($i = 0; $i < min($count1y, 12 * $interval1y); $i += $interval1y) {
        $currentDate = $startDate3m->copy()->addMonths($i);
        $dates1y[] = DB::table('bulls')->where('bull', $symbol)->whereDate('date', '>=', $startDate1y)->skip($i)->take(1)->pluck('date')[0];
        $close1ySymbol[] = DB::table('bulls')->where('bull', $symbol)->whereDate('date', '>=', $startDate1y)->skip($i)->take(1)->pluck('close')[0];
//        $close1yBull[] = DB::table('bulls')->where('bull', $bull)->whereDate('date', '>=', $startDate1y)->whereDate('date', '<=', $currentDate)->sum('close');
    }
    
    // Get all the bulls for the past 3 months
    $bulls1y = DB::table('bulls')
        ->where('bull', $bull)
        ->whereDate('date', '>=', $startDate1y)
        ->orderBy('date')  // Ensure they're in date order
        ->get();
    $accumulatedClose = 0;
    $close1yBull_long = [];
    // Iterate through all the bulls
    foreach ($bulls1y as $bull2) {
        // Add the current close value to the accumulated total
        $accumulatedClose += $bull2->close;

        // Store the accumulated total in the result array
        $close1yBull_long[] = $accumulatedClose;
    }
    $length = count($close1yBull_long);
    $step = floor($length / 12); // Determine the step size
    $close1yBull = [];
    for ($i = 0; $i < $length; $i += $step) {
        $close1yBull[] = $close1yBull_long[$i];
    }
    // Make sure that we have exactly 12 points
    while(count($close1yBull) > 12) {
        array_pop($close1yBull);
    }
    

    // Return the dates and close values
    return [
        'dates3m'.$symbol => $dates3m,
        'dates1y'.$symbol => $dates1y,
        'close3m'.$symbol => $close3mSymbol,
        'close1y'.$symbol => $close1ySymbol,
        'close3m'.$bull => $close3mBull,
        'close1y'.$bull => $close1yBull
    ];
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
        $min = 0;
        $currency = $request->input('currency');
        $token = (float) $request->input('token_amount');
        $plan = $request->input('plan');
        $duration = $request->input('duration');
        $ret['modal'] = '<a href="#" class="modal-close" data-dismiss="modal"><em class="ti ti-close"></em></a><div class="tranx-popup"><h3>' . __('messages.trnx.wrong') . '</h3></div>';
        $_data = [];
        
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
                        'plan' => $_COOKIE['plan'],
                        'duration' => $_COOKIE['duration'],
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

        $validator = Validator::make($request->all(), [
            'agree' => 'required',
            'pp_token' => 'required',
            'pp_currency' => 'required',
            'pay_option' => 'required',
        ], [
            'pp_currency.required' => __('messages.trnx.require_currency'),
            'pp_token.required' => __('messages.trnx.require_token'),
            'pay_option.required' => __('messages.trnx.select_method'),
            'agree.required' => __('messages.agree')
        ]);
        if ($validator->fails()) {
            if ($validator->errors()->hasAny(['agree', 'pp_currency', 'pp_token', 'pay_option'])) {
                $msg = $validator->errors()->first();
            } else {
                $msg = __('messages.form.wrong');
            }

            $ret['msg'] = 'warning';
            $ret['message'] = $msg;
        }else{
            if ($method == "usdt") {
                $request = new Request([
                    'currency' => 'usdt' // or whatever value you want
                ]);
                $this->create_wallet($request);
            } else {
                $type = strtolower($request->input('pp_currency'));
                $method = strtolower($request->input('pay_option'));
                return $this->module->make_payment($method, $request);
            }   
        }
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
                        return __('Trabsaction is not available.');
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
        if ($request->get('tnx_id') || $request->get('investment')) {
            $id = $request->get('tnx_id');
            $pay_token = $request->get('investment');
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
                return redirect(route('user.investment'))->with(['danger'=>"Sorry, we're unable to proceed the transaction. This transaction may deleted. Please contact with administrator.", 'modal'=>'danger']);
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
        return redirect(route('user.investment'))->with(['danger'=>$name, 'modal'=>'danger']);
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
            ['id' => intval(DB::table('wallet')->count()+1), 'custom_wallet_name' => $user->name . "'s " . strtoupper($currency) . ' Wallet', 'currency' => $currency, 'amount' => '0', 'user_id' => $user->id, 'privatekey' => $privateKey, 'publickey' => $publicKey, 'wallet_address' => $address, 'created_at' => Carbon::now()->toDateTimeString()],
        );   

    }
}
