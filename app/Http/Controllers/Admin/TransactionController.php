<?php

namespace App\Http\Controllers\Admin;
/**
 * Transactions Controller
 *
 * @package TokenLite
 * @author Softnio
 * @version 1.1.0
 */
use Auth;
use DB;
use DateTime;
use Validator;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Setting;
use App\Models\IcoStage;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Helpers\ReferralHelper;
use App\Notifications\TnxStatus;
use App\Notifications\Refund;
use App\Http\Controllers\Controller;
use App\Helpers\TokenCalculate as TC;
use Illuminate\Support\Facades\Crypt;
use App\Models\ClientMessage;


class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @version 1.1
     * @since 1.0
     * @return void
     */
    public function index(Request $request, $status = '')
    {
        if(auth()->user()->role == 'user' && $status != 'expiring') {
            // Redirecting to the 'expiring' status page
            return redirect()->route('admin.transactions', 'expiring');
        } 
        
        $per_page = gmvl('tnx_per_page', 10);
        $order_by = gmvl('tnx_order_by', 'id');
        $ordered  = gmvl('tnx_ordered', 'DESC');

        if($status=='referral' || $status=='bonus') {
            $trnxs = Transaction::whereNotIn('status', ['deleted', 'new'])->where('tnx_type', $status);
        } elseif($status=='bonuses') {
            $trnxs = Transaction::whereNotIn('status', ['deleted', 'new'])->whereNotIn('tnx_type', ['withdraw'])->whereIn('tnx_type', ['referral', 'bonus']);
        } elseif($status=='approved') {
            $trnxs = Transaction::whereNotIn('status', ['deleted', 'new'])->whereNotIn('tnx_type', ['withdraw', 'bonus', 'referral'])->where('status', $status);
        }  elseif($status=='pending') {
            $trnxs = Transaction::whereNotIn('status', ['deleted', 'new'])->whereNotIn('tnx_type', ['withdraw'])->whereIn('status', [$status, 'onhold']);
        } elseif($status=='expiring') {
            
            
            $trnxs = Transaction::whereNotIn('status', ['deleted', 'new'])
            ->whereNotIn('tnx_type', ['withdraw', 'bonus', 'referral', 'demo'])
            ->whereNotIn('plan', ['RBC', 'Bonus', 'Referral', "DOGE", "BTC", "ETH", "Transaction from"])
            ->whereNotIn('user', ['1', '2', '3', '4'])
            ->where('status', "approved")
            ->where(function($query) {
                $query->where(function($query) {
                    $query->where('duration', "3 Month")
                    ->where('created_at', '<', date("Y-m-d", strtotime("-3 Month +".((auth()->user()->role == 'admin') ? 20 : 20)." days")));
                })->orWhere(function($query) {
                    $query->where('duration', "6 Month")
                    ->where('created_at', '<', date("Y-m-d", strtotime("-6 Month +".((auth()->user()->role == 'admin') ? 20 : 20)." days")));
                })->orWhere(function($query) {
                    $query->where('duration', "12 Month")
                    ->where('created_at', '<', date("Y-m-d", strtotime("-12 Month +".((auth()->user()->role == 'admin') ? 20 : 20)." days")));
                });
            })
            ->when(auth()->user()->role == 'user', function($query) {
                $query->whereDoesntHave('tnxUser', function($subQuery) {
                    $subQuery->where('vip_user', 1);
                });
            })
            ->orderBy('created_at', 'ASC')
            ->paginate(100)->appends($request);

        }  elseif($status=='percentage') {
            $trnxs = Transaction::whereNotIn('status', ['deleted', 'new'])
            ->whereNotIn('tnx_type', ['withdraw', 'bonus', 'referral', 'demo'])
            ->whereNotIn('plan', ['RBC', 'Bonus', 'Referral', "DOGE", "BTC", "ETH", "Transaction from"])
            ->whereNotIn('user', ['1', '2', '3', '4'])
            ->where('status', "approved")->orderBy("percentage", "DESC", "decimal")->paginate($per_page);
        
        } elseif($status=='biggest') {
            $trnxs = Transaction::whereNotIn('status', ['deleted', 'new'])
            ->whereNotIn('tnx_type', ['withdraw', 'bonus', 'referral', 'demo'])
            ->whereNotIn('plan', ['RBC', 'Bonus', 'Referral', "DOGE", "BTC", "ETH", "Transaction from"])
            ->whereNotIn('user', ['1', '2', '3', '4'])
            ->where('status', "approved")->orderBy("equity", "DESC", "decimal")->paginate($per_page);
            
            
        } elseif($status!=null) {
            $trnxs = Transaction::whereNotIn('status', ['deleted', 'new'])->whereNotIn('tnx_type', ['withdraw'])->where('status', $status);
        } else {
            $trnxs = Transaction::whereNotIn('status', ['deleted', 'new'])->whereNotIn('tnx_type', ['withdraw']);
        }

        // Advance search v1.1.0
        if($request->s){
            $trnxs  = Transaction::AdvancedFilter($request);
        }
        if($request->filter){
            $trnxs = Transaction::AdvancedFilter($request);
        }
        
        if ($request->filled('exclude_user_ids')) {
            $idsToExclude = ['1', '2', '3', '4']; // replace with your logic for determining which IDs to exclude
            $trnxs->whereNotIn('user', $idsToExclude);
        }
        
        // Add the new orderByRaw() here
        if ($order_by === 'tokenBalance' && $status!='expiring' && $status!='percentage' && $status!='biggest' || $order_by === 'equity' && $status!='expiring' && $status!='percentage' && $status!='biggest' || $order_by === 'percentage' && $status!='expiring' && $status!='percentage' && $status!='biggest') {
            $trnxs = $trnxs->orderBy($order_by, $ordered, "decimal")->paginate($per_page);
        } elseif ($order_by === 'created_at' && $status!='expiring' && $status!='percentage' && $status!='biggest') {
            $trnxs = $trnxs->orderBy($order_by, $ordered)->paginate($per_page);
        } elseif ($status!='expiring' && $status!='percentage' && $status!='biggest') {
            $trnxs = $trnxs->orderBy($order_by, $ordered)->paginate($per_page);
        }

        $is_page = (empty($status) ? 'all' : $status);
        $pmethods = PaymentMethod::where('status', 'active')->get();
        $gateway = PaymentMethod::all()->pluck('payment_method');
        $stages = IcoStage::whereNotIn('status', ['deleted'])->get();
        $pm_currency = PaymentMethod::Currency;
        $users = User::where('status', 'active')->whereNotNull('email_verified_at')->where('role', '!=', 'admin')->get();
        $pagi = $trnxs->appends($request);
        
        
        $t = new Datetime('19:30:00');
        
        $result = DB::table('coin')->where('date', $t->format('U')."000")->first();

        $current_price = $result->close ?? 1;
        
        return view('admin.transactions', compact('trnxs', 'users', 'stages', 'pmethods', 'pm_currency', 'gateway', 'is_page', 'pagi', 'current_price'));
    }
    
    public function show($trnx_id = '')
    {
        if ($trnx_id == '') {
            return __('messages.wrong');
        } else {
            if(auth()->user()->role == 'admin') {
                $trnx = Transaction::FindOrFail($trnx_id);
                return view('admin.trnx_details', compact('trnx'))->render();
            } else {
            
                $trnx = Transaction::FindOrFail($trnx_id);

                // Check if the user of this transaction has any transactions that are expiring or already expired
                $user_trnxs = Transaction::where('user', $trnx->user) // assuming the user's ID is stored in the 'user_id' field of the Transaction model
                    ->whereNotIn('status', ['deleted', 'new'])
                    ->whereNotIn('tnx_type', ['withdraw', 'bonus', 'referral', 'demo'])
                    ->whereNotIn('plan', ['RBC', 'Bonus', 'Referral', "DOGE", "BTC", "ETH", "Transaction from"])
                    ->whereNotIn('user', ['1', '2', '3', '4'])
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
                    ->when(auth()->user()->role == 'user', function($query) {
                        $query->whereDoesntHave('tnxUser', function($subQuery) {
                            $subQuery->where('vip_user', 1);
                        });
                    })->count(); // We're only interested in the count, not the transactions themselves

                if ($user_trnxs > 0) {
                    return view('admin.trnx_details', compact('trnx'))->render();
                } else {
                    abort(404);
                }
                
            }
        }
    }

    
    public function reinvest2(Request $request) {
        if(auth()->user()->role == 'admin') {
            if (version_compare(phpversion(), '7.1', '>=')) {
                ini_set('precision', 17);
                ini_set('serialize_precision', -1);
            }

            $ret['msg'] = 'info';
            $ret['message'] = __('messages.nothing');
            $validator = Validator::make($request->all(), [
                'total_tokens' => 'required|integer|min:1',
            ], [
                'total_tokens.required' => "Token amount is required!.",
            ]);

            //all variables
            
            $trnx_id = $request->trnx_id;
            $trnx = Transaction::FindOrFail($trnx_id);
//            $user = User::find($request->input('user_id'));
            $user = User::find($trnx->user);
            
            $tc = new TC();
            $base_currency = strtolower(base_currency());
            $base_currency_rate = Setting::exchange_rate($tc->get_current_price(), $base_currency);
            $all_currency_rate = json_encode(Setting::exchange_rate($tc->get_current_price(), 'except'));

            $added_time = Carbon::now()->toDateTimeString();

            $transactionVariables = [];
            
            if (!empty($request->amount_reinvestment) && $request->amount_reinvestment != "0") {
                $transactionVariables[] = [
                        'amount' => $request->amount_reinvestment,
                        'duration' => $request->duration,
                        'plan' => $request->plan,
                ];
            }
            if (!empty($request->amount_reinvestment_new_plan1) && $request->amount_reinvestment_new_plan1 != "0") {
                $transactionVariables[] = [
                    'amount' => $request->amount_reinvestment_new_plan1,
                    'duration' => $request->duration_new_plan1,
                    'plan' => $request->plan_new_plan1,
                ];
            }
            if (!empty($request->amount_reinvestment_new_plan2) && $request->amount_reinvestment_new_plan2 != "0") {
                $transactionVariables[] = [
                    'amount' => $request->amount_reinvestment_new_plan2,
                    'duration' => $request->duration_new_plan2,
                    'plan' => $request->plan_new_plan2,
                ];
            }
            if (!empty($request->amount_reinvestment_new_plan3) && $request->amount_reinvestment_new_plan3 != "0") {
                $transactionVariables[] = [
                    'amount' => $request->amount_reinvestment_new_plan3,
                    'duration' => $request->duration_new_plan3,
                    'plan' => $request->plan_new_plan3,
                ];
            }
            if (!empty($request->amount_reinvestment_new_plan4) && $request->amount_reinvestment_new_plan4 != "0") {
                $transactionVariables[] = [
                    'amount' => $request->amount_reinvestment_new_plan4,
                    'duration' => $request->duration_new_plan4,
                    'plan' => $request->plan_new_plan4,
                ];
            }
            
            $createdTransactions = []; // Store transaction ids here

            foreach ($transactionVariables as $variables) {
                $save_data = [
                    'created_at' => $added_time,
                    'tnx_id' => set_id(rand(100, 999), 'trnx'),
                    'tnx_type' => "purchase",
                    'tnx_time' => $added_time,
                    'tokens' => $variables['amount'],
                    'bonus_on_base' => 0,
                    'bonus_on_token' => 0,
                    'total_bonus' => 0,
                    'total_tokens' => $variables['amount'],
                    'stage' => $trnx->stage,
                    'user' => $trnx->user,
                    'amount' => $variables['amount'],
                    'receive_amount' => $variables['amount'],
                    'receive_currency' => $user->base_currency,
                    'base_amount' => $variables['amount'],
                    'base_currency' => $user->base_currency,
                    'base_currency_rate' => $base_currency_rate,
                    'currency' => $user->base_currency,
                    'currency_rate' => "1",
                    'all_currency_rate' => $all_currency_rate,
                    'payment_method' => "reinvestment",
                    'payment_to' => '',
                    'payment_id' => rand(1000, 9999),
                    'details' => $trnx->details,
                    'status' => $trnx->status,
                    'duration' => $variables['duration'],
                    'plan' => $variables['plan'],
                    'percentage' => $variables['percentage'],
                ];

                $iid = Transaction::insertGetId($save_data);
                if ($iid != null) {
                    $createdTransactions[] = $iid;
                } else {
                    $ret['msg'] = 'error';
                    $ret['message'] = __('messages.token.failed');
                    Transaction::where('id', $iid)->delete();
                }
            }

            // After creating all 5 transactions, approve each of them
            foreach ($createdTransactions as $transactionId) {
                $transaction = Transaction::where('id', $transactionId)->first();
                $transaction->save();
                IcoStage::token_add_to_account($transaction, 'add');
                $transaction->checked_by = json_encode(['name' => Auth::user()->name, 'id' => Auth::id()]);
                $transaction->added_by = set_added_by(Auth::id(), Auth::user()->role);
                $transaction->checked_time = now();
                $transaction->save();
                IcoStage::token_add_to_account($transaction, '', 'add');
                $ret['msg'] = 'success';
                $ret['message'] = __('messages.token.success');
            }

            // Delete the old transaction after creating and confirming the 5 new transactions
            $trnx->status = "deleted";
            $trnx->save();

            return redirect()->back()->with('success', 'Successfully processed');
        }
    }


    public function reinvest(Request $request) {
        if(auth()->user()->role == 'admin') {
        if (version_compare(phpversion(), '7.1', '>=')) {
            ini_set('precision', 17);
            ini_set('serialize_precision', -1);
        }
        
        $ret['msg'] = 'info';
        $ret['message'] = __('messages.nothing');
        $validator = Validator::make($request->all(), [
            'total_tokens' => 'required|integer|min:1',
        ], [
            'total_tokens.required' => "Token amount is required!.",
        ]);
        
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
                'tokens' => $trnx->equity,
                'bonus_on_base' => 0,
                'bonus_on_token' => 0,
                'total_bonus' => 0,
                'total_tokens' => $trnx->equity,
                'stage' => $trnx->stage,
                'user' => $trnx->user,
                
                'amount' => $trnx->equity,
                'receive_amount' => $trnx->equity,
                'receive_currency' => $trnx->receive_currency,
                'base_amount' => $trnx->equity,
                'base_currency' => $trnx->base_currency,
                'base_currency_rate' => $base_currency_rate,
                'currency' => $trnx->currency,
                
                'currency_rate' => "1",
                'all_currency_rate' => $all_currency_rate,
                'payment_method' => "reinvestment",
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
//            $transaction_old = Transaction::where('id', $trnx_id)->first();
//            $transaction_old->status = "deleted";
            $trnx->status = "deleted";
            $trnx->save();
        
        
        //        if ($request->ajax()) {
//            return response()->json($ret);
//        }
//        return back()->with([$ret['msg'] => $ret['message']]);
        return back();
    }
    }

    public function withdraw(Request $request) {
        if(auth()->user()->role == 'admin') {
        if (version_compare(phpversion(), '7.1', '>=')) {
            ini_set('precision', 17);
            ini_set('serialize_precision', -1);
        }
        
        $ret['msg'] = 'info';
        $ret['message'] = __('messages.nothing');
        $validator = Validator::make($request->all(), [
            'total_tokens' => 'required|integer|min:1',
        ], [
            'total_tokens.required' => "Token amount is required!.",
        ]);
        
        $trnx_id = $request->trnx_id;
        $trnx = Transaction::FindOrFail($trnx_id);
        $trnx->status = "deleted";
        $trnx->save();
        
        
        //        if ($request->ajax()) {
//            return response()->json($ret);
//        }
//        return back()->with([$ret['msg'] => $ret['message']]);
        return back();
    }
    }

    public function editNote(Request $request) {
    try {
        // Validate the incoming request
        $request->validate([
            'user_id' => 'required|integer'
        ]);

        // Find the user using the given ID
        $user = User::find($request->input('user_id'));

        // Check if user exists
        if(!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }

        // Replace line breaks with <br>
        $note = $request->input('user_note');
        $note_with_br = str_replace("\r\n", "<br>", $note);  // replace Windows-style line breaks
        $note_with_br = str_replace("\n", "<br>", $note_with_br);  // replace Unix-style line breaks
        $note_with_br = str_replace("\r", "<br>", $note_with_br);  // replace old Mac-style line breaks

        // Update the note of the user
        $user->note = $note_with_br;
        $user->save();

        // Return a successful response
        return redirect()->back()->with('success', 'Note updated successfully');
        
    } catch (\Exception $e) {
        // If there are any exceptions, return an error message
        return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
    }
}

    public function trnx_edit_status(Request $request) {
        if(auth()->user()->role == 'admin') {
        
            $trnx_id = $request->id;
            $trnx = Transaction::FindOrFail($trnx_id);
            
            $trnx->status = $request->status;
            $trnx->save();
            
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
    
    public function trnx_edit_plan(Request $request) {
        if(auth()->user()->role == 'admin') {
        
            $trnx_id = $request->id;
            $trnx = Transaction::FindOrFail($trnx_id);
            
            $trnx->plan = $request->plan;
            $trnx->save();
            
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

    public function trnx_edit_duration(Request $request) {
        if(auth()->user()->role == 'admin') {
        
            $trnx_id = $request->id;
            $trnx = Transaction::FindOrFail($trnx_id);
            
            $trnx->duration = $request->duration;
            $trnx->save();
            
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @version 1.1.0
     * @since 1.0
     */
    public function update(Request $request)
    {
        if(auth()->user()->role == 'admin') {
        if (version_compare(phpversion(), '7.1', '>=')) {
            ini_set('precision', 17);
            ini_set('serialize_precision', -1);
        }

        $type = $request->input('req_type');
        $id = $request->input('tnx_id');
        $ret['msg'] = 'info';
        $ret['message'] = __('messages.nothing');
        $trnx = Transaction::findOrFail($id);
        if ($trnx) {
            $status = $trnx->status;
            if($status == 'approved') {                
                $ret['msg'] = 'info';
                $ret['message'] = __('messages.trnx.admin.already_approved');
            } else {
                if( $status == 'canceled' && in_array($type, ['approved', 'deleted']) ) {
                    if($type == 'deleted') {
                        $ret = $this->deleted_tnx($trnx);
                    } elseif($type == 'approved') {
                        $ret = $this->approved_tnx($trnx, $request);
                    }
                }elseif(in_array($status, ['onhold', 'pending']) && in_array($type, ['approved', 'canceled']) ){
                    if( $type == 'approved' ){
                        $ret = $this->approved_tnx($trnx, $request);
                    }elseif($type == 'canceled'){
                        $ret = $this->canceled_tnx($trnx, $request);
                    }
                }
            }
        }

        if($type == 'refund' && $trnx){
            $refund = $this->refund($trnx, $request->input('message'));
            if($refund){
                $ret['refund'] = $refund;
                $ret['msg'] = 'success';
                $ret['reload'] = true;
                $ret['message'] = __('Refund Successful!');
            }else{
                $ret['msg'] = 'warning';
                $ret['message'] = __('Already Refunded!');
            }
        }

        $ret['data'] = $trnx;
        if ($request->ajax()) {
            return response()->json($ret);
        }
        return back()->with([$ret['msg'] => $ret['message']]);
    }
    }

    /**
     * Cancel the Transaction by Admin
     *
     * @version 1.0.0
     * @since 1.1.4
     */
    private function canceled_tnx($trnx, $request)
    {
        if(auth()->user()->role == 'admin') {
        $ret['msg'] = 'warning';
        $ret['message'] = __('Unable to cancel the transaction, reload the page.');
        if ($trnx) {
            if($trnx->status == 'canceled' || $trnx->status == 'approved') {
                $ret['msg'] = 'info';
                $ret['message'] = ($trnx->status == 'approved') ? __('messages.trnx.admin.already_approved') : __('messages.trnx.admin.already_canceled');
                 return $ret;
            }

            if(in_array($trnx->status, ['onhold', 'pending'])){
                $trnx->status = 'canceled';
                $trnx->checked_by = json_encode(['name' => Auth::user()->name, 'id' => Auth::id()]);
                $trnx->checked_time = date('Y-m-d H:i:s');
                $trnx->save();
                IcoStage::token_add_to_account($trnx, 'sub');
                
                try {
                    $trnx->tnxUser()->notify((new TnxStatus($trnx, 'rejected-user')));
                    $ret['msg'] = 'success';
                    $ret['message'] = __('messages.trnx.admin.canceled');
                } catch (\Exception $e) {
                    $ret['errors'] = $e->getMessage();
                    $ret['msg'] = 'warning';
                    $ret['message'] = __('messages.trnx.admin.canceled').' '.__('messages.email.failed');
                }
            }
        }
        return $ret;
    }
    }

    /**
     * Approve the Transaction by Admin
     *
     * @version 1.0.0
     * @since 1.1.4
     */
    private function approved_tnx($trnx, $request)
    {
        if(auth()->user()->role == 'admin') {
        $ret['msg'] = 'warning';
        $ret['message'] = __('Unable to approve the transaction, reload the page.');

        if($trnx->status == 'deleted' || $trnx->status == 'approved') {
            $ret['msg'] = 'info';
            $ret['message'] = __('messages.trnx.admin.already_updated', ['status' => $trnx->status]);
            return $ret;
        }

        if ($trnx) {
            $msg = __('messages.form.wrong');
            $validator = Validator::make($request->all(), [ 'amount' => 'gt:0' ]);
            if ($validator->fails() && gateway_type($trnx->payment_method, 'short') != 'online') {
                if ($validator->errors()->has('amount')) {
                    $msg = $validator->errors()->first();
                }
                $ret['msg'] = 'warning';
                $ret['message'] = $msg;
            } else {
                if (gateway_type($trnx->payment_method, 'short') != 'online') {
                    $chk_adjust = $request->input('chk_adjust');
                    $receive_amount = round($request->input('amount'), max_decimal());
                    $adjust_token = round($request->input('adjusted_token'), min_decimal());
                    $token = round($request->input('token'), min_decimal());
                    $base_bonus = round($request->input('base_bonus'), min_decimal());
                    $token_bonus = round($request->input('token_bonus'), min_decimal());
                }
                
                if(in_array($trnx->status, ['onhold', 'pending', 'canceled'])){
                    $old_status = $trnx->status;

                    if (gateway_type($trnx->payment_method, 'short') != 'online') {
                        if ($chk_adjust == 1) {
                            $old_tokens = $trnx->total_tokens;
                            $old_base_amount = $trnx->base_amount;
                            $trnx->tokens = $token;
                            $trnx->base_amount = $token * $trnx->base_currency_rate;
                            $trnx->total_bonus = $base_bonus + $token_bonus;
                            $trnx->bonus_on_base = $base_bonus;
                            $trnx->bonus_on_token = $token_bonus;
                            $trnx->total_tokens = $adjust_token;
                            $trnx->amount = $receive_amount;
    
                            if ($old_status != 'canceled') {
                                $adjust_stage_token = $old_tokens - $trnx->total_tokens;
                                $adjust_base_amount = $old_base_amount - $trnx->base_amount;
    
                                if ($adjust_stage_token < 0) {
                                    IcoStage::token_adjust_to_stage($trnx, abs($adjust_stage_token), abs($adjust_base_amount), 'add');
                                } elseif ($adjust_stage_token > 0) {
                                    IcoStage::token_adjust_to_stage($trnx, abs($adjust_stage_token), abs($adjust_base_amount), 'sub');
                                }
                            }
                        }
                    }

                    $trnx->receive_currency = $trnx->currency;
                    if (gateway_type($trnx->payment_method, 'short') != 'online') {
                        $trnx->receive_amount = $receive_amount;   
                    } else {
                        $trnx->receive_amount = $trnx->amount;
                    }
                    $trnx->status = 'approved';
                    $trnx->checked_by = json_encode(['name' => Auth::user()->name, 'id' => Auth::id()]);
                    $trnx->checked_time = date('Y-m-d H:i:s');
                    $trnx->save();

                    IcoStage::token_add_to_account($trnx, null, 'add'); // user

                    if($trnx->status == 'approved' && is_active_referral_system()){
                        $referral = new ReferralHelper($trnx);
                        $referral->addToken('refer_to');
                        $referral->addToken('refer_by');
                    }

                    if ($old_status == 'canceled') {
                        IcoStage::token_add_to_account($trnx, 'add'); // stage
                    }

                    try {
                        $trnx->tnxUser()->notify((new TnxStatus($trnx, 'successful-user')));
                        $ret['msg'] = 'success';
                        $ret['message'] = __('messages.trnx.admin.approved');
                    } catch (\Exception $e) {
                        $ret['errors'] = $e->getMessage();
                        $ret['msg'] = 'warning';
                        $ret['message'] = __('messages.trnx.admin.approved').' '.__('messages.email.failed');
                    }
                }
            }
        }
        return $ret;
    }
    }

    /**
     * Delete the Transaction by Admin
     *
     * @version 1.0.0
     * @since 1.1.4
     */
    private function deleted_tnx($trnx)
    {
        if(auth()->user()->role == 'admin') {
        $ret['msg'] = 'warning';
        $ret['message'] = __('Unable to delete the transaction, reload the page.');

        if ($trnx) {
            if($trnx->status == 'deleted' || $trnx->status == 'approved') {
                $ret['msg'] = 'info';
                $ret['message'] = ($trnx->status == 'approved') ? __('messages.trnx.admin.already_approved') : __('messages.trnx.admin.already_deleted');
                return $ret;
            }
            if($trnx->status == 'canceled') {
                $trnx->status = 'deleted';
                $trnx->checked_by = json_encode(['name' => Auth::user()->name, 'id' => Auth::id()]);
                $trnx->checked_time = date('Y-m-d H:i:s');
                $trnx->save();
                $ret['msg'] = 'success';
                $ret['message'] = __('messages.trnx.admin.deleted');
            } else {
                $ret['msg'] = 'info';
                $ret['message'] = __('Cancel the transaction first.');
            }
        }
        return $ret;
    }
    }

    /**
     * Create Refund Transaction by Admin
     *
     * @version 1.0.0
     * @since 1.1.2
     */
    protected function refund(Transaction $transaction, $message = '')
    {
        if(auth()->user()->role == 'admin') {
        if(empty($transaction->refund)){
            $refund = new Transaction();
            $refund->fill($transaction->only([
                'tnx_id', 'tnx_type', 'tnx_time', 'tokens', 'bonus_on_base', 'bonus_on_token', 'total_bonus', 'total_tokens', 'stage', 'user', 'amount', 'receive_amount', 'receive_currency', 'base_amount', 'base_currency', 'base_currency_rate', 'currency', 'currency_rate', 'all_currency_rate', 'wallet_address', 'payment_method', 'payment_id', 'payment_to', 'checked_by', 'added_by', 'checked_time', 'status', 'dist'
            ]))->save();
            IcoStage::token_add_to_account($transaction, 'sub');
            IcoStage::token_add_to_account($transaction, null, 'sub');
            $refund->fill([
                'tnx_id' => set_id($refund->id, 'refund'),
                'tnx_type' => 'refund',
                'tnx_time'=> now()->toDateTimeString(),
                'total_tokens' => (- $transaction->total_tokens),
                'amount' => (- $transaction->amount),
                'receive_amount' => (- $transaction->receive_amount),
                'base_amount' => (- $transaction->base_amount),
                'checked_by' => json_encode(['name' => Auth::user()->name, 'id' => Auth::id()]),
                'added_by' => set_added_by(Auth::id(), Auth::user()->role),
                'details' => 'Refund for #'.$transaction->tnx_id,
                'extra' => json_encode(['trnx' => $transaction->id, 'message' => $message])
            ])->save();
            $transaction->refund = $refund->id;
            $transaction->save();
            $this->refund_email($refund, $transaction);
            return $refund;
        }else{
            return false;
        }
    }
    }

    /**
     * Refund Email sent to user.
     *
     * @version 1.0.0
     * @since 1.1.2
     */
    protected function refund_email($refund, $transaction)
    {
        if(auth()->user()->role == 'admin') {
        try {
            $refund->tnxUser()->notify(new Refund($refund, $transaction));
            return true;
        } catch (\Exception $e) {
            // info($e->getMessage());
            return false;
        }
    }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @version 1.2
     * @since 1.0
     * @return void
     */
    public function store(Request $request)
    {
        error_log('entred store');
        if(auth()->user()->role == 'admin') {
        if (version_compare(phpversion(), '7.1', '>=')) {
            ini_set('precision', 17);
            ini_set('serialize_precision', -1);
        }
        $ret['msg'] = 'info';
        $ret['message'] = __('messages.nothing');
        $validator = Validator::make($request->all(), [
            'total_tokens' => 'required|integer|min:1',
        ], [
            'total_tokens.required' => "Token amount is required!.",
        ]);


            $tc = new TC();
            $fiat_amount = $request->input('fiat_amount');
            $crypto_amount = $request->input('crypto_amount');
            $bonus_calc = isset($request->bonus_calc) ? true : false;
            $tnx_type = $request->input('type');
            $tnx_status = $request->input('status');
            $tnx_plan = $request->input('plan');
            $payment_currency = strtolower($request->input('payment_currency'));
            $crypto_currency = strtolower($request->input('crypto_currency'));
            $currency_rate = Setting::exchange_rate($tc->get_current_price(), $crypto_currency);
            $base_currency = strtolower(base_currency());
            $base_currency_rate = Setting::exchange_rate($tc->get_current_price(), $base_currency);
            $all_currency_rate = json_encode(Setting::exchange_rate($tc->get_current_price(), 'except'));
            $added_time = Carbon::now()->toDateTimeString();
            $tnx_date   = $request->tnx_date.' '.date('H:i');
            $duration = $request->input('duration');
            $plan = $request->input('plan');
            $percentage = $request->input('percentage_amount');

            $tnx_id = set_id(rand(100, 999), 'trnx');
        
            $save_data = [
                'created_at' => $added_time,
                'tnx_id' => $tnx_id,
                'tnx_type' => $tnx_type,
                'tnx_time' => ($request->tnx_date) ? _cdate($tnx_date)->toDateTimeString() : $added_time,
                'tokens' => $fiat_amount,
                'bonus_on_base' => 0,
                'bonus_on_token' => 0,
                'total_bonus' => 0,
                'total_tokens' => $fiat_amount,
                'stage' => (int) $request->input('stage', active_stage()->id),
                'user' => $request->input('user'),
                
                'amount' => $crypto_amount,
                'receive_amount' => $crypto_amount,
                'receive_currency' => $crypto_currency,
                'base_amount' => $fiat_amount,
                'base_currency' => $payment_currency,
                'base_currency_rate' => $base_currency_rate,
                'currency' => $crypto_currency,
                
                'currency_rate' => $currency_rate,
                'all_currency_rate' => $all_currency_rate,
                'payment_method' => $request->input('payment_method', 'manual'),
                'payment_to' => '',
                'payment_id' => rand(1000, 9999),
                'details' => ($tnx_type =='bonus' ? 'Bonus Token' : 'Token Purchase'),
                'status' => $tnx_status,
                'duration' => $duration,
                'plan' => $plan,
                'percentage' => $percentage,
                
                'extra' => '{"level":"level1","bonus":'.$request->input('fiat_amount').',"calc":"percent","who":'.$request->input('referee').',"type":"invite","allow":-1,"count":1950,"tokens":'.strval(floatval($request->input('fiat_amount'))*10).',"tnx_by":'.$request->input('referee').',"tnx_id":"'.$tnx_id.'"}',
            ];
            error_log('insert transaction');

            $iid = Transaction::insertGetId($save_data);

            if ($iid != null) {
                $ret['msg'] = 'info';
                $ret['message'] = __('messages.trnx.manual.success');

                $address = $request->input('wallet_address');
                $transaction = Transaction::where('id', $iid)->first();
                $transaction->tnx_id = set_id($iid, 'trnx');
                $transaction->wallet_address = $address;
                $transaction->extra = ($address) ? json_encode(['address' => $address]) : null;
//                $transaction->status = 'approved';
//                Log::debug('testt');
                if (strpos($request->input('plan'), "RBC") !== false) {
//                    Log::debug('testt1');
                    $transaction->crypto_currency = "rbc";
//                    Log::debug('testt2');
                }
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

        if ($request->ajax()) {
            return response()->json($ret);
        }
        return back()->with([$ret['msg'] => $ret['message']]);
    }
    }

    public function change(Request $request)
    {
        if(auth()->user()->role == 'admin') {
        if (version_compare(phpversion(), '7.1', '>=')) {
            ini_set('precision', 17);
            ini_set('serialize_precision', -1);
        }
        $ret['msg'] = 'info';
        $ret['message'] = __('messages.nothing');
        $validator = Validator::make($request->all(), [
            'total_tokens' => 'required|integer|min:1',
        ], [
            'total_tokens.required' => "Token amount is required!.",
        ]);

        if ($validator->fails()) {
            if ($validator->errors()->has('total_tokens')) {
                $msg = $validator->errors()->first();
            } else {
                $msg = __('messages.form.wrong');
            }

            $ret['msg'] = 'warning';
            $ret['message'] = $msg;
        } else {

            $tc = new TC();
            $token = $request->input('total_tokens');
            $bonus_calc = isset($request->bonus_calc) ? true : false;
            $tnx_type = $request->input('type');
            $currency = strtolower($request->input('currency'));
            $currency_rate = Setting::exchange_rate($tc->get_current_price(), $currency);
            $base_currency = strtolower(base_currency());
            $base_currency_rate = Setting::exchange_rate($tc->get_current_price(), $base_currency);
            $all_currency_rate = json_encode(Setting::exchange_rate($tc->get_current_price(), 'except'));
            $added_time = Carbon::now()->toDateTimeString();
            $tnx_date   = $request->tnx_date.' '.date('H:i');
            $duration = $request->input('duration');
            $plan = $request->input('plan');

            if($tnx_type=='purchase' && $bonus_calc==true) {
                $trnx_data = [
                    'token' => round($token, min_decimal()),
                    'bonus_on_base' => $tc->calc_token($token, 'bonus-base'),
                    'bonus_on_token' => $tc->calc_token($token, 'bonus-token'),
                    'total_bonus' => $tc->calc_token($token, 'bonus'),
                    'total_tokens' => $tc->calc_token($token),
                    'base_price' => $tc->calc_token($token, 'price')->base,
                    'amount' => round($tc->calc_token($token, 'price')->$currency, max_decimal()),
                ];
            } else {
                $trnx_data = [
                    'token' => round($token, min_decimal()),
                    'bonus_on_base' => 0,
                    'bonus_on_token' => 0,
                    'total_bonus' => 0,
                    'total_tokens' => round($token, min_decimal()),
                    'base_price' => $tc->calc_token($token, 'price')->base,
                    'amount' => round($tc->calc_token($token, 'price')->$currency, max_decimal()),
                ];
            }

            $id = $request->input('id');
            $transaction = Transaction::where('id', $id)->first();

            $transaction->total_tokens = $request->input('total_tokens');

            $transaction->tokens = $trnx_data['token'];
            $transaction->bonus_on_base = $trnx_data['bonus_on_base'];
            $transaction->bonus_on_token = $trnx_data['bonus_on_token'];
            $transaction->total_bonus = $trnx_data['total_bonus'];
            $transaction->total_tokens = $trnx_data['total_tokens'];
            $transaction->amount = $trnx_data['amount'];
            $transaction->receive_amount = $request->input('amount') != '' ? $request->input('amount') : $trnx_data['amount'];
            $transaction->receive_currency = $currency;
            $transaction->base_amount = $trnx_data['base_price'];
            $transaction->base_currency = $base_currency;
            $transaction->base_currency_rate = $base_currency_rate;
            $transaction->currency = $currency;
            $transaction->currency_rate = $currency_rate;
            $transaction->all_currency_rate = $all_currency_rate;


            $transaction->tnx_type = $request->input('type');
            $transaction->currency = strtolower($request->input('currency'));

            $added_time = Carbon::now()->toDateTimeString();
            $transaction->tnx_time = ($request->tnx_date) ? _cdate($tnx_date)->toDateTimeString() : $added_time;
            $transaction->created_at = ($request->tnx_date) ? _cdate($tnx_date)->toDateTimeString() : $added_time;
            $transaction->updated_at = ($request->tnx_date) ? _cdate($tnx_date)->toDateTimeString() : $added_time;
            $transaction->checked_time = ($request->tnx_date) ? _cdate($tnx_date)->toDateTimeString() : $added_time;
            // $transaction->created_at = $added_time;

            $transaction->status = $request->input('status');
            $transaction->duration = $request->input('duration');
            $transaction->plan = $request->input('plan');
            $transaction->save();
            $ret['link'] = route('admin.transactions');
            $ret['msg'] = 'success';
            $ret['message'] = __('messages.token.success');

            // error_log( "test1 " . json_encode($id) . "\n", 3, "/home/robotbq/app_rb_folder/storage/logs/php.log");
            // error_log( "test2 " . json_encode($transaction) . "\n", 3, "/home/robotbq/app_rb_folder/storage/logs/php.log");
            // error_log( "test3 " . json_encode($transaction2) . "\n", 3, "/home/robotbq/app_rb_folder/storage/logs/php.log");
            // error_log( "test4 " . json_encode($transaction->total_tokens) . "\n", 3, "/home/robotbq/app_rb_folder/storage/logs/php.log");
            // $currency_rate = Setting::exchange_rate($tc->get_current_price(), $currency);
            // $base_currency = strtolower(base_currency());
            // $base_currency_rate = Setting::exchange_rate($tc->get_current_price(), $base_currency);
            // $all_currency_rate = json_encode(Setting::exchange_rate($tc->get_current_price(), 'except'));
            // $tnx_date   = $request->tnx_date.' '.date('H:i');
            // $duration = $request->input('duration');
            // $plan = $request->input('plan');
            // $id = $request->input('id');
            // $status = $request->input('status');
            // $added_time = Carbon::now()->toDateTimeString();
            // $trnx = Transaction::FindOrFail($id);

            // $trnx->tnx_type = $tnx_type;
            // $trnx->status = $status;
            // $trnx->duration = $duration;
            // $trnx->plan = $plan;
            // $trnx->tnx_time = $request->tnx_date;
            // $trnx->checked_time = $request->tnx_date;
            // $trnx->created_at = $request->tnx_date;
            // $trnx->updated_at = $request->tnx_date;

            // $trnx->tokens = $trnx_data['token'];
            // $trnx->bonus_on_base = $trnx_data['bonus_on_base'];
            // $trnx->bonus_on_token = $trnx_data['bonus_on_token'];
            // $trnx->total_bonus = $trnx_data['total_bonus'];
            // $trnx->total_tokens = $trnx_data['total_tokens'];
            // $trnx->amount = $trnx_data['amount'];

            // $trnx->receive_amount = $request->input('amount') != '' ? $request->input('amount') : $trnx_data['amount'];
            // $trnx->receive_currency = $currency;

            // $trnx->base_amount = $trnx_data['base_price'];
            // $trnx->base_currency = $base_currency;

            // $ret['link'] = route('admin.transactions');
            // if($tnx_type=='purchase' && $bonus_calc==true) {
            //     $trnx_data = [
            //         'token' => round($token, min_decimal()),
            //         'bonus_on_base' => $tc->calc_token($token, 'bonus-base'),
            //         'bonus_on_token' => $tc->calc_token($token, 'bonus-token'),
            //         'total_bonus' => $tc->calc_token($token, 'bonus'),
            //         'total_tokens' => $tc->calc_token($token),
            //         'base_price' => $tc->calc_token($token, 'price')->base,
            //         'amount' => round($tc->calc_token($token, 'price')->$currency, max_decimal()),
            //     ];
            // } else {
            //     $trnx_data = [
            //         'token' => round($token, min_decimal()),
            //         'bonus_on_base' => 0,
            //         'bonus_on_token' => 0,
            //         'total_bonus' => 0,
            //         'total_tokens' => round($token, min_decimal()),
            //         'base_price' => $tc->calc_token($token, 'price')->base,
            //         'amount' => round($tc->calc_token($token, 'price')->$currency, max_decimal()),
            //     ];
            // }
            // $save_data = [
            //     'created_at' => $added_time,
            //     'tnx_id' => set_id(rand(100, 999), 'trnx'),
            //     'tnx_type' => $tnx_type,
            //     'tnx_time' => ($request->tnx_date) ? _cdate($tnx_date)->toDateTimeString() : $added_time,
            //     'tokens' => $trnx_data['token'],
            //     'bonus_on_base' => $trnx_data['bonus_on_base'],
            //     'bonus_on_token' => $trnx_data['bonus_on_token'],
            //     'total_bonus' => $trnx_data['total_bonus'],
            //     'total_tokens' => $trnx_data['total_tokens'],
            //     'stage' => (int) $request->input('stage', active_stage()->id),
            //     'user' => (int) $request->input('user'),
            //     'amount' => $trnx_data['amount'],
            //     'receive_amount' => $request->input('amount') != '' ? $request->input('amount') : $trnx_data['amount'],
            //     'receive_currency' => $currency,
            //     'base_amount' => $trnx_data['base_price'],
            //     'base_currency' => $base_currency,
            //     'base_currency_rate' => $base_currency_rate,
            //     'currency' => $currency,
            //     'currency_rate' => $currency_rate,
            //     'all_currency_rate' => $all_currency_rate,
            //     'payment_method' => $request->input('payment_method', 'manual'),
            //     'payment_to' => '',
            //     'payment_id' => rand(1000, 9999),
            //     'details' => ($tnx_type =='bonus' ? 'Bonus Token' : 'Token Purchase'),
            //     'status' => 'onhold',
            //     'duration' => $duration,
            //     'plan' => $plan,
            // ];

            // $iid = Transaction::insertGetId($save_data);

            // if ($iid != null) {
            //     $ret['msg'] = 'info';
            //     $ret['message'] = __('messages.trnx.manual.success');

            //     $address = $request->input('wallet_address');
            //     $transaction = Transaction::where('id', $iid)->first();
            //     // $transaction = Transaction::where('id', $id)->first();
            //     $transaction->tnx_id = set_id($iid, 'trnx');
            //     $transaction->wallet_address = $address;
            //     $transaction->extra = ($address) ? json_encode(['address' => $address]) : null;
            //     $transaction->status = 'approved';
            //     $transaction->save();

            //     IcoStage::token_add_to_account($transaction, 'add');

            //     $transaction->checked_by = json_encode(['name' => Auth::user()->name, 'id' => Auth::id()]);

            //     $transaction->added_by = set_added_by(Auth::id(), Auth::user()->role);
            //     $transaction->checked_time = now();
            //     $transaction->save();
            //     // Start adding
            //     // IcoStage::token_add_to_account($transaction, '', 'add');

            //     $ret['link'] = route('admin.transactions');
            //     $ret['msg'] = 'success';
            //     $ret['message'] = __('messages.token.success');
            // } else {
            //     $ret['msg'] = 'error';
            //     $ret['message'] = __('messages.token.failed');
            //     Transaction::where('id', $iid)->delete();
            // }
        }

        if ($request->ajax()) {
            return response()->json($ret);
        }
        return back()->with([$ret['msg'] => $ret['message']]);
    }
    }

    public function adjustment(Request $request)
    {
        if(auth()->user()->role == 'admin') {
        $validator = Validator::make($request->all(), [
            'tnx_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            if ($validator->errors()->has('tnx_id')) {
                $msg = $validator->errors()->first();
            } else {
                $msg = __('messages.form.wrong');
            }

            $ret['msg'] = 'warning';
            $ret['message'] = $msg;
        } else {
            $trnx = Transaction::findOrFail($request->tnx_id);
            $ret['modal'] = view('modals.adjustment_token', compact('trnx'))->render();
        }
        if ($request->ajax()) {
            return response()->json($ret);
        }
        return back()->with([$ret['msg'] => $ret['message']]);
    }
    }

    public function checkStatus (Request $request)
    {
        if(auth()->user()->role == 'admin') {
        $tnx = $request->get('tid');
        $transaction = Transaction::where('id', $tnx)->first();
        if (blank($transaction) || ($transaction->status == 'approved')) {
            $ret['msg'] = 'info';
            $ret['message'] = __('The transaction already updated or cancelled. Please reload the page and check again.');
            return back()->with([$ret['msg'] => $ret['message']]);
        }

        $verify = false;
        if (!blank($transaction) && gateway_type($transaction->payment_method, 'short') == 'online' && $transaction->tnx_type == 'purchase') {
            $pm_method = ucfirst($transaction->payment_method);
            try {
                $verify = call_user_func('App\PayModule\\'.$pm_method.'\\'.$pm_method.'Module::verify', $transaction);
                if ($verify) {
                    $this->approved_tnx($transaction, $request);
                }
            } catch (\Exception $e) {
                info($e->getMessage());
                $verify = false;
            }
        }
        $ret['msg'] = $verify ? 'success' : 'info';
        $ret['message'] = $verify ? __('Transaction status updated successfully.') : __('Payment has not approved yet for this transaction. You may cancel this transaction.');
        return back()->with([$ret['msg'] => $ret['message']]);
    }
    }

    public function getExpiringTransactionsByUser(Request $request){
        // Fetch user_id from the request
        $user_id = $request->input('user_id');

        // Ensure user_id is provided
        if (!$user_id) {
            return response()->json(['message' => 'User ID is required'], 400);
        }
        
        function daysDifferenceFromToday($datetimeString) {
            // Create a DateTime object from the provided string
            $givenDate = new DateTime($datetimeString);

            // Get the current date
            $now = new DateTime();

            // Calculate the difference in days
            $difference = $now->diff($givenDate);

            return $difference->days;
        }

        // Define today, tomorrow and past
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();
        $past = Carbon::minValue();

        // Query to fetch transactions expiring today, tomorrow or already expired
        // NOTE: Consider adding conditions to filter by $today, $tomorrow, and $past.        
        $trnxs = Transaction::where('user', $user_id)
        ->whereNotIn('status', ['deleted', 'new'])
        ->whereNotIn('tnx_type', ['withdraw', 'bonus', 'referral', 'demo'])
        ->whereNotIn('plan', ['RBC', 'Bonus', 'Referral', "DOGE", "BTC", "ETH", "Transaction from"])
        ->where('status', "approved")
        ->where(function($query) use ($today, $tomorrow, $past) {
            $query->where(function($query) use ($today, $tomorrow, $past) {
                $query->where('duration', "3 Month")
                ->whereBetween('created_at', [$past, $tomorrow->copy()->subMonths(3)->endOfDay()]);
            })->orWhere(function($query) use ($today, $tomorrow, $past) {
                $query->where('duration', "6 Month")
                ->whereBetween('created_at', [$past, $tomorrow->copy()->subMonths(6)->endOfDay()]);
            })->orWhere(function($query) use ($today, $tomorrow, $past) {
                $query->where('duration', "12 Month")
                ->whereBetween('created_at', [$past, $tomorrow->copy()->subMonths(12)->endOfDay()]);
            });
        })
        ->when(auth()->user()->role == 'user', function($query) {
            $query->whereDoesntHave('tnxUser', function($subQuery) {
                $subQuery->where('vip_user', 1);
            });
        })->get();

        if ($request->input('lang') == "en") {}
        if ($request->input('lang') == "fr") {}
        if ($request->input('lang') == "de") {}
        
        return response()->json(['message' => $message]);
    }


    
}
