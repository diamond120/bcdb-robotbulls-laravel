<?php

namespace App\Http\Controllers\Admin;
/**
 * Users Controller
 *
 * @package TokenLite
 * @author Softnio
 * @version 1.1.3
 */
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
use App\Models\ClientMessage;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use DB;
use SWeb3\SWeb3;
use SWeb3\Utils;



class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @version 1.1
     * @since 1.0
     * @return void
     */
    public function index(Request $request, $role = '')
{  
    if(auth()->user()->id == 1) {
    
        $role_data  = '';
        $per_page   = gmvl('user_per_page', 10);
        $order_by   = (gmvl('user_order_by', 'id')=='token') ? 'tokenBalance' : gmvl('user_order_by', 'id');
        $ordered    = gmvl('user_ordered', 'DESC');
        $is_page    = (empty($role) ? 'all' : ($role=='user' ? 'investor' : $role));

        if(!empty($role) && $role != 'biggest') {
            $users = User::whereNotIn('status', ['deleted'])->where('role', $role);
        } else {
            $users = User::whereNotIn('status', ['deleted']);
        }

        if($request->s){
            $users = User::AdvancedFilter($request);
        }

        if ($request->filter) {
            $users = User::AdvancedFilter($request);
        }

        if($role == 'biggest') {
            $users = $users->where('role', 'user')->whereNotIn('id', ['1', '2', '3', '4'])->orderByRaw("CAST(equity AS DECIMAL(10,2)) DESC");
        } 
        
//        if ($order_by === 'tokenBalance' || $order_by === 'equity') {
//            $users = $users->orderByRaw("CAST($order_by AS DECIMAL(10,2)) $ordered");
//        } else {
//            $users = $users->orderBy($order_by, $ordered);
//        }
        
        $users = $users->orderBy($order_by, $ordered);

        if ($request->filled('exclude_user_ids')) {
            $idsToExclude = ['1', '2', '3', '4'];
            $users->whereNotIn('id', $idsToExclude);
        }

        $users = $users->paginate($per_page);
        $pagi = $users->appends(request()->all());

        return view('admin.users', compact('users', 'role_data', 'is_page', 'pagi'));
    
    } else {
        abort(404);
    }
    
}


    /**
     * Send email to specific user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @version 1.0.1
     * @since 1.0
     * @return void
     */
    public function send_email(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ], [
            'user_id.required' => __('Select a user first!'),
        ]);

        if ($validator->fails()) {
            if ($validator->errors()->has('name')) {
                $msg = $validator->errors()->first();
            } else {
                $msg = __('messages.somthing_wrong');
            }

            $ret['msg'] = 'warning';
            $ret['message'] = $msg;
        } else {
            $user = User::FindOrFail($request->input('user_id'));

            if ($user) {
                $msg = $request->input('message');
                $msg = replace_with($msg, '[[user_name]]', $user->name);
                $data = (object) [
                    'user' => (object) ['name' => $user->name, 'email' => $user->email],
                    'subject' => $request->input('subject'),
                    'greeting' => $request->input('greeting'),
                    'text' => str_replace("\n", "<br>", $msg),
                ];
                $when = now()->addMinutes(2);

                try {
                    Mail::to($user->email)
                    ->later($when, new EmailToUser($data));
                    $ret['msg'] = 'success';
                    $ret['message'] = __('messages.mail.send');
                } catch (\Exception $e) {
                    $ret['errors'] = $e->getMessage();
                    $ret['msg'] = 'warning';
                    $ret['message'] = __('messages.mail.issues');
                }
            } else {
                $ret['msg'] = 'warning';
                $ret['message'] = __('messages.mail.failed');
            }

            if ($request->ajax()) {
                return response()->json($ret);
            }
            return back()->with([$ret['msg'] => $ret['message']]);
        }
    }

    
    //send SMS
    public function send_sms(Request $request) {
                
        $account_sid = config('mobile.account.sid');
        $auth_token = config('mobile.auth.token');
        
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ], [
            'user_id.required' => __('Select a user first!'),
        ]);
        
        if ($validator->fails()) {
            if ($validator->errors()->has('name')) {
                $msg = $validator->errors()->first();
            } else {
                $msg = __('messages.somthing_wrong');
            }

            $ret['msg'] = 'warning';
            $ret['message'] = $msg;
        } else {
            $user = User::FindOrFail($request->input('user_id'));

            if ($user) {
                $msg = $request->input('message');
                $msg = replace_with($msg, '[[user_name]]', $user->name);
                $data = (object) [
                    'user' => (object) ['name' => $user->name, 'email' => $user->email],
                    'sender' => $request->input('sender'),
                    'text' => str_replace("\n", "<br>", $msg),
                ];
                $when = now()->addMinutes(2);

                try {
                    $client = new Client($account_sid, $auth_token);
                    // Send the message
                    $message = $client->messages->create(
                        $user->email,
                        [
                            'from' => $request->input('sender'),
                            'body' => $msg,
                        ]
                    );
                    $ret['msg'] = 'success';
                    $ret['message'] = __('messages.mail.send');
                } catch (\Exception $e) {
                    $ret['errors'] = $e->getMessage();
                    $ret['msg'] = 'warning';
                    $ret['message'] = __('messages.mail.issues');
                }
            } else {
                $ret['msg'] = 'warning';
                $ret['message'] = __('messages.mail.failed');
            }

            if ($request->ajax()) {
                return response()->json($ret);
            }
            return back()->with([$ret['msg'] => $ret['message']]);
        }
    }
    
    
    //send whatsapp
    public function send_whatsapp(Request $request) {
                
        $account_sid = config('mobile.account.sid');
        $auth_token = config('mobile.auth.token');
    
        $twilio_number = config('mobile.auth.token'); // Replace with your Twilio number

        $client = new Client($account_sid, $auth_token);

        $message = $client->messages->create(
            config('mobile.recipient.number'), // Replace with the recipient's number
            [
                'from' => $twilio_number,
                'body' => 'Hello, this is a test message!'
            ]
        );

        return $message->sid;
        
    }
    
    //add referrant
    public function add_referrant(Request $request) {
        
        Log::debug('test1');
        
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'referrant' => 'required',
        ], [
            'user_id.required' => __('Select a user first!'),
            'referrant.required' => __('Referrant field is required!'),
        ]);

        
        if ($validator->fails()) {
            Log::debug('test2');
            if ($validator->errors()->has('name')) {
                $msg = $validator->errors()->first();
            } else {
                $msg = __('messages.somthing_wrong');
            }

            $ret['msg'] = 'warning';
            $ret['message'] = $msg;
        } else {
            Log::debug('test3');
            $user = User::FindOrFail($request->input('user_id'));
            $referrant = User::FindOrFail($request->input('referrant'));
                
            if ($user) {
                Log::debug('test4');
                $msg = $request->input('message');
                $msg = replace_with($msg, '[[user_name]]', $user->name);
                try {
                    Log::debug('test5');
                    $user->referral = $referrant->id;
                    $user->referralInfo = '{"user":'.$referrant->id.',"name":'.$referrant->name.',"time":[]}';
                    $user->save();
                    Log::debug('test6');
                    $ret['msg'] = 'success';
                    $ret['message'] = __('messages.mail.send');
                } catch (\Exception $e) {
                    Log::debug('test6');
                    $ret['errors'] = $e->getMessage();
                    $ret['msg'] = 'warning';
                    $ret['message'] = __('messages.mail.issues');
                }
            } else {
                Log::debug('test7');
                $ret['msg'] = 'warning';
                $ret['message'] = __('messages.mail.failed');
            }

            if ($request->ajax()) {
                return response()->json($ret);
            }
            return back()->with([$ret['msg'] => $ret['message']]);
        }
    }
    
    
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @version 1.0.2
     * @since 1.0
     * @return void
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role' => 'required',
            'name' => 'required|min:3',
            'email' => 'required|unique:users,email',
            'password' => 'nullable|min:6',
        ], [
            'email.unique' => __('messages.email.unique'),
        ]);

        if ($validator->fails()) {
            $msg = '';
            if ($validator->errors()->hasAny(['name', 'email', 'password'])) {
                $msg = $validator->errors()->first();
            } else {
                $msg = __('messages.somthing_wrong');
            }

            $ret['msg'] = 'warning';
            $ret['message'] = $msg;
            return response()->json($ret);
        } else {
            if($request->input('role')=='admin' && !super_access()) {
                $ret['msg'] = 'warning';
                $ret['message'] = __("You do not have enough permissions to perform requested operation.");
            } else {
                $password = Hash::make($request->input('password') ?? '123456');
                $lastLogin = date("Y-m-d H:i:s");
                
                $base_currency = 'eur';
                if(substr( $request->input('email'), 0, 3 ) === "+41"){
                    $base_currency = 'chf';
                }

                if(substr( $request->input('email'), 0, 4 ) === "+376" || substr( $request->input('email'), 0, 3 ) === "+43" || substr( $request->input('email'), 0, 3 ) === "+32" || substr( $request->input('email'), 0, 4 ) === "+357" || substr( $request->input('email'), 0, 4 ) === "+372" || substr( $request->input('email'), 0, 4 ) === "+358" || substr( $request->input('email'), 0, 3 ) === "+33" || substr( $request->input('email'), 0, 3 ) === "+49" || substr( $request->input('email'), 0, 3 ) === "+30" || substr( $request->input('email'), 0, 4 ) === "+353" || substr( $request->input('email'), 0, 3 ) === "+39" || substr( $request->input('email'), 0, 4 ) === "+383" || substr( $request->input('email'), 0, 4 ) === "+371" || substr( $request->input('email'), 0, 4 ) === "+370" || substr( $request->input('email'), 0, 4 ) === "+352" || substr( $request->input('email'), 0, 4 ) === "+356" || substr( $request->input('email'), 0, 4 ) === "+377" || substr( $request->input('email'), 0, 4 ) === "+382" || substr( $request->input('email'), 0, 3 ) === "+31" || substr( $request->input('email'), 0, 4 ) === "+351" || substr( $request->input('email'), 0, 4 ) === "+378" || substr( $request->input('email'), 0, 34) === "+421" || substr( $request->input('email'), 0, 4 ) === "+386" || substr( $request->input('email'), 0, 3 ) === "+34" || substr( $request->input('email'), 0, 4 ) === "+379" || substr( $request->input('email'), 0, 4 ) === "+359" ) {
                    $base_currency = 'eur';
                }

                if(substr( $request->input('email'), 0, 2 ) === "+1"){
                    $base_currency = 'usd';
                }

                if(substr( $request->input('email'), 0, 3 ) === "+90"){
                    $base_currency = 'try';
                }

                if(substr( $request->input('email'), 0, 3 ) === "+81"){
                    $base_currency = 'jpy';
                }

                if(substr( $request->input('email'), 0, 3 ) === "+243"){
                    $base_currency = 'cdf';
                }
                
                
                $user = User::create([
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'mobile' => $request->input('email'),
                    'password' => $password,
                    'role' => $request->input('role'),
                    'lastLogin' => $lastLogin,
                ]);

                if ($user) {
                    $user->email_verified_at = isset($request->email_req) ? null : date('Y-m-d H:i:s');
                    $user->registerMethod = 'Internal';
                    $user->base_currency = $base_currency;
                    $user->contributed = "0.000000";
                    $user->tokenBalance = "0.000000";
                    $user->equity = "0.000000";
                    // $user->referral = ($user->id.'.'.str_random(50));
                    $user->save();
                    $meta = UserMeta::create([
                        'userId' => $user->id,
                    ]);
                    $meta->notify_admin = ($request->input('role')=='user')?0:1;
                    $meta->email_token = str_random(65);
                    $meta->email_expire = now()->addMinutes(75);
                    $meta->save();

                    $extra = (object) [
                        'name' => $user->name,
                        'email' => $user->email,
                        'password' => $req_password,
                    ];

                    try {
                        if (isset($request->email_req)) {
                            $user->notify(new ConfirmEmail($user, $extra));
                        }
                        // $user->notify(new AddUserEmail($user));
                        $ret['link'] = route('admin.users');
                        $ret['msg'] = 'success';
                        $ret['message'] = __('messages.insert.success', ['what' => 'User']);
                    } catch (\Exception $e) {
                        $ret['errors'] = $e->getMessage();
                        $ret['link'] = route('admin.users');
                        $ret['msg'] = 'warning';
                        $ret['message'] = __('messages.insert.success', ['what' => 'User']).' '.__('messages.email.failed');
                        ;
                    }
                } else {
                    $ret['msg'] = 'warning';
                    $ret['message'] = __('messages.insert.warning', ['what' => 'User']);
                }
            }

            if ($request->ajax()) {
                return response()->json($ret);
            }
            return back()->with([$ret['msg'] => $ret['message']]);
        }
    }
    
    public function name_edit(Request $request)
    {
        if(auth()->user()->id == 1) {
                
                $id = $request->input('id');
        
                $user = User::FindOrFail($id);

                if ($user) {
                    $user->name = $request->input('name');
                    
                    $user->save();

                    try {
                        $ret['link'] = route('admin.users');
                        $ret['msg'] = 'success';
                        $ret['message'] = __('messages.insert.success', ['what' => 'User']);
                    } catch (\Exception $e) {
                        $ret['errors'] = $e->getMessage();
                        $ret['link'] = route('admin.users');
                        $ret['msg'] = 'warning';
                        $ret['message'] = __('messages.insert.success', ['what' => 'User']).' '.__('messages.email.failed');
                        ;
                    }
                } else {
                    $ret['msg'] = 'warning';
                    $ret['message'] = __('messages.insert.warning', ['what' => 'User']);
                }

            if ($request->ajax()) {
                return response()->json($ret);
            }
            return back()->with([$ret['msg'] => $ret['message']]);
        }
    }
    
    public function email_edit(Request $request)
    {
        
        if(auth()->user()->id == 1) {
                
                $id = $request->input('id');
        
                $user = User::FindOrFail($id);

                if ($user) {
                    $user->email = $request->input('email');
                    $user->mobile = $request->input('email');
                    
                    $user->save();

                    try {
                        $ret['link'] = route('admin.users');
                        $ret['msg'] = 'success';
                        $ret['message'] = __('messages.insert.success', ['what' => 'User']);
                    } catch (\Exception $e) {
                        $ret['errors'] = $e->getMessage();
                        $ret['link'] = route('admin.users');
                        $ret['msg'] = 'warning';
                        $ret['message'] = __('messages.insert.success', ['what' => 'User']).' '.__('messages.email.failed');
                        ;
                    }
                } else {
                    $ret['msg'] = 'warning';
                    $ret['message'] = __('messages.insert.warning', ['what' => 'User']);
                }

            if ($request->ajax()) {
                return response()->json($ret);
            }
            return back()->with([$ret['msg'] => $ret['message']]);
        }
    }
    
    public function base_currency_edit(Request $request)
    {
        
        if(auth()->user()->id == 1) {
                
                $id = $request->input('id');
        
                $user = User::FindOrFail($id);

                if ($user) {
                    $user->base_currency = $request->input('base_currency');
                    
                    $user->save();

                    try {
                        $ret['link'] = route('admin.users');
                        $ret['msg'] = 'success';
                        $ret['message'] = __('messages.insert.success', ['what' => 'User']);
                    } catch (\Exception $e) {
                        $ret['errors'] = $e->getMessage();
                        $ret['link'] = route('admin.users');
                        $ret['msg'] = 'warning';
                        $ret['message'] = __('messages.insert.success', ['what' => 'User']).' '.__('messages.email.failed');
                        ;
                    }
                } else {
                    $ret['msg'] = 'warning';
                    $ret['message'] = __('messages.insert.warning', ['what' => 'User']);
                }

            if ($request->ajax()) {
                return response()->json($ret);
            }
            return back()->with([$ret['msg'] => $ret['message']]);
        }
    }
    
    public function two_fa_edit(Request $request)
    {
        
        if(auth()->user()->id == 1) {
                
                $id = $request->input('id');
        
                $user = User::FindOrFail($id);

                if ($user) {
                    $user->google2fa = $request->input('two_fa');
                    
                    $user->save();

                    try {
                        $ret['link'] = route('admin.users');
                        $ret['msg'] = 'success';
                        $ret['message'] = __('messages.insert.success', ['what' => 'User']);
                    } catch (\Exception $e) {
                        $ret['errors'] = $e->getMessage();
                        $ret['link'] = route('admin.users');
                        $ret['msg'] = 'warning';
                        $ret['message'] = __('messages.insert.success', ['what' => 'User']).' '.__('messages.email.failed');
                        ;
                    }
                } else {
                    $ret['msg'] = 'warning';
                    $ret['message'] = __('messages.insert.warning', ['what' => 'User']);
                }

            if ($request->ajax()) {
                return response()->json($ret);
            }
            return back()->with([$ret['msg'] => $ret['message']]);
        }
    }
        
    public function ambassador_edit(Request $request) {
        
        if(auth()->user()->id == 1) {
                
                $id = $request->input('id');
        
                $user = User::FindOrFail($id);

                if ($user) {
                    $user->ambassador = $request->input('ambassador');
                    
                    $user->save();

                    try {
                        $ret['link'] = route('admin.users');
                        $ret['msg'] = 'success';
                        $ret['message'] = __('messages.insert.success', ['what' => 'User']);
                    } catch (\Exception $e) {
                        $ret['errors'] = $e->getMessage();
                        $ret['link'] = route('admin.users');
                        $ret['msg'] = 'warning';
                        $ret['message'] = __('messages.insert.success', ['what' => 'User']).' '.__('messages.email.failed');
                        ;
                    }
                } else {
                    $ret['msg'] = 'warning';
                    $ret['message'] = __('messages.insert.warning', ['what' => 'User']);
                }

            if ($request->ajax()) {
                return response()->json($ret);
            }
            return back()->with([$ret['msg'] => $ret['message']]);
        }
    }
    
    public function referral_rights_edit(Request $request) {
        
        if(auth()->user()->id == 1) {
                
                $id = $request->input('id');
        
                $user = User::FindOrFail($id);

                if ($user) {
                    $user->referral_rights = $request->input('referral_rights');
                    
                    $user->save();

                    try {
                        $ret['link'] = route('admin.users');
                        $ret['msg'] = 'success';
                        $ret['message'] = __('messages.insert.success', ['what' => 'User']);
                    } catch (\Exception $e) {
                        $ret['errors'] = $e->getMessage();
                        $ret['link'] = route('admin.users');
                        $ret['msg'] = 'warning';
                        $ret['message'] = __('messages.insert.success', ['what' => 'User']).' '.__('messages.email.failed');
                        ;
                    }
                } else {
                    $ret['msg'] = 'warning';
                    $ret['message'] = __('messages.insert.warning', ['what' => 'User']);
                }

            if ($request->ajax()) {
                return response()->json($ret);
            }
            return back()->with([$ret['msg'] => $ret['message']]);
        }
    }
    
    public function vip_user_edit(Request $request) {
        
        if(auth()->user()->id == 1) {
                
                $id = $request->input('id');
        
                $user = User::FindOrFail($id);

                if ($user) {
                    $user->vip_user = $request->input('vip_user');
                    
                    $user->save();

                    try {
                        $ret['link'] = route('admin.users');
                        $ret['msg'] = 'success';
                        $ret['message'] = __('messages.insert.success', ['what' => 'User']);
                    } catch (\Exception $e) {
                        $ret['errors'] = $e->getMessage();
                        $ret['link'] = route('admin.users');
                        $ret['msg'] = 'warning';
                        $ret['message'] = __('messages.insert.success', ['what' => 'User']).' '.__('messages.email.failed');
                        ;
                    }
                } else {
                    $ret['msg'] = 'warning';
                    $ret['message'] = __('messages.insert.warning', ['what' => 'User']);
                }

            if ($request->ajax()) {
                return response()->json($ret);
            }
            return back()->with([$ret['msg'] => $ret['message']]);
        }
    }
    
    public function whitelisting_comptete_edit(Request $request) {
        
        if(auth()->user()->id == 1) {
                
                $id = $request->input('id');
        
                $user = User::FindOrFail($id);

                if ($user) {
                    $user->whitelisting_comptete = $request->input('whitelisting_comptete');
                    
                    $user->save();

                    try {
                        $ret['link'] = route('admin.users');
                        $ret['msg'] = 'success';
                        $ret['message'] = __('messages.insert.success', ['what' => 'User']);
                    } catch (\Exception $e) {
                        $ret['errors'] = $e->getMessage();
                        $ret['link'] = route('admin.users');
                        $ret['msg'] = 'warning';
                        $ret['message'] = __('messages.insert.success', ['what' => 'User']).' '.__('messages.email.failed');
                        ;
                    }
                } else {
                    $ret['msg'] = 'warning';
                    $ret['message'] = __('messages.insert.warning', ['what' => 'User']);
                }

            if ($request->ajax()) {
                return response()->json($ret);
            }
            return back()->with([$ret['msg'] => $ret['message']]);
        }
    }
    
    public function whitelisting_balance_edit(Request $request) {
        
        if(auth()->user()->id == 1) {
                
                $id = $request->input('id');
        
                $user = User::FindOrFail($id);

                if ($user) {
                    $user->whitelist_balance = $request->input('whitelisting_balance');
                    
                    $user->save();

                    try {
                        $ret['link'] = route('admin.users');
                        $ret['msg'] = 'success';
                        $ret['message'] = __('messages.insert.success', ['what' => 'User']);
                    } catch (\Exception $e) {
                        $ret['errors'] = $e->getMessage();
                        $ret['link'] = route('admin.users');
                        $ret['msg'] = 'warning';
                        $ret['message'] = __('messages.insert.success', ['what' => 'User']).' '.__('messages.email.failed');
                        ;
                    }
                } else {
                    $ret['msg'] = 'warning';
                    $ret['message'] = __('messages.insert.warning', ['what' => 'User']);
                }

            if ($request->ajax()) {
                return response()->json($ret);
            }
            return back()->with([$ret['msg'] => $ret['message']]);
        }
    }

    
    
    /**
     * Display the specified resource.
     *
     * @param string $id
     * @param string $type
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     * @version 1.1
     * @since 1.0
     */
    public function show(Request $request, $id=null, $type=null)
    {
        if($request->ajax()){
            $id = $request->input('uid');
            $type = $request->input('req_type');
            $user = User::FindOrFail($id);
            if ($type == 'transactions') {
                $transactions = \App\Models\Transaction::where(['user' => $id, 'status' => 'approved'])->latest()->get();
                return view('modals.user_transactions', compact('user', 'transactions'))->render();
            }
            if ($type == 'activities') {
                $activities = \App\Models\Activity::where('user_id', $id)->latest()->limit(50)->get();
                return view('modals.user_activities', compact('user', 'activities'))->render();
            }
            // v1.1
            if ($type == 'referrals') {
                $refered = User::where('referral', $user->id)->get(['id', 'name', 'created_at', 'email']);
                foreach ($refered as $refer) {
                    $ref_count = User::where('referral', $refer->id)->count();
                    if($ref_count > 0){
                        $refer->refer_to = $ref_count;
                    }else{
                        $refer->refer_to = 0;
                    }
                }
                return view('modals.user_referrals', compact('user', 'refered'))->render();
            }
            
            if ($type == 'withdraw') {
                $withdrawal = \App\Models\Transaction::where([ 'id', $id])->first()->get();
                return view('modals.user_withdrawal', compact('user', 'withdrawal'))->render();
            }
            if ($type == 'reinvestment') {
                $reinvestment = \App\Models\Transaction::where(['id', $id])->first()->get();
                return view('modals.user_reinvestment', compact('user', 'reinvestment'))->render();
            }
            if ($type == 'note') {
//                $note = \App\Models\Transaction::where(['id', $id])->first()->get();
//                return view('modals.user_note', compact('user', 'note'))->render();
                return view('modals.user_note', compact('user'))->render();
            }
        }

        $user = User::FindOrFail($id);
//        if ($type == 'details') {
//            $order_by   = (gmvl('user_order_by', 'id')=='token') ? 'tokenBalance' : gmvl('user_order_by', 'id');
//            $ordered    = gmvl('user_ordered', 'DESC');
//            $users = User::where('status', 'active')->whereNotNull('email_verified_at')->where('role', '!=', 'admin')->get();
//            $refered = User::FindOrFail($id)->referrals();
//            return view('admin.user_details', compact('user', 'refered', 'users'))->render();
//        }
        
        if ($type == 'details') {
            
            if(auth()->user()->id == 1) {
                $order_by   = (gmvl('user_order_by', 'id')=='token') ? 'tokenBalance' : gmvl('user_order_by', 'id');
                $ordered    = gmvl('user_ordered', 'DESC');
                $users = User::where('status', 'active')->whereNotNull('email_verified_at')->where('role', '!=', 'admin')->get();
                $refered = User::FindOrFail($id)->referrals();
                
                $wallet = DB::table('wallets')
                ->where('user_id', $user->id)
                ->first(['id', 'custom_wallet_name', 'currency', 'amount', 'user_id', 'publickey', 'wallet_address', 'created_at']);
                
                $sweb3 = new SWeb3('https://rpc.ankr.com/eth');
                $sweb3->chainId = 0x1;

                // Assuming you have the contract addresses for USDC and USDT
                $contractAddressUSDC = '0xA0b86991c6218b36c1d19D4a2e9Eb0cE3606eB48';
                $contractAddressUSDT = '0xdAC17F958D2ee523a2206206994597C13D831ec7';
                
                if ($wallet) {
                    $wallet->balanceETH = $this->getBalance($sweb3, Crypt::decryptString($wallet->wallet_address));
                    $wallet->balanceUSDC = $this->getBalance($sweb3, Crypt::decryptString($wallet->wallet_address), $contractAddressUSDC);
                    $wallet->balanceUSDT = $this->getBalance($sweb3, Crypt::decryptString($wallet->wallet_address), $contractAddressUSDT);
                } else {
                    $wallet = false;
                }
                
                return view('admin.user_details', compact('user', 'refered', 'users', 'wallet'))->render();
            } else {
            
                $order_by   = (gmvl('user_order_by', 'id')=='token') ? 'tokenBalance' : gmvl('user_order_by', 'id');
                $ordered    = gmvl('user_ordered', 'DESC');

                // Get the user's transactions
                $trnxs = \App\Models\Transaction::whereNotIn('status', ['deleted', 'new'])
                    ->whereNotIn('tnx_type', ['withdraw', 'bonus', 'referral', 'demo'])
                    ->whereNotIn('plan', ['RBC', 'Bonus', 'Referral', "DOGE", "BTC", "ETH", "Transaction from"])
                    ->whereNotIn('user', ['1', '2', '3', '4'])
                    ->where('user', $id)  // Assuming that the column that relates the transaction to the user is 'user'
                    ->where('status', "approved")
                    ->where(function($query) {
                        $query->where(function($query) {
                            $query->where('duration', "3 Month")
                            ->where('created_at', '<', date("Y-m-d", strtotime("-3 Month +20 days")));
                        })->orWhere(function($query) {
                            $query->where('duration', "6 Month")
                            ->where('created_at', '<', date("Y-m-d", strtotime("-6 Month +20 days")));
                        })->orWhere(function($query) {
                            $query->where('duration', "12 Month")
                            ->where('created_at', '<', date("Y-m-d", strtotime("-12 Month +20 days")));
                        });
                    })
                    ->when(auth()->user()->id != 1, function($query) {
                        $query->whereDoesntHave('tnxUser', function($subQuery) {
                            $subQuery->where('vip_user', 1);
                        });
                    })
                    ->orderByRaw("DATE_ADD(created_at, INTERVAL IF(duration = '3 Month', 3, IF(duration = '6 Month', 6, 12)) MONTH)")
                    ->get();

                // If the user has transactions coming to an end soon or already ended, then return the view
                if ($trnxs->count() > 0) {
                    $users = User::where('status', 'active')->whereNotNull('email_verified_at')->where('role', '!=', 'admin')->get();
                    $refered = User::FindOrFail($id)->referrals();
                    return view('admin.user_details', compact('user', 'refered', 'users'))->render();
                } else {
                    // Handle the case where the user does not have the required transactions. Maybe return a different view or throw an error.
                    // For this example, I'll return back with an error message. You can handle it differently based on your needs.
                    abort(404);
                }
            }
        }

        
        if ($type == 'reinvest_trnx') {
            if (version_compare(phpversion(), '7.1', '>=')) {
                ini_set('precision', 17);
                ini_set('serialize_precision', -1);
            }

            $trnx_id = $request->trnx_id;

            $trnx = Transaction::FindOrFail($trnx_id);

                $tc = new TC();
    //            $fiat_amount = $request->input('fiat_amount');
    //            $crypto_amount = $request->input('crypto_amount');
    //            $bonus_calc = isset($request->bonus_calc) ? true : false;
    //            $tnx_type = $request->input('type');
    //            $tnx_status = $request->input('status');
    //            $tnx_plan = $request->input('plan');
    //            $payment_currency = strtolower($request->input('payment_currency'));
    //            $crypto_currency = strtolower($request->input('crypto_currency'));
    //            $currency_rate = Setting::exchange_rate($tc->get_current_price(), $crypto_currency);
                $base_currency = strtolower(base_currency());
                $base_currency_rate = Setting::exchange_rate($tc->get_current_price(), $base_currency);
                $all_currency_rate = json_encode(Setting::exchange_rate($tc->get_current_price(), 'except'));

                $added_time = Carbon::now()->toDateTimeString();
    //            $tnx_date   = $request->tnx_date.' '.date('H:i');
    //            $duration = $request->input('duration');
    //            $plan = $request->input('plan');

                $save_data = [
                    'created_at' => $added_time,
                    'tnx_id' => set_id(rand(100, 999), 'trnx'),
                    'tnx_type' => $trnx->tnx_type,
                    'tnx_time' => $added_time,
                    'tokens' => $trnx->tokens,
                    'bonus_on_base' => 0,
                    'bonus_on_token' => 0,
                    'total_bonus' => 0,
                    'total_tokens' => $trnx->total_tokens,
                    'stage' => $trnx->stage,
                    'user' => $trnx->user,

                    'amount' => $trnx->amount,
                    'receive_amount' => $trnx->receive_amount,
                    'receive_currency' => $trnx->receive_currency,
                    'base_amount' => $trnx->base_amount,
                    'base_currency' => $trnx->base_currency,
                    'base_currency_rate' => $base_currency_rate,
                    'currency' => $trnx->currency,

                    'currency_rate' => "1",
                    'all_currency_rate' => $all_currency_rate,
                    'payment_method' => $trnx->payment_method,
                    'payment_to' => '',
                    'payment_id' => rand(1000, 9999),
                    'details' => $trnx->details,
                    'status' => $trnx->status,
                    'duration' => $trnx->duration,
                    'plan' => $trnx->plan,
                    'percentage' => $trnx->percentage,
                ];

                $iid = Transaction::insertGetId($save_data);

                if ($iid != null) {
                    $ret['msg'] = 'info';
                    $ret['message'] = __('messages.trnx.manual.success');

    //                $address = $request->input('wallet_address');
                    $transaction = Transaction::where('id', $iid)->first();
    //                $transaction->tnx_id = set_id($iid, 'trnx');
    //                $transaction->wallet_address = $address;
    //                $transaction->extra = ($address) ? json_encode(['address' => $address]) : null;
    //                $transaction->status = 'approved';
                    $transaction->save();

                    IcoStage::token_add_to_account($transaction, 'add');

                    $transaction->checked_by = json_encode(['name' => Auth::user()->name, 'id' => Auth::id()]);

                    $transaction->added_by = set_added_by(Auth::id(), Auth::user()->role);
                    $transaction->checked_time = now();
                    $transaction->save();
                    // Start adding
                    IcoStage::token_add_to_account($transaction, '', 'add');

                    $ret['link'] = route('admin.transactions');
                    $ret['msg'] = 'success';
                    $ret['message'] = __('messages.token.success');
                } else {
                    $ret['msg'] = 'error';
                    $ret['message'] = __('messages.token.failed');
                    Transaction::where('id', $iid)->delete();
                }

                //delete old transaction
                $transaction_old = Transaction::where('id', $trnx_id)->first();
                $transaction_old->status = "deleted";
                $transaction_old->save;


            //        if ($request->ajax()) {
    //            return response()->json($ret);
    //        }
    //        return back()->with([$ret['msg'] => $ret['message']]);
            return response()->json($result);
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
    
    
    public function status(Request $request)
    {
        $id = $request->input('uid');
        $type = $request->input('req_type');
        
        if(!super_access()) {
            $up = User::where('id', $id)->first();
            if($up) {
                if($up->role!='user') {
                    $result['msg'] = 'warning';
                    $result['message'] = __("You do not have enough permissions to perform requested operation.");
                    return response()->json($result);
                }
            }
        }

        if ($type == 'suspend_user') {
            $admin_count = User::where('role', 'admin')->count();
            if ($admin_count >= 1) {
                $up = User::where('id', $id)->update([
                    'status' => 'suspend',
                ]);
                if ($up) {
                    $result['msg'] = 'warning';
                    $result['css'] = 'danger';
                    $result['status'] = 'active_user';
                    $result['message'] = 'User Suspend Success!!';
                } else {
                    $result['msg'] = 'warning';
                    $result['message'] = 'Failed to Suspend!!';
                }
            } else {
                $result['msg'] = 'warning';
                $result['message'] = 'Minimum one admin account is required!';
            }

            return response()->json($result);
        }
        if ($type == 'active_user') {
            $up = User::where('id', $id)->update([
                'status' => 'active',
            ]);
            if ($up) {
                $result['msg'] = 'success';
                $result['css'] = 'success';
                $result['status'] = 'suspend_user';
                $result['message'] = 'User Active Success!!';
            } else {
                $result['msg'] = 'warning';
                $result['message'] = 'Failed to Active!!';
            }
            return response()->json($result);
        }
        if ($type == 'reset_pwd') {
            $pwd = str_random(15);
            $up = User::where('id', $id)->first();
            $up->password = Hash::make($pwd);

            $update = (object) [
                'new_password' => $pwd,
                'name' => $up->name,
                'email' => $up->email,
                'id' => $up->id,
            ];
            if ($up->save()) {
                try {
                    $up->notify(new PasswordResetByAdmin($update));
                    $result['msg'] = 'success';
                    $result['message'] = 'Password Changed!! ';
                } catch (\Exception $e) {
                    $ret['errors'] = $e->getMessage();
                    $result['msg'] = 'warning';
                    $result['message'] = 'Password Changed!! but user was not notified. Please! check your email setting and try again.';
                }
            } else {
                $result['msg'] = 'warning';
                $result['message'] = 'Failed to Changed!!';
            }
            return response()->json($result);
        }
        if ($type == 'reset_2fa') {
            $user = User::where('id', $id)->first();
            if ($user) {
                $user->notify(new Reset2FA($user));
                $result['msg'] = 'success';
                $result['message'] = '2FA confirmation email send to the user.';
            } else {
                $ret['errors'] = $e->getMessage();
                $result['msg'] = 'warning';
                $result['message'] = 'Failed to reset 2FA!!';
            }
            return response()->json($result);
        }
        
        if ($type == 'reinvest_trnx') {
            if (version_compare(phpversion(), '7.1', '>=')) {
                ini_set('precision', 17);
                ini_set('serialize_precision', -1);
            }

            $trnx_id = $request->trnx_id;

            $trnx = Transaction::FindOrFail($trnx_id);

                $tc = new TC();
    //            $fiat_amount = $request->input('fiat_amount');
    //            $crypto_amount = $request->input('crypto_amount');
    //            $bonus_calc = isset($request->bonus_calc) ? true : false;
    //            $tnx_type = $request->input('type');
    //            $tnx_status = $request->input('status');
    //            $tnx_plan = $request->input('plan');
    //            $payment_currency = strtolower($request->input('payment_currency'));
    //            $crypto_currency = strtolower($request->input('crypto_currency'));
    //            $currency_rate = Setting::exchange_rate($tc->get_current_price(), $crypto_currency);
                $base_currency = strtolower(base_currency());
                $base_currency_rate = Setting::exchange_rate($tc->get_current_price(), $base_currency);
                $all_currency_rate = json_encode(Setting::exchange_rate($tc->get_current_price(), 'except'));

                $added_time = Carbon::now()->toDateTimeString();
    //            $tnx_date   = $request->tnx_date.' '.date('H:i');
    //            $duration = $request->input('duration');
    //            $plan = $request->input('plan');

                $save_data = [
                    'created_at' => $added_time,
                    'tnx_id' => set_id(rand(100, 999), 'trnx'),
                    'tnx_type' => $trnx->tnx_type,
                    'tnx_time' => $added_time,
                    'tokens' => $trnx->tokens,
                    'bonus_on_base' => 0,
                    'bonus_on_token' => 0,
                    'total_bonus' => 0,
                    'total_tokens' => $trnx->total_tokens,
                    'stage' => $trnx->stage,
                    'user' => $trnx->user,

                    'amount' => $trnx->amount,
                    'receive_amount' => $trnx->receive_amount,
                    'receive_currency' => $trnx->receive_currency,
                    'base_amount' => $trnx->base_amount,
                    'base_currency' => $trnx->base_currency,
                    'base_currency_rate' => $base_currency_rate,
                    'currency' => $trnx->currency,

                    'currency_rate' => "1",
                    'all_currency_rate' => $all_currency_rate,
                    'payment_method' => $trnx->payment_method,
                    'payment_to' => '',
                    'payment_id' => rand(1000, 9999),
                    'details' => $trnx->details,
                    'status' => $trnx->status,
                    'duration' => $trnx->duration,
                    'plan' => $trnx->plan,
                    'percentage' => $trnx->percentage,
                ];

                $iid = Transaction::insertGetId($save_data);

                if ($iid != null) {
                    $ret['msg'] = 'info';
                    $ret['message'] = __('messages.trnx.manual.success');

    //                $address = $request->input('wallet_address');
                    $transaction = Transaction::where('id', $iid)->first();
    //                $transaction->tnx_id = set_id($iid, 'trnx');
    //                $transaction->wallet_address = $address;
    //                $transaction->extra = ($address) ? json_encode(['address' => $address]) : null;
    //                $transaction->status = 'approved';
                    $transaction->save();

                    IcoStage::token_add_to_account($transaction, 'add');

                    $transaction->checked_by = json_encode(['name' => Auth::user()->name, 'id' => Auth::id()]);

                    $transaction->added_by = set_added_by(Auth::id(), Auth::user()->role);
                    $transaction->checked_time = now();
                    $transaction->save();
                    // Start adding
                    IcoStage::token_add_to_account($transaction, '', 'add');

                    $ret['link'] = route('admin.transactions');
                    $ret['msg'] = 'success';
                    $ret['message'] = __('messages.token.success');
                } else {
                    $ret['msg'] = 'error';
                    $ret['message'] = __('messages.token.failed');
                    Transaction::where('id', $iid)->delete();
                }

                //delete old transaction
                $transaction_old = Transaction::where('id', $trnx_id)->first();
                $transaction_old->status = "deleted";
                $transaction_old->save;


            //        if ($request->ajax()) {
    //            return response()->json($ret);
    //        }
    //        return back()->with([$ret['msg'] => $ret['message']]);
            return response()->json($result);
        }
    }

    /**
     * wallet change request
     *
     * @return \Illuminate\Http\Response
     * @version 1.0.0
     * @since 1.0
     */
    public function wallet_change_request()
    {
        $meta_data = GlobalMeta::where('name', 'user_wallet_address_change_request')->get();
        return view('admin.user-request', compact('meta_data'));
    }
    public function wallet_change_request_action(Request $request)
    {
        $meta = GlobalMeta::FindOrFail($request->id);
        $ret['msg'] = 'info';
        $ret['message'] = __('messages.nothing');
        if ($meta) {
            $action = $request->action;

            if ($action == 'approve') {
                $meta->user->walletType = $meta->data()->name;
                $meta->user->walletAddress = $meta->data()->address;

                $meta->user->save();
                $meta->delete();
                $ret['msg'] = 'success';
                $ret['message'] = __('messages.wallet.approved');
            }
            if ($action == 'reject') {
                $ret['msg'] = 'warning';
                $ret['message'] = __('messages.wallet.cancel');
                $meta->delete();
            }
        } else {
            $ret['msg'] = 'warning';
            $ret['message'] = __('messages.wallet.failed');
        }

        if ($request->ajax()) {
            return response()->json($ret);
        }
        return back()->with([$ret['msg'] => $ret['message']]);
    }

    /**
     * Delete all unverified users
     *
     * @return \Illuminate\Http\Response
     * @version 1.0.0
     * @since 1.0
     */
    public function delete_unverified_user(Request $request)
    {
        $ret['msg'] = 'info';
        $ret['message'] = __('messages.nothing');

        $user = User::where(['registerMethod' => "Email", 'email_verified_at' => NULL])->get();
        if($user->count()){
            $data = $user->each(function($item){
                $item->meta()->delete();
                $item->logs()->delete();
                $item->delete();
            });

            if($data){
                $ret['msg'] = 'success';
                $ret['message'] = __('messages.delete.delete', ['what' => 'Unvarified users']);
            }
            else{
                $ret['msg'] = 'warning';
                $ret['message'] = __('messages.delete.delete_failed', ['what' => 'Unvarified users']);
            }
        }
        else{
            $ret['msg'] = 'success';
            $ret['alt'] = 'no';
            $ret['message'] = __('There has not any unvarified users!');
        }


        if ($request->ajax()) {
            return response()->json($ret);
        }
        return back()->with([$ret['msg'] => $ret['message']]);
    }
    
    public function fetchMessages($user_id) {
        // Check if the $user_id is a phone number with country code
        if (preg_match("/^(\+\d{1,4})?[\s.-]?\d{1,15}$/", $user_id)) {
            $messages = ClientMessage::where('user', $user_id)->get();

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

        // If not a phone number with country code, just return the $user_id as it is.
        return response()->json(['error' => 'Invalid user ID.']);
    }

    
//    public function fetchMessagesUnknown {
//        
//    }

    

}
