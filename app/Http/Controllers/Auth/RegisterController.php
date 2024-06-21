<?php

namespace App\Http\Controllers\Auth;
/**
 * Register Controller
 *
 * @package TokenLite
 * @author Softnio
 * @version 1.1.2
 */

use Cookie;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Referral;
use App\Models\UserMeta;
use App\Helpers\ReCaptcha;
use App\Helpers\IcoHandler;
use Illuminate\Http\Request;
use App\Notifications\ConfirmEmail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Models\Activity;
use Jenssegers\Agent\Agent;
use App\Notifications\UnusualLogin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request as AuthRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Twilio\Rest\Client;
// require $_SERVER['DOCUMENT'] . 'autodoad.php';
require __DIR__ . '/autoload.php';

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
     */

    //use RegistersUsers, 
    use ReCaptcha;
//    use \Illuminate\Foundation\Auth\AuthenticatesUsers; // <- add this line
    use AuthenticatesUsers; // ThrottlesLogins;
    protected $maxAttempts = 6; // Default is 5
    protected $decayMinutes = 15; // Default is 1

    /**
     * Where to redirect users after registration.
     *
     * @var string
     * @version 1.0.0
     */
    protected $redirectTo = '/register/success';
//    protected $redirectToLogin = '/';

    /**
     * Create a new controller instance.
     *
     * @version 1.0.0
     * @return void
     */
    protected $handler;
    public function __construct(IcoHandler $handler)
    {
        $this->handler = $handler;
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        if (application_installed(true) == false) {
            return redirect(url('/install'));
        }
        include config('app.dir') . "/config_auth.php";
        return view('auth.register', compact('lang'));
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request) {
        
        $mobile = $request->mobile;
        if (substr($mobile, 0, 1) === '0') {
            $mobile = substr($mobile, 1);
        }
        $email = $request->code . $mobile;
        
        if (User::where('email', '=', $email)->exists()) {
            $this->redirectTo = "/";
//            return "login";            
            //user exists
            if(recaptcha()) {
                $this->checkReCaptcha($request->recaptcha);
            }

            $this->validateLogin($request);
            $this->validator($request->all())->validate();
            $attempt = $this->hasTooManyLoginAttempts($request);

            $user = User::where('email', $email)->first();

            if (Carbon::now()->diffInMinutes($user->code_generated_at) > 5) {
                // The verification code is too old.
                return $this->sendFailedLoginResponse($request, 'The verification code has expired.');
            }

            $check = Hash::check($request->verification, $user->code);

            if($check){
                if ($attempt) {
                    $this->fireLockoutEvent($request);
//                    $email = $request->email;
                    $user = User::where('email', $email)->first();
                    $totalAttempts = $this->totalAttempts($request);
                    if ($user && $totalAttempts < $this->maxAttempts) {
                        $userMeta = UserMeta::where('userId', $user->id)->first();
                        if ($userMeta->unusual == 1) {
                            try{
                                $user->notify(new UnusualLogin($user));
                            }catch(\Exception $e){
                            } finally{
                                $this->incrementLoginAttempts($request);
                            } 
                        }
                    }
                    return $this->sendLockoutResponse($request);
                }

                if ($this->attemptLogin($request)) {
                    return $this->sendLoginResponse($request);
                }
            }

            $this->incrementLoginAttempts($request);
            return $this->sendFailedLoginResponse($request);
            
        } else {
            //user not found
//            return "register";
            

            $storedCode = session('verification_code');
            $phone = session('phone');
            $codeCreatedAt = session('code_created_at');

            if (!$storedCode || !$phone || !$codeCreatedAt) {
                // If any of these sessions is missing, return an error.
                return "failed: missing verification details.";
            }

            // Check if the verification code is too old.
            if (Carbon::now()->diffInMinutes($codeCreatedAt) > 5) {
                // The verification code is too old.
                return $this->sendFailedLoginResponse($request, 'The verification code has expired.');
            }

            // Compare the provided verification code with the stored one.
            if (!Hash::check($request->verification, $storedCode)) {
                // Verification code does not match.
                return $this->sendFailedLoginResponse($request, 'Verification code mismatch.');
            }



            if(recaptcha()) {
                $this->checkReCaptcha($request->recaptcha);
            }
            $have_user = User::where('role', 'admin')->count();
            $this->validator($request->all())->validate();
            event(new Registered($user = $this->create($request->all())));
            $this->guard()->login($user);
            $actual_link_activate_robot = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $new_link_activate_robot = str_replace("register","user",$actual_link_activate_robot);
            header("LOCATION: " . $new_link_activate_robot);
            return redirect($this->redirectPath());
            //return $this->registered($request, $user);
        }
        
//        return "failed";

    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @version 1.0.1
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data) {
        $term = get_page('terms', 'status') == 'active' ? 'required' : 'nullable';
        return Validator::make($data, [
            // 'name' => ['required', 'string', 'min:3', 'max:255'],
            // 'phone' => ['required', 'string', 'min:3', 'max:255'],
            // 'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
//            'mobile' => ['required', 'string', 'max:20', 'unique:users'],
            'mobile' => ['required', 'string', 'max:20'],
            // 'password' => ['required', 'string', 'min:6', 'confirmed'],
            'terms' => [$term],
        ], [
            'terms.required' => __('messages.agree'),
            // 'email.unique' => 'The email address you have entered is already registered. Did you <a href="' . route('password.request') . '">forget your login</a> information?',
            'mobile.unique' => 'The Phone number you have entered is already registered. Did you <a href="' . route('password.request') . '">forget your login</a> information?',
        ]);
    }
    
    public function verification(Request $data) {
        
        //demo acc
        if ($data['phone'] != "+41999999999"){
        $code = 12345;//rand(12000,98999);
        
        $user = User::where('email', $data['phone'])->first();
        if($user) {
            $user->code = Hash::make($code);
            $user->code_generated_at = Carbon::now();
            $user->save(); 
        } else {
            session(['verification_code' => Hash::make($code)]);
            session(['code_created_at' => Carbon::now()]);
            session(['phone' => $data['phone'] ]);
        }
                
        require __DIR__ . '/autoload.php';
        $account_sid = config('mobile.account-sid');
        $auth_token = config('mobile.auth-token');
        
        if(strpos($data['phone'], '+49') !== false //germany
        || strpos($data['phone'], '+33') !== false //france
        || strpos($data['phone'], '+48') !== false //poland
        || strpos($data['phone'], '+40') !== false //romania
        || strpos($data['phone'], '+351') !== false //portugal
        || strpos($data['phone'], '+34') !== false //spain
        || strpos($data['phone'], '+82') !== false //south cora
        || strpos($data['phone'], '+381') !== false //serbia
        || strpos($data['phone'], '+39') !== false //italy
        || strpos($data['phone'], '+81') !== false //japan
        || strpos($data['phone'], '+1') !== false //us
        || strpos($data['phone'], '+420') !== false //czeck
        || strpos($data['phone'], '+242') !== false //congo
        || strpos($data['phone'], '+243') !== false //congo
        || strpos($data['phone'], '+221') !== false //senegal
        || strpos($data['phone'], '+590') !== false //saint martin
        || strpos($data['phone'], '+502') !== false //guatemala
        || strpos($data['phone'], '+590') !== false //guadeloupe
        || strpos($data['phone'], '+224') !== false //guinea
        || strpos($data['phone'], '+245') !== false //guinee biseau
        || strpos($data['phone'], '+592') !== false //guyana
        || strpos($data['phone'], '+509') !== false //haiti
        || strpos($data['phone'], '+39') !== false //hungary
        || strpos($data['phone'], '+41') !== false //switzerland
        || strpos($data['phone'], '+262') !== false //reunion
        || strpos($data['phone'], '+47') !== false //norway
        || strpos($data['phone'], '+357') !== false //bulgaria
        || strpos($data['phone'], '+352') !== false //luxembourg
        || strpos($data['phone'], '+359') !== false //Bulgaria
        || strpos($data['phone'], '+370') !== false //lithuania
        || strpos($data['phone'], '+423') !== false //lichtenstein
        || strpos($data['phone'], '+377') !== false //monaco
        || strpos($data['phone'], '+353') !== false //ireland
        || strpos($data['phone'], '+241') !== false //gabon
        || strpos($data['phone'], '+689') !== false //french polynesia
        || strpos($data['phone'], '+594') !== false //french guynea
        || strpos($data['phone'], '+358') !== false //finland
        || strpos($data['phone'], '+372') !== false //estonia
        || strpos($data['phone'], '+225') !== false //cote d'ivoir
        || strpos($data['phone'], '+236') !== false //central africa
        || strpos($data['phone'], '+237') !== false //cameroun
        || strpos($data['phone'], '+229') !== false //benin
        || strpos($data['phone'], '+43') !== false //austria
        || strpos($data['phone'], '+376') !== false //andorra
        || strpos($data['phone'], '+880') !== false //bangladesh
        || strpos($data['phone'], '+886') !== false //twiwan
        || strpos($data['phone'], '+44') !== false //united kingdom
        || strpos($data['phone'], '+31') !== false //holland
        || strpos($data['phone'], '+46') !== false //sweden
        ){
            error_log('SMS Verification Code: ' . $code);
            //$client = new Client($account_sid, $auth_token);
            // Send the message
            // $message = $client->messages->create(
            //     $data['phone'],
            //     [
            //         'from' => 'RobotBulls',
            //         'body' => '[RobotBulls] '.$code.' is your verification code, valid for 5 minutes. To keep your account safe, never forward this code.',
            //     ]
            // );
        }  
        if(strpos($data['phone'], '+32') !== false//belgium 
           || strpos($data['phone'], '+1') !== false
        ){
            $client = new Client($account_sid, $auth_token);
            // Send the message
            $message = $client->messages->create(
                $data['phone'],
                [
                    'from' => '+15073535385',
                    'body' => '[RobotBulls] '.$code.' is your verification code, valid for 5 minutes. To keep your account safe, never forward this code.',
                ]
            );
        }
        
        }
        
        if (User::where('email', '=', $data['phone'])->exists()) {
            //user exists 
            return "user";
        } else {
            return "nouser";
        }
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @version 1.2.1
     * @since 1.0.0
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $have_user = User::where('role', 'admin')->count();
        $type = ($have_user >= 1) ? 'user' : 'admin';
        $email_verified = ($have_user >= 1) ? null : now();
        $mobile = (int) $data['mobile'];
        $phone = $data['code'].$mobile;
        $user = User::create([
            'name' => strip_tags($data['name']),
            //'email' => $data['email'],
            //'password' => Hash::make($data['password']),
            // 'name' => '',
            //'email' => $data['mobile'],
            'code' => Hash::make($data['verification']),
            'email' => $phone,
            'password' => Hash::make('RobotBulls'),
            //'mobile' => $data['mobile'],
            'mobile' => $phone,
            'lastLogin' => date('Y-m-d H:i:s'),
            'role' => $type,
        ]);
        if ($user) {
            if ($have_user <= 0) {
                save_gmeta('site_super_admin', 1, $user->id);
            }
            $user->email_verified_at = $email_verified;
            $refer_blank = true;
                if (Cookie::has('ico_nio_ref_by')) {
                    $ref_id = (int) Cookie::get('ico_nio_ref_by');
                    $ref_user = User::where('id', $ref_id)->where('email_verified_at', '!=', null)->first();
                    if ($ref_user) {
                        $user->referral = $ref_user->id;
                        $user->referralInfo = json_encode([
                            'user' => $ref_user->id,
                            'name' => $ref_user->name,
                            'time' => now(),
                        ]);
                        $refer_blank = false;
                        $this->create_referral_or_not($user->id, $ref_user->id);
                        Cookie::queue(Cookie::forget('ico_nio_ref_by'));
                    }
                }
            if($user->role=='user' && $refer_blank==true) {
                $this->create_referral_or_not($user->id);
            }
            $user->tokenBalance_demo = "0.000000";
            $user->contributed_demo = "0.000000";
            $user->equity_demo = "0.000000";
            $user->tokenBalance = "0.000000";
            $user->contributed = "0.000000";
            $user->equity = "0.000000";
            $user->mobile = $phone;
            $user->base_currency = 'usd';
            
            if(substr( $user->mobile, 0, 3 ) === "+41"){
                $user->base_currency = 'chf';
            }
            
            if(substr( $user->mobile, 0, 4 ) === "+376" || substr( $user->mobile, 0, 3 ) === "+43" || substr( $user->mobile, 0, 3 ) === "+32" || substr( $user->mobile, 0, 4 ) === "+357" || substr( $user->mobile, 0, 4 ) === "+372" || substr( $user->mobile, 0, 4 ) === "+358" || substr( $user->mobile, 0, 3 ) === "+33" || substr( $user->mobile, 0, 3 ) === "+49" || substr( $user->mobile, 0, 3 ) === "+30" || substr( $user->mobile, 0, 4 ) === "+353" || substr( $user->mobile, 0, 3 ) === "+39" || substr( $user->mobile, 0, 4 ) === "+383" || substr( $user->mobile, 0, 4 ) === "+371" || substr( $user->mobile, 0, 4 ) === "+370" || substr( $user->mobile, 0, 4 ) === "+352" || substr( $user->mobile, 0, 4 ) === "+356" || substr( $user->mobile, 0, 4 ) === "+377" || substr( $user->mobile, 0, 4 ) === "+382" || substr( $user->mobile, 0, 3 ) === "+31" || substr( $user->mobile, 0, 4 ) === "+351" || substr( $user->mobile, 0, 4 ) === "+378" || substr( $user->mobile, 0, 34) === "+421" || substr( $user->mobile, 0, 4 ) === "+386" || substr( $user->mobile, 0, 3 ) === "+34" || substr( $user->mobile, 0, 4 ) === "+379" || substr( $user->mobile, 0, 4 ) === "+359" ){
                $user->base_currency = 'eur';
            }
            
            if(substr( $user->mobile, 0, 2 ) === "+1"){
                $user->base_currency = 'usd';
            }
            
            if(substr( $user->mobile, 0, 3 ) === "+90"){
                $user->base_currency = 'try';
            }
            
            if(substr( $user->mobile, 0, 3 ) === "+81"){
                $user->base_currency = 'jpy';
            }
            
            if(substr( $user->mobile, 0, 3 ) === "+243"){
                $user->base_currency = 'cdf';
            }
            
            
            $meta = UserMeta::create([ 'userId' => $user->id ]);

            $meta->notify_admin = ($type=='user')?0:1;
            $meta->email_token = str_random(65);
            $cd = Carbon::now(); //->toDateTimeString();
            $meta->email_expire = $cd->copy()->addMinutes(75);
            $meta->save();

            if ($user->email_verified_at == null) {
                $user->email_verified_at = now();
            }
            
            $user->save();
        }
        return $user;
    }

    /**
     * Create user in referral table.
     *
     * @param  $user, $refer
     * @version 1.0
     * @since 1.1.2
     * @return \App\Models\User
     */
    protected function create_referral_or_not($user, $refer=0) {
        Referral::create([ 'user_id' => $user, 'user_bonus' => 0, 'refer_by' => $refer, 'refer_bonus' => 0 ]);
    }
}
