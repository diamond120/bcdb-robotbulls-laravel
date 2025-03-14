<?php
namespace App\Http\Controllers\Admin;
/**
 * Admin Controller
 *
 * @package TokenLite
 * @author Softnio
 * @version 1.0.3
 */
use Auth;
use Cookie;
use Validator;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Page;
use App\Models\UserMeta;
use App\Models\Activity;
use App\Helpers\IcoHandler;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Notifications\PasswordChange;
use App\Models\Transaction;
use App\Models\KYC;
use App\Notifications\KycStatus;

class AdminController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        
        function getCountryFromCountryCode($countryCode) {
            $mapping = [
                '1' => 'USA',
                '33' => 'France',
                '41' => 'Switzerland',
                '44' => 'UK',
                // ... (and so on for other countries)
            ];
            return $mapping[$countryCode] ?? null;
        }

        
        if(auth()->user()->role == 'admin') {
            $get_tnx = ($request->get('chart') ? $request->get('chart') : 30) - 1;
            $get_user = ($request->get('user') ? $request->get('user') : 30) - 1;
            $stage = \App\Models\IcoStage::dashboard();
            $users = User::dashboard($get_user);
            $trnxs = \App\Models\Transaction::dashboard($get_tnx);
            
            
            
            
            
            $total_tokens = Transaction::join('users', 'transactions.user', '=', 'users.id')
                ->where('users.role', 'user')//['1', '2', '3', '4'])
                ->where('transactions.tnx_type', 'purc hase')
                ->where('transactions.status', 'approved')
                ->where('users.status', '<>', 'suspended')
                ->where('users.email', 'not like', '%@%')
                ->sum('transactions.tokens');

            $total_equity = Transaction::join('users', 'transactions.user', '=', 'users.id')
                ->where('users.role', 'user')
                ->where('transactions.tnx_type', 'purchase')
                ->where('transactions.status', 'approved')
                ->where('users.status', '<>', 'suspended')
                ->where('users.email', 'not like', '%@%')
                ->sum('transactions.equity');

            

            //get kyc countries
            $kycs = KYC::pluck('country')->toJson();
            
            
            if(isset($request->user)){
                $data = $users;
            }elseif(isset($request->chart)){
                $data = $trnxs;
            }else{
                $data = null;
            }
            if($request->ajax()){
                return response()->json((empty($data) ? [] : $data));
            }

            
            
            $users2 = User::whereNotIn('status', ['suspended'])
                ->where('role', 'user')
                ->where('equity', '>', 0)
                ->where('email', 'not like', '%@%')
                ->get();



            $users_with_tokens = $users2->count();
            $users_0_1000 = $users2->whereBetween('equity', [1, 1000])->count();
            $users_1001_5000 = $users2->whereBetween('equity', [1001, 5000])->count();
            $users_5001_10000 = $users2->whereBetween('equity', [5001, 10000])->count();
            $users_10001_50000 = $users2->whereBetween('equity', [10001, 50000])->count();
            $users_50001_100000 = $users2->whereBetween('equity', [50001, 100000])->count();
            $users_100001_500000 = $users2->whereBetween('equity', [100001, 500000])->count();
            $users_500000_1000000 = $users2->whereBetween('equity', [500000, 1000000])->count();
            $users_1000001_more = $users2->where('equity', '>', 1000000)->count();
            
            
            $equity_sum_total = $users2->sum('equity');
            $equity_sum_0_1000 = $users2->whereBetween('equity', [1, 1000])->sum('equity');
            $equity_sum_1001_5000 = $users2->whereBetween('equity', [1001, 5000])->sum('equity');
            $equity_sum_5001_10000 = $users2->whereBetween('equity', [5001, 10000])->sum('equity');
            $equity_sum_10001_50000 = $users2->whereBetween('equity', [10001, 50000])->sum('equity');
            $equity_sum_50001_100000 = $users2->whereBetween('equity', [50001, 100000])->sum('equity');
            $equity_sum_100001_500000 = $users2->whereBetween('equity', [100001, 500000])->sum('equity');
            $equity_sum_500000_1000000 = $users2->whereBetween('equity', [500000, 1000000])->sum('equity');
            $equity_sum_1000001_more = $users2->where('equity', '>', 1000000)->sum('equity');
            
            $balance_sum_total = $users2->sum('tokenBalance');
            $balance_sum_0_1000 = $users2->whereBetween('tokenBalance', [1, 1000])->sum('tokenBalance');
            $balance_sum_1001_5000 = $users2->whereBetween('tokenBalance', [1001, 5000])->sum('tokenBalance');
            $balance_sum_5001_10000 = $users2->whereBetween('tokenBalance', [5001, 10000])->sum('tokenBalance');
            $balance_sum_10001_50000 = $users2->whereBetween('tokenBalance', [10001, 50000])->sum('tokenBalance');
            $balance_sum_50001_100000 = $users2->whereBetween('tokenBalance', [50001, 100000])->sum('tokenBalance');
            $balance_sum_100001_500000 = $users2->whereBetween('tokenBalance', [100001, 500000])->sum('tokenBalance');
            $balance_sum_500000_1000000 = $users2->whereBetween('tokenBalance', [500000, 1000000])->sum('tokenBalance');
            $balance_sum_1000001_more = $users2->where('tokenBalance', '>', 1000000)->sum('tokenBalance');


            return view('admin.dashboard', compact('stage', 'kycs', 'users', 'trnxs', 'total_tokens', 'total_equity', 'users_with_tokens', 'users_0_1000', 'users_1001_5000', 'users_5001_10000', 'users_10001_50000', 'users_50001_100000', 'users_100001_500000', 'users_500000_1000000', 'users_1000001_more', 'equity_sum_total', 'equity_sum_0_1000', 'equity_sum_1001_5000', 'equity_sum_5001_10000', 'equity_sum_10001_50000', 'equity_sum_50001_100000', 'equity_sum_100001_500000', 'equity_sum_500000_1000000', 'equity_sum_1000001_more', 'balance_sum_total', 'balance_sum_0_1000', 'balance_sum_1001_5000', 'balance_sum_5001_10000', 'balance_sum_10001_50000', 'balance_sum_50001_100000', 'balance_sum_100001_500000', 'balance_sum_500000_1000000', 'balance_sum_1000001_more'));

        } else {
            return redirect()->route('admin.transactions', 'expiring');
        }
    }

    /**
     * Show the application User Profile.
     *
     * @return \Illuminate\Http\Response
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public function profile()
    {
        $user = Auth::user();
        $userMeta = UserMeta::getMeta($user->id);

        $g2fa = new Google2FA();
        $google2fa_secret = $g2fa->generateSecretKey();
        $google2fa = $g2fa->getQRCodeUrl(
            site_info().'-'.$user->name,
            $user->email,
            $google2fa_secret
        );
        return view('admin.profile', compact('user', 'userMeta', 'google2fa', 'google2fa_secret'));
    }

    /**
     * Show the application User Profile.
     *
     * @return \Illuminate\Http\Response
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public function profile_update(Request $request)
    {
        $type = $request->input('action_type');
        $ret['msg'] = 'info';
        $ret['message'] = __('messages.nothing');

        if ($type == 'personal_data') {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:4',
            ]);

            if ($validator->fails()) {
                $msg = '';
                if ($validator->errors()->has('name')) {
                    $msg = $validator->errors()->first();
                } else {
                    $msg = __('messages.form.wrong');
                }

                $ret['msg'] = 'warning';
                $ret['message'] = $msg;
                return response()->json($ret);
            } else {
                $user = User::FindOrFail(Auth::id());
                $user->name = $request->input('name');
                //$user->email = $request->input('email');
                $user->mobile = $request->input('mobile');
                $user_saved = $user->save();

                if ($user) {
                    $ret['msg'] = 'success';
                    $ret['message'] = __('messages.update.success', ['what' => 'Profile']);
                } else {
                    $ret['msg'] = 'error';
                    $ret['message'] = __('messages.update.warning');
                }
            }
        }

        if ($type == 'notification') {
            $notify_admin = $newsletter = $unusual = 0;

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
                $ret['msg'] = 'error';
                $ret['message'] = __('messages.update.warning');
            }
        }
        if ($type == 'security') {

            $save_activity = $mail_pwd = 'FALSE';
            $unusual = $notify_admin = 0;

            if (isset($request['notify_admin'])) {
                $notify_admin = 1;
            }
            if (isset($request['save_activity'])) {
                $save_activity = 'TRUE';
            }
            if (isset($request['mail_pwd'])) {
                $mail_pwd = 'TRUE';
            }

            $mail_pwd = 'TRUE';

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
                $userMeta->unusual = $unusual;
                $userMeta->pwd_chng = $mail_pwd;
                $userMeta->save_activity = $save_activity;
                $userMeta->notify_admin = $notify_admin;
                $userMeta->save();
                $ret['msg'] = 'success';
                $ret['message'] = __('messages.update.success', ['what' => 'Security']);
            } else {
                $ret['msg'] = 'error';
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
                $msg = '';
                if ($validator->errors()->hasAny(['old-password', 'new-password', 're-password'])) {
                    $msg = $validator->errors()->first();
                } else {
                    $msg = __('messages.form.wrong');
                }

                $ret['msg'] = 'warning';
                $ret['message'] = $msg;
                return response()->json($ret);
            } else {
                $user = User::FindOrFail(Auth::id());
                if ($user) {
                    if (!Hash::check($request->input('old-password'), $user->password)) {
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
                            $ret['msg'] = 'error';
                            $ret['message'] = __('messages.form.wrong');
                        }
                    }
                } else {
                    $ret['msg'] = 'error';
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
                        $ret['message'] = __('Successfully '.($google2fa == 1 ? 'enable' : 'disable').' 2FA security in your account.');
                    }else{
                        $ret['msg'] = 'error';
                        $ret['message'] = __('You provided a invalid 2FA authentication code!');
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

    /**
     * @version 1.1
     * @since 1.1.0
     */
    public function system_info(Request $request)
    {
        return view('admin.system');
    }

    /**
     * @version 1.0
     * @since 1.1.0
     */
    public function treatment(Request $request)
    {
        $handle = (new IcoHandler());
        if($request->isMethod('POST')){
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email',
                'purchase_code' => 'required|min:24',
            ], [
                'name.required' => __('Envato Username is required for activation.'),
                'email.required' => __('Your Email address is required for activation.'),
                'email.email' => __('Please enter a valid email address.'),
                'purchase_code.required' => __('Envato Purchase Code is required for activation.'),
                'purchase_code.min' => __('Please enter a valid purchase code.'),
            ]);

            if ($validator->fails()) {
                $error = ['msg' => 'warning', 'message' => $validator->errors()->first()];
                if ($request->ajax()) {
                    return response()->json($error);
                }
                return back()->with($error);
            }
            return $handle->checkHelth($request);
        }
        if(is_demo_user() || is_demo_preview()) {
            $error['warning'] = (is_demo_preview()) ? __('messages.demo_preview') : __('messages.demo_user');
            return redirect()->route('admin.system')->with($error);
        }
        if($request->skip && $request->skip=='reg'){
            Cookie::queue(Cookie::make('ico_nio_reg_skip', 1, 1440)); // one day
            $last = (int)get_setting('piks_ger_oin_oci', 0);
            add_setting('piks_ger_oin_oci', $last + 1);
            return redirect()->route('admin.home');
        }
        if($request->revoke && $request->revoke=='license'){
            delete_setting(['env_pcode','nio_lkey','nio_email','env_uname', 'env_ptype']);
            add_setting('tokenlite_update', time()); add_setting('tokenlite_credible', str_random(48)); 
            add_setting('site_api_secret', str_random(16));
            Cookie::queue(Cookie::forget('ico_nio_reg_skip'));
            return redirect()->route('admin.home');
        }
        if($handle->check_body() && str_contains(app_key(), $handle->find_the_path($handle->getDomain()))){
            return redirect()->route('admin.home');
        }
        return view('auth.chamber');
    }

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
                $ret['msg'] = 'error';
                $ret['message'] = __('messages.password.failed');
            }
        } else {
            $ret['msg'] = 'error';
            $ret['message'] = __('messages.password.token');
        }

        return redirect()->route('admin.profile')->with([$ret['msg'] => $ret['message']]);
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
    public function activity()
    {
        $user = Auth::user();
        $activities = Activity::where('user_id', $user->id)->orderBy('created_at', 'DESC')->limit(50)->get();
        return view('admin.activity', compact('user', 'activities'));
    }
    /**
     * Delete activity
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public function activity_delete(Request $request)
    {
        $id = $request->input('delete_activity');
        $ret['msg'] = 'info';
        $ret['message'] = __('messages.nothing');

        if ($id !== 'all') {
            $remove = Activity::where('id', $id)->where('user_id', Auth::id())->delete();
        } else {
            $remove = Activity::where('user_id', Auth::id())->delete();
        }
        if ($remove) {
            $ret['msg'] = 'success';
            $ret['message'] = __('messages.delete.delete', ['what' => 'Activity']);
        } else {
            $ret['msg'] = 'error';
            $ret['message'] = __('messages.form.wrong');
        }
        if ($request->ajax()) {
            return response()->json($ret);
        }
        return back()->with([$ret['msg'] => $ret['message']]);
    }
}
