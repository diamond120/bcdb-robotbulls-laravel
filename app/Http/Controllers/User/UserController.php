<?php

namespace App\Http\Controllers\User;

/**
 * User Controller
 *
 *
 * @package TokenLite
 * @author Softnio
 * @version 1.0.6
 */
use Auth;
use DB;
use DateTime;
use Validator;
use IcoHandler;
use Carbon\Carbon;
use App\Models\Page;
use App\Models\User;
use App\Models\IcoStage;
use App\Models\UserMeta;
use App\Models\Activity;
use App\Helpers\NioModule;
use App\Models\GlobalMeta;
use App\Models\Transaction;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;
use App\Notifications\PasswordChange;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Jenssegers\Agent\Agent;



//crypto

use Web3\Web3;
use Web3\ValueObjects\{Transaction2, Wei};
use Web3\Providers\HttpProvider;
use Web3\RequestManagers\HttpRequestManager;
use Web3\Methods\Eth;
use Elliptic\EC;


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

use Web3p\EthereumTx\Transaction as Transaction3;

use Illuminate\Support\Facades\Crypt;
use GuzzleHttp\Client;


class UserController extends Controller
{
    protected $handler;
    public function __construct(IcoHandler $handler)
    {
        $this->middleware('auth');
        $this->handler = $handler;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public function index() {
        
        include config('app.dir') . "/config_u.php";
        $user = Auth::user();
        $contribution = Transaction::user_contribution();
        $has_sidebar = false;
        $content_class = 'col-lg-8';
        
        $trnxs = Transaction::where('user', $user->id)
        ->where('status', '=', 'approved')
        ->whereNotIn('tnx_type', ['demo'])
        ->orderBy('created_at', 'DESC')->get();
        
        //rbc_price
        $t = new Datetime('19:30:00');
        $dbrow = DB::table('coin')->where('date', $t->format('U')."000")->first();   
        $current_price = $dbrow->close ?? 1;
        
        //get user equity
        $result = $user->equity;
        error_log($result);
        $equity = 0;//$result['equity'];
        $balance = 0;//$result['balance'];
        $name_of_asset_classes = [];//$result['name_of_asset_classes'];
        $balance_of_asset_classes = [];//$result['balance_of_asset_classes'];
        $equity_of_asset_classes = [];//$result['equity_of_asset_classes'];
        
        //2fa token
        $g2fa = new Google2FA();
        $google2fa_secret = $g2fa->generateSecretKey();
        $google2fa = $g2fa->getQRCodeUrl(
            site_info().'-'.$user->name,
            $user->email,
            $google2fa_secret
        );
        
        //add to activity
        $agent = new Agent();
        $ret['activity'] = Activity::create([
            'user_id' => Auth::id(),
            'browser' => $agent->browser() . '/' . $agent->version($agent->browser()),
            'device' => $agent->device() . '/' . $agent->platform() . '-' . $agent->version($agent->platform()),
            'ip' => request()->ip(),
            'extra' => "dashboard",
            'equity' => $equity,
        ]);
        
        //send money to main wallet if money on whitelisting balance
        
        
        //add base_currency if not exist
        if (!$user->base_currency) {
            if(substr( $user->mobile, 0, 3 ) === "+41"){
                $user->base_currency = 'chf';
            } else if(substr( $user->mobile, 0, 4 ) === "+376" || substr( $user->mobile, 0, 3 ) === "+43" || substr( $user->mobile, 0, 3 ) === "+32" || substr( $user->mobile, 0, 4 ) === "+357" || substr( $user->mobile, 0, 4 ) === "+372" || substr( $user->mobile, 0, 4 ) === "+358" || substr( $user->mobile, 0, 3 ) === "+33" || substr( $user->mobile, 0, 3 ) === "+49" || substr( $user->mobile, 0, 3 ) === "+30" || substr( $user->mobile, 0, 4 ) === "+353" || substr( $user->mobile, 0, 3 ) === "+39" || substr( $user->mobile, 0, 4 ) === "+383" || substr( $user->mobile, 0, 4 ) === "+371" || substr( $user->mobile, 0, 4 ) === "+370" || substr( $user->mobile, 0, 4 ) === "+352" || substr( $user->mobile, 0, 4 ) === "+356" || substr( $user->mobile, 0, 4 ) === "+377" || substr( $user->mobile, 0, 4 ) === "+382" || substr( $user->mobile, 0, 3 ) === "+31" || substr( $user->mobile, 0, 4 ) === "+351" || substr( $user->mobile, 0, 4 ) === "+378" || substr( $user->mobile, 0, 34) === "+421" || substr( $user->mobile, 0, 4 ) === "+386" || substr( $user->mobile, 0, 3 ) === "+34" || substr( $user->mobile, 0, 4 ) === "+379" || substr( $user->mobile, 0, 4 ) === "+359" ){
                $user->base_currency = 'eur';
            } else if(substr( $user->mobile, 0, 2 ) === "+1"){
                $user->base_currency = 'usd';
            } else if(substr( $user->mobile, 0, 3 ) === "+90"){
                $user->base_currency = 'try';
            } else if(substr( $user->mobile, 0, 3 ) === "+81"){
                $user->base_currency = 'jpy';
            } else if(substr( $user->mobile, 0, 3 ) === "+243"){
                $user->base_currency = 'cdf';
            }
            
            $user->save();
        }
          
        //check for 2fa popup
        if( (!isset($_COOKIE["questionnaire"])) && $user->google2fa != 1) {
            return view('user.2fa_popup', compact('user', 'contribution', 'current_price', 'lang', 'google2fa', 'google2fa_secret', 'trnxs'));
        }
        if(isset($_COOKIE["questionnaire"]) || $user->google2fa == 1) {
            return view('user.dashboard', compact('user', 'contribution', 'current_price', 'lang', 'google2fa', 'google2fa_secret', 'trnxs', 'equity', 'balance', 'name_of_asset_classes', 'balance_of_asset_classes', 'equity_of_asset_classes'));
        }
    }
    
    
    private function dateDiffInDays($date1, $date2) {
        // Calculating the difference in timestamps
        $diff = strtotime($date2) - strtotime($date1);
        // 1 day = 24 hours
        // 24 * 60 * 60 = 86400 seconds
        return abs(round($diff / 86400));
    }


    /**
     * Show the user account page.
     *
     * @return \Illuminate\Http\Response
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public function account()
    {
        include config('app.dir') . "/config_u.php";
        $countries = $this->handler->getCountries();
        $user = Auth::user();
        $userMeta = UserMeta::getMeta($user->id);
        $has_sidebar = false;
        $content_class = 'col-lg-8';

        $g2fa = new Google2FA();
        $google2fa_secret = $g2fa->generateSecretKey();
        $google2fa = $g2fa->getQRCodeUrl(
            site_info().'-'.$user->name,
            $user->email,
            $google2fa_secret
        );

        //add to activity
        $agent = new Agent();
        $ret['activity'] = Activity::create([
            'user_id' => Auth::id(),
            'browser' => $agent->browser() . '/' . $agent->version($agent->browser()),
            'device' => $agent->device() . '/' . $agent->platform() . '-' . $agent->version($agent->platform()),
            'ip' => request()->ip(),
            'extra' => "profile",
        ]);
        
        return view('user.account', compact('user', 'userMeta','countries', 'google2fa', 'google2fa_secret', 'lang', 'has_sidebar', 'content_class'));
    }

    /**
     * Show the user account activity page.
     * and Delete Activity
     *
     * @return \Illuminate\Http\Response
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public function account_activity()
    {
        $user = Auth::user();
        include config('app.dir') . "/config_u.php";
        $activities = Activity::where('user_id', $user->id)->orderBy('created_at', 'DESC')->limit(50)->get();

        //add to activity
        $agent = new Agent();
        $ret['activity'] = Activity::create([
            'user_id' => Auth::id(),
            'browser' => $agent->browser() . '/' . $agent->version($agent->browser()),
            'device' => $agent->device() . '/' . $agent->platform() . '-' . $agent->version($agent->platform()),
            'ip' => request()->ip(),
            'extra' => "activities",
        ]);
        
        return view('user.activity', compact('user', 'activities', 'lang'));
    }

    /**
     * Show the user account token management page.
     *
     * @return \Illuminate\Http\Response
     * @version 1.0.0
     * @since 1.1.2
     * @return void
     */
    public function mytoken_balance()
    {
        if(gws('user_mytoken_page')!=1) {
            return redirect()->route('user.home');
        }
        include config('app.dir') . "/config_u.php";
        $user = Auth::user();
        $contribution = Transaction::user_contribution();
        $token_account = Transaction::user_mytoken('balance');
        $token_stages = Transaction::user_mytoken('stages');
        $user_modules = nio_module()->user_modules();
        
        
        //rbc_price
        $t = new Datetime('19:30:00');
        $dbrow = DB::table('coin')->where('date', $t->format('U')."000")->first();   
        $current_price = $dbrow->close;
        //get equity
        $result = $this->get_user_equity();
        $name_of_asset_classes = $result['name_of_asset_classes'];
        $balance_of_asset_classes = $result['balance_of_asset_classes'];
        $equity_of_asset_classes = $result['equity_of_asset_classes'];
        $balance_currency_of_asset_classes = $result['balance_currency_of_asset_classes'];
        $tnx_type_of_asset_classes = $result['tnx_type_of_asset_classes'];
        
        
        Transaction::where(['user' => auth()->id(), 'status' => 'new'])->delete();
        $trnxs = Transaction::where('user', Auth::id())
                    ->where('status', '!=', 'new')
                    ->where('status', '!=', 'deleted')
                    ->where('status', '!=', 'canceled')
                    ->where('status', '!=', 'pending')
                    ->where('status', '=', 'approved')
                    ->whereNotIn('tnx_type', ['demo'])
                    ->orderBy('created_at', 'DESC')->get();
        $transfers = Transaction::get_by_own(['tnx_type' => 'transfer'])->get()->count();
        $referrals = Transaction::get_by_own(['tnx_type' => 'referral'])->get()->count();
        $bonuses   = Transaction::get_by_own(['tnx_type' => 'bonus'])->get()->count();
        $refunds   = Transaction::get_by_own(['tnx_type' => 'refund'])->get()->count();
        $has_trnxs = (object) [
            'transfer' => ($transfers > 0) ? true : false,
            'referral' => ($referrals > 0) ? true : false,
            'bonus' => ($bonuses > 0) ? true : false,
            'refund' => ($refunds > 0) ? true : false
        ];
        
        //add to activity
        $agent = new Agent();
        $ret['activity'] = Activity::create([
            'user_id' => Auth::id(),
            'browser' => $agent->browser() . '/' . $agent->version($agent->browser()),
            'device' => $agent->device() . '/' . $agent->platform() . '-' . $agent->version($agent->platform()),
            'ip' => request()->ip(),
            'extra' => "portfolio",
        ]);
        
        
        
        $receive_currency = 'pmc_auto_rate_';
        $receive_currency .= strval( $user->base_currency );
        $date = Carbon::createFromDate(null, 12, 31)->subYear()->format('Y-m-d');
        $tax_date = date("Y") - 1;
        
        $date_start = (new DateTime('last year'))->format('Y') . '-12-26 00:00:00';
        $date_end = (new DateTime('last year'))->modify('+1 year')->format('Y') . '-01-05 23:59:59';
        $amount = Activity::whereBetween('created_at', [$date_start, $date_end])
                          ->where('user_id', Auth::id())
                          ->whereNotNull('equity')
                          ->orderBy('created_at', 'asc')
                          ->value('equity');
        $tax_amount = $amount;

        
//        $t2 = new DateTime('last year December 31 19:30:00');
//        $dbrow2 = DB::table('coin')->where('date', $t2->format('U')."000")->first();
//        $tax_rbc_price = $dbrow2->close;

        $tax_rbc_price = 10.39 * get_setting($receive_currency);
        
        
        $tax_amount_rbc = round(intval($tax_amount) / $tax_rbc_price);

        if ($user->base_currency == "chf") {
            $exchange_rate = 1.19;
        } else if ($user->base_currency == "eur") {
            $exchange_rate = 1.1;
        } else if ($user->base_currency == "usd") {
            $exchange_rate = 1;
        } else {
            $exchange_rate = false;
        }
        
        if ($exchange_rate != false) {
            $usdt_amount = round($tax_amount*$exchange_rate);
        } else {
            $usdt_amount = false;
        }
        
        return view('user.account-token', compact('user', 'current_price', 'contribution', 'trnxs', 'has_trnxs', 'token_account', 'token_stages', 'user_modules', 'lang', 'name_of_asset_classes', 'balance_of_asset_classes', 'equity_of_asset_classes', 'balance_currency_of_asset_classes', 'tnx_type_of_asset_classes', 'tax_date', 'tax_amount', 'tax_rbc_price', 'tax_amount_rbc', 'usdt_amount'));
    }


    /**
     * submit questionaire
     *
     */
    
     public function submit_questionnaire()
    {
        $user = Auth::user();
        $user->questionaire_filled = "true";
        
//    $q1 = $_GET['form-q1'];
//    $q2 = $_GET['form-q2'];
//    $q3 = $_GET['form-q3'];
//    $q4 = $_GET['form-q4'];
//    $q5 = $_GET['form-q5'];
//    $q1 = stripslashes(trim($_POST['form-q1']));
//    $q2 = stripslashes(trim($_POST['form-q2']));
//    $q3 = stripslashes(trim($_POST['form-q3']));
//    $q4 = stripslashes(trim($_POST['form-q4']));
//    $q5 = stripslashes(trim($_POST['form-q5']));
//         
         
//         $dom = DOMDocument::loadHTML($html);
  
  
         $user->q1 = $_GET['form-q1'];
         $user->q2 = $_GET['form-q2'];
         $user->q3 = $_GET['form-q3'];
         $user->q4 = $_GET['form-q4'];
         $user->q5 = $_GET['form-q5'];
         $user->q6 = $_GET['form-q6'];
//         $user->q2 = $q2;
//         $user->q3 = $q3;
//         $user->q4 = $q4;
//         $user->q5 = $q5;
         
        $user->save();

         $actual_link_questionaire = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $new_link_questionaire = str_replace("/submit/questionnaire","",$actual_link_questionaire);
        $new_link2_questionaire = substr($new_link_questionaire, 0, strpos($new_link_questionaire, "?"));
        
        header("LOCATION: " . $new_link2_questionaire);
         
    }
    
    
    /**
     * Activity delete
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public function account_activity_delete(Request $request)
    {
        $id = $request->input('delete_activity');
        $ret['msg'] = 'info';
        $ret['message'] = "Nothing to do!";

        if ($id !== 'all') {
            $remove = Activity::where('id', $id)->where('user_id', Auth::id())->delete();
        } else {
            $remove = Activity::where('user_id', Auth::id())->delete();
        }
        if ($remove) {
            $ret['msg'] = 'success';
            $ret['message'] = __('messages.delete.delete', ['what'=>'Activity']);
        } else {
            $ret['msg'] = 'warning';
            $ret['message'] = __('messages.form.wrong');
        }
        if ($request->ajax()) {
            return response()->json($ret);
        }
        return back()->with([$ret['msg'] => $ret['message']]);
    }

    /**
     * update the user account page.
     *
     * @return \Illuminate\Http\Response
     * @version 1.2
     * @since 1.0
     * @return void
     */
    public function account_update(Request $request)
    {
        $type = $request->input('action_type');
        $ret['msg'] = 'info';
        $ret['message'] = __('messages.nothing');

        if ($type == 'personal_data') {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:3',
                'phone' => 'required|min:5',
                'dateOfBirth' => 'required|date_format:"m/d/Y"'
            ]);

            if ($validator->fails()) {
                $msg = __('messages.form.wrong');
                if ($validator->errors()->hasAny(['name', 'phone', 'dateOfBirth'])) {
                    $msg = $validator->errors()->first();
                }

                $ret['msg'] = 'warning';
                $ret['message'] = $msg;
                return response()->json($ret);
            } else {
                $user = User::FindOrFail(Auth::id());
                $user->name = strip_tags($request->input('name'));
                $user->email = $request->input('phone');
                $user->mobile = strip_tags($request->input('mobile'));
                $user->dateOfBirth = $request->input('dateOfBirth');
                $user->nationality = strip_tags($request->input('nationality'));
                $user->base_currency = strip_tags($request->input('currency'));
                $user_saved = $user->save();

                if ($user) {
                    $ret['msg'] = 'success';
                    $ret['message'] = __('messages.update.success', ['what' => 'Account']);
                } else {
                    $ret['msg'] = 'warning';
                    $ret['message'] = __('messages.update.warning');
                }
            }
        }
        if ($type == 'wallet') {
            $validator = Validator::make($request->all(), [
                'wallet_name' => 'required',
                'wallet_address' => 'required|min:10'
            ]);

            if ($validator->fails()) {
                $msg = __('messages.form.wrong');
                if ($validator->errors()->hasAny(['wallet_name', 'wallet_address'])) {
                    $msg = $validator->errors()->first();
                }

                $ret['msg'] = 'warning';
                $ret['message'] = $msg;
                return response()->json($ret);
            } else {
                $is_valid = $this->handler->validate_address($request->input('wallet_address'), $request->input('wallet_name'));
                if ($is_valid) {
                    $user = User::FindOrFail(Auth::id());
                    $user->walletType = $request->input('wallet_name');
                    $user->walletAddress = $request->input('wallet_address');
                    $user_saved = $user->save();

                    if ($user) {
                        $ret['msg'] = 'success';
                        $ret['message'] = __('messages.update.success', ['what' => 'Wallet']);
                    } else {
                        $ret['msg'] = 'warning';
                        $ret['message'] = __('messages.update.warning');
                    }
                } else {
                    $ret['msg'] = 'warning';
                    $ret['message'] = __('messages.invalid.address');
                }
            }
        }
        if ($type == 'wallet_request') {
            $validator = Validator::make($request->all(), [
                'wallet_name' => 'required',
                'wallet_address' => 'required|min:10'
            ]);

            if ($validator->fails()) {
                $msg = __('messages.form.wrong');
                if ($validator->errors()->hasAny(['wallet_name', 'wallet_address'])) {
                    $msg = $validator->errors()->first();
                }

                $ret['msg'] = 'warning';
                $ret['message'] = $msg;
                return response()->json($ret);
            } else {
                $is_valid = $this->handler->validate_address($request->input('wallet_address'), $request->input('wallet_name'));
                if ($is_valid) {
                    $meta_data = ['name' => $request->input('wallet_name'), 'address' => $request->input('wallet_address')];
                    $meta_request = GlobalMeta::save_meta('user_wallet_address_change_request', json_encode($meta_data), auth()->id());

                    if ($meta_request) {
                        $ret['msg'] = 'success';
                        $ret['message'] = __('messages.wallet.change');
                    } else {
                        $ret['msg'] = 'warning';
                        $ret['message'] = __('messages.wallet.failed');
                    }
                } else {
                    $ret['msg'] = 'warning';
                    $ret['message'] = __('messages.invalid.address');
                }
            }
        }
        if ($type == 'notification') {
            $notify_admin = $newsletter = $unusual = 0;

            if (isset($request['notify_admin'])) {
                $notify_admin = 1;
            }
            if (isset($request['newsletter'])) {
                $newsletter = 1;
            }
            if (isset($request['unusual'])) {
                $unusual = 1;
            }

            $user = User::FindOrFail(Auth::id());
            if ($user) {
                $userMeta = UserMeta::where('userId', $user->id)->first();
                if ($userMeta == null) {
                    $userMeta = new UserMeta();
                    $userMeta->userId = $user->id;
                }
                $userMeta->notify_admin = $notify_admin;
                $userMeta->newsletter = $newsletter;
                $userMeta->unusual = $unusual;
                $userMeta->save();
                $ret['msg'] = 'success';
                $ret['message'] = __('messages.update.success', ['what' => 'Notification']);
            } else {
                $ret['msg'] = 'warning';
                $ret['message'] = __('messages.update.warning');
            }
        }
        if ($type == 'security') {
            $save_activity = $mail_pwd = 'FALSE';

            if (isset($request['save_activity'])) {
                $save_activity = 'TRUE';
            }
            if (isset($request['mail_pwd'])) {
                $mail_pwd = 'TRUE';
            }

            $user = User::FindOrFail(Auth::id());
            if ($user) {
                $userMeta = UserMeta::where('userId', $user->id)->first();
                if ($userMeta == null) {
                    $userMeta = new UserMeta();
                    $userMeta->userId = $user->id;
                }
                $userMeta->pwd_chng = $mail_pwd;
                $userMeta->save_activity = $save_activity;
                $userMeta->save();
                $ret['msg'] = 'success';
                $ret['message'] = __('messages.update.success', ['what' => 'Security']);
            } else {
                $ret['msg'] = 'warning';
                $ret['message'] = __('messages.update.warning');
            }
        }
        if ($type == 'account_setting') {
            $notify_admin = $newsletter = $unusual = 0;
            $save_activity = $mail_pwd = 'FALSE';
            $user = User::FindOrFail(Auth::id());

            if (isset($request['save_activity'])) {
                $save_activity = 'TRUE';
            }
            if (isset($request['mail_pwd'])) {
                $mail_pwd = 'TRUE';
            }

            $mail_pwd = 'TRUE'; //by default true
            if (isset($request['notify_admin'])) {
                $notify_admin = 1;
            }
            if (isset($request['newsletter'])) {
                $newsletter = 1;
            }
            if (isset($request['unusual'])) {
                $unusual = 1;
            }


            if ($user) {
                $userMeta = UserMeta::where('userId', $user->id)->first();
                if ($userMeta == null) {
                    $userMeta = new UserMeta();
                    $userMeta->userId = $user->id;
                }

                $userMeta->notify_admin = $notify_admin;
                $userMeta->newsletter = $newsletter;
                $userMeta->unusual = $unusual;

                $userMeta->pwd_chng = $mail_pwd;
                $userMeta->save_activity = $save_activity;

                $userMeta->save();
                $ret['msg'] = 'success';
                $ret['message'] = __('messages.update.success', ['what' => 'Account Settings']);
            } else {
                $ret['msg'] = 'warning';
                $ret['message'] = __('messages.update.warning');
            }
        }
        if ($type == 'pwd_change') {
            //validate data
            $validator = Validator::make($request->all(), [
                'old-password' => 'required|min:6',
                'new-password' => 'required|min:6',
                're-password' => 'required|min:6|same:new-password',
            ]);
            if ($validator->fails()) {
                $msg = __('messages.form.wrong');
                if ($validator->errors()->hasAny(['old-password', 'new-password', 're-password'])) {
                    $msg = $validator->errors()->first();
                }

                $ret['msg'] = 'warning';
                $ret['message'] = $msg;
                return response()->json($ret);
            } else {
                $user = Auth::user();
                if ($user) {
                    if (! Hash::check($request->input('old-password'), $user->password)) {
                        $ret['msg'] = 'warning';
                        $ret['message'] = __('messages.password.old_err');
                    } else {
                        $userMeta = UserMeta::where('userId', $user->id)->first();
                        $userMeta->pwd_temp = Hash::make($request->input('new-password'));
                        $cd = Carbon::now();
                        $userMeta->email_expire = $cd->copy()->addMinutes(60);
                        $userMeta->email_token = str_random(65);
                        if ($userMeta->save()) {
                            try {
                                $user->notify(new PasswordChange($user, $userMeta));
                                $ret['msg'] = 'success';
                                $ret['message'] = __('messages.password.changed');
                            } catch (\Exception $e) {
                                $ret['msg'] = 'warning';
                                $ret['message'] = __('messages.email.password_change',['email' => get_setting('site_email')]);
                            }
                        } else {
                            $ret['msg'] = 'warning';
                            $ret['message'] = __('messages.form.wrong');
                        }
                    }
                } else {
                    $ret['msg'] = 'warning';
                    $ret['message'] = __('messages.form.wrong');
                }
            }
        }
        if($type == 'google2fa_setup'){
            $google2fa = $request->input('google2fa', 0);
            $user = User::FindOrFail(Auth::id());
            if($user){
                // Google 2FA
                $ret['link'] = route('user.account');
                if(!empty($request->google2fa_code)){
                    $g2fa = new Google2FA();
                    if($google2fa == 1){
                        $verify = $g2fa->verifyKey($request->google2fa_secret, $request->google2fa_code);
                    }else{
                        $verify = $g2fa->verify($request->google2fa_code, $user->google2fa_secret);
                    }

                    if($verify){
                        $user->google2fa = $google2fa;
                        $user->google2fa_secret = ($google2fa == 1 ? $request->google2fa_secret : null);
                        $user->save();
                        $ret['msg'] = 'success';
                        $ret['message'] = __('You successfully '.($google2fa == 1 ? 'enabled' : 'disabled').' 2FA in your account.');
                    }else{
                        $ret['msg'] = 'error';
                        $ret['message'] = __('You have provided an invalid 2FA authentication code!');
                    }
                }else{
                    $ret['msg'] = 'warning';
                    $ret['message'] = __('Please enter a valid authentication code!');
                }
            }
        }

        if ($request->ajax()) {
            return response()->json($ret);
        }
        return back()->with([$ret['msg'] => $ret['message']]);
    }

    
    
//    public function account_whitelisting(Request $request)
//    {
//        $type = $request->input('action_type');
//        $ret['msg'] = 'info';
//        $ret['message'] = __('messages.nothing');
//    }
    
    
    
    public function password_confirm($token)
    {
        $user = Auth::user();
        $userMeta = UserMeta::where('userId', $user->id)->first();
        if ($token == $userMeta->email_token) {
            if (_date($userMeta->email_expire, 'Y-m-d H:i:s') >= date('Y-m-d H:i:s')) {
                $user->password = $userMeta->pwd_temp;
                $user->save();
                $userMeta->pwd_temp = null;
                $userMeta->email_token = null;
                $userMeta->email_expire = null;
                $userMeta->save();

                $ret['msg'] = 'success';
                $ret['message'] = __('messages.password.success');
            } else {
                $ret['msg'] = 'warning';
                $ret['message'] = __('messages.password.failed');
            }
        } else {
            $ret['msg'] = 'warning';
            $ret['message'] = __('messages.password.token');
        }

        return redirect()->route('user.account')->with([$ret['msg'] => $ret['message']]);
    }

    /**
     * Get pay now form
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public function get_wallet_form(Request $request)
    {
        include config('app.dir') . "/config_u.php";
        
        //add to activity
        $agent = new Agent();
        $ret['activity'] = Activity::create([
            'user_id' => Auth::id(),
            'browser' => $agent->browser() . '/' . $agent->version($agent->browser()),
            'device' => $agent->device() . '/' . $agent->platform() . '-' . $agent->version($agent->platform()),
            'ip' => request()->ip(),
            'extra' => "wallet_form",
        ]);
        
        return view('modals.user_wallet', compact('lang'))->render();
    }
    
    public function whitelisting_form(Request $request)
    {
        include config('app.dir') . "/config_u.php";
        
        //add to activity
        $agent = new Agent();
        $ret['activity'] = Activity::create([
            'user_id' => Auth::id(),
            'browser' => $agent->browser() . '/' . $agent->version($agent->browser()),
            'device' => $agent->device() . '/' . $agent->platform() . '-' . $agent->version($agent->platform()),
            'ip' => request()->ip(),
            'extra' => "whitelisting_form",
        ]);
        
        return view('modals.user_whitelisting', compact('lang'))->render();
    }
    
    public function get_key_form(Request $request)
    {
        include config('app.dir') . "/config_u.php";
        
        //add to activity
        $agent = new Agent();
        $ret['activity'] = Activity::create([
            'user_id' => Auth::id(),
            'browser' => $agent->browser() . '/' . $agent->version($agent->browser()),
            'device' => $agent->device() . '/' . $agent->platform() . '-' . $agent->version($agent->platform()),
            'ip' => request()->ip(),
            'extra' => "get_key_form",
        ]);
        
        return view('modals.user_wallet_key', compact('lang'))->render();
    }
    
    public function messages_form(Request $request)
    {
        include config('app.dir') . "/config_u.php";
        
        //add to activity
        $agent = new Agent();
        $ret['activity'] = Activity::create([
            'user_id' => Auth::id(),
            'browser' => $agent->browser() . '/' . $agent->version($agent->browser()),
            'device' => $agent->device() . '/' . $agent->platform() . '-' . $agent->version($agent->platform()),
            'ip' => request()->ip(),
            'extra' => "messages",
        ]);
        
        return view('modals.user_messages', compact('lang'))->render();
    }
    
    
    public function white_paper(Request $request)
    {
        include config('app.dir') . "/config_u.php";
        
        //add to activity
        $agent = new Agent();
        $ret['activity'] = Activity::create([
            'user_id' => Auth::id(),
            'browser' => $agent->browser() . '/' . $agent->version($agent->browser()),
            'device' => $agent->device() . '/' . $agent->platform() . '-' . $agent->version($agent->platform()),
            'ip' => request()->ip(),
            'extra' => "white_paper",
        ]);
        
        return view('modals.white_paper', compact('lang'))->render();
    }

    /**
     * Show the user Referral page
     *
     * @version 1.0.0
     * @since 1.0.3
     * @return void
     */
    public function referral()
    {
        include config('app.dir') . "/config_u.php";
        $page = Page::where('slug', 'referral')->where('status', 'active')->first();
        $reffered = User::where('referral', auth()->id())->get();
        if(get_page('referral', 'status') == 'active'){
            
            //add to activity
            $agent = new Agent();
            $ret['activity'] = Activity::create([
                'user_id' => Auth::id(),
                'browser' => $agent->browser() . '/' . $agent->version($agent->browser()),
                'device' => $agent->device() . '/' . $agent->platform() . '-' . $agent->version($agent->platform()),
                'ip' => request()->ip(),
                'extra' => "referral",
            ]);
            
            return view('user.referral', compact('page', 'reffered', 'lang'));
        }else{
            abort(404);
        }
    }
    
    
    public function buy_crypto()
    {
        include config('app.dir') . "/config_u.php";

        //add to activity
        $agent = new Agent();
        $ret['activity'] = Activity::create([
            'user_id' => Auth::id(),
            'browser' => $agent->browser() . '/' . $agent->version($agent->browser()),
            'device' => $agent->device() . '/' . $agent->platform() . '-' . $agent->version($agent->platform()),
            'ip' => request()->ip(),
            'extra' => "buy_crypto",
        ]);
        
        return view('user.buycrypto', compact('lang'));
            
    }
    
    
    //The goal is to send X+Y funds from main wallet to user wallet and that the user wallet can send X funds to $address
    
    //check if user has a wallet if not make one.
    //get amount
    //get total amount to send with Gas fees for user wallet 
    //send from main wallet to user wallet if main wallet has enough funds otherwise return an error to the blade
    //wait until monet is received 
    //send from user wallet to $address
    //if transactions successful send success message otherwise send error message
    
    

    
    private function getBalance($sweb3, $address, $contractAddress = null, $decimals = 18): ?float {
        try {
            if ($contractAddress) {
                // ERC20 Token Balance
                $data = '0x70a08231' . str_pad(substr($address, 2), 64, '0', STR_PAD_LEFT);
                // Assuming $sweb3->call() should receive a string method name and array parameters for contract calls
                $result = $sweb3->call('eth_call', [[
                    'to' => $contractAddress,
                    'data' => $data
                ], 'latest'])->result;
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

            $publicKey = Crypt::encryptString($public_key_hex);
            $address = Crypt::encryptString($wallet_address);
        }
        
        
        //add to user
        DB::table('wallets')->updateOrInsert(
            ['id' => intval(DB::table('wallets')->count()+1), 'custom_wallet_name' => $user->name . "'s-" . strtoupper($currency) . '-Wallet', 'currency' => $currency, 'amount' => '0', 'user_id' => $user->id, 'publickey' => $publicKey, 'wallet_address' => $address, 'created_at' => Carbon::now()->toDateTimeString()],
        );   

    }
        
        
    private function is_transaction_confirmed($txHash) {
        try {
            $web3 = new Web3('https://rpc.ankr.com/eth');
            $transaction = $web3->eth->getTransaction($txHash);
            // Directly return the boolean result of the condition
            return ($transaction && isset($transaction->blockNumber));
        } catch (Exception $e) {
            // Handle any exceptions that occur during the RPC call
            error_log($e->getMessage());
            return false;
        }
    }

    private function stripZeroes($hexValue) {
        if (strlen($hexValue) % 2 != 0) {
            $hexValue = '0' . $hexValue;
        }
        return $hexValue ?: '0';
    }
     

    private function sendETH($from, $privateKey, $to, $amount, $muliplier)
    {
        error_log( "Transaction Started \n", 3, "/home/robotbq/app_rb_folder/storage/logs/php.log"); 
        $provider = new HttpProvider(new HttpRequestManager('https://rpc.ankr.com/eth', 5));
        $web3 = new Web3($provider);
        $eth = $web3->eth;
        
        error_log( "Test1 \n", 3, "/home/robotbq/app_rb_folder/storage/logs/php.log"); 

//        $gasPrice = $eth->gasPrice();
        $apikey = "2U4KNH6YFSTKAM4CV2MBF64VK6DZI878KC";
        $gasPrice = json_decode(file_get_contents("https://api.etherscan.io/api?module=gastracker&action=gasoracle&apikey=$apikey"))->result->SafeGasPrice;
        $doubleGasPrice = bcmul(gmp_strval($gasPrice), $muliplier);
        $doubleGasPrice = $doubleGasPrice * 1e9; // 20 GWei.

        error_log( "sendETH - Amount: ".json_decode($amount)."\n", 3, "/home/robotbq/app_rb_folder/storage/logs/php.log"); 
        
        $amountInWei = Utils2::toWei(strval($amount), 'ether');
        
        
        $gasCost = bcmul($doubleGasPrice, 210000); // Calculate total gas cost in Wei
        // Convert gas cost to ETH for comparison
        $gasCostInEth = bcdiv($gasCost, Utils2::toWei('1', 'ether'), 18); // Convert back to ETH with 18 decimal precision
        error_log( "sendETH - gasCostInEth ".json_encode($gasCostInEth)." \n", 3, "/home/robotbq/app_rb_folder/storage/logs/php.log");
        
        
        
        
        
        $eth->getTransactionCount($from, function ($err, $nonce) use ($from, $to, $doubleGasPrice, $amountInWei, $privateKey, $eth) {

            if ($err !== null) {
                return response()->json(['error' => 'Error getting the transaction nonce.'], 500);
            }
            $transaction = new Transaction3([
                'nonce' => '0x' . Utils2::toHex($nonce, true),
                'from' => $from,
                'to' => $to,
                'gasPrice' => '0x' . Utils2::toHex($doubleGasPrice, true),
                'gasLimit' => '0x' . Utils2::toHex(210000, true),
                'value' => Utils2::toHex($amountInWei, true),
                'chainId' => 0x1 // Replace with the actual chainId
            ]);

            error_log( "Test2 \n", 3, "/home/robotbq/app_rb_folder/storage/logs/php.log"); 

            $signedTransaction = $transaction->sign($privateKey);

            error_log( "Transaction signed \n", 3, "/home/robotbq/app_rb_folder/storage/logs/php.log"); 

            $eth->sendRawTransaction('0x' . $signedTransaction, function ($err, $tx) {
                if ($err !== null) {
                    error_log( "Transaction Error ".json_encode($err->getMessage())." \n", 3, "/home/robotbq/app_rb_folder/storage/logs/php.log"); 
                    return ['error' => $err->getMessage()];
                }
                error_log( "TransactionHash ".json_encode($tx)." \n", 3, "/home/robotbq/app_rb_folder/storage/logs/php.log"); 
                return ['transactionHash' => $tx];
            });
        });
    }
     
    
    
    /** Messages*/
    
    public function supportMessage(Request $request) {

        $from = 'user';
        $channel = 'support';
        $created_at = date('Y-m-d H:i:s');
    
        $user = Auth::user();
        
        DB::table('client_messages')->insert([
            'user' => $user->id,
            'user_phone' => "-",
            'created_at' => $created_at,
            'message' => Crypt::encryptString($request->input('message')),
            'from' => Crypt::encryptString($from),
            'channel' => Crypt::encryptString($channel),
            'from_phone' => "-",
            'sid' => "-",
        ]); 

        $ret['msg'] = 'success';
        $ret['message'] = __('Message sent successfully');
        return back()->with([$ret['msg'] => $ret['message']]);
    }
    
    public function fetchMessages($user_id) {
        $user = Auth::user();
        // Check if the $user_id is a phone number with country code
        $messages = ClientMessage::where('user', $user->id)->get();
        // Decrypt data here before sending to front-end if needed
        foreach ($messages as $message) {
            $message->from = Crypt::decryptString($message->from);
            $message->message = Crypt::decryptString($message->message);
            $date = Carbon::parse($message->created_at);
            $dateInCET = $date->timezone('Europe/Brussels');
            $message->created_at = $dateInCET;
            // the following two lines are not required as you're not changing these properties
            // $message->sid = $message->sid;
            // $message->status = $message->status;
            $message->channel = Crypt::decryptString($message->channel);
        }
        return response()->json($messages);

    }
    
    public function get_user_equity() {}
    

}
