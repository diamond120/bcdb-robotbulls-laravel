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


class ReferralsController extends Controller
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
        if(auth()->user()->role == 'admin' || auth()->user()->referral_rights == 1) {

            $role_data  = '';
            $per_page   = gmvl('user_per_page', 10);
            $order_by   = (gmvl('user_order_by', 'id')=='token') ? 'tokenBalance' : gmvl('user_order_by', 'id');
            $ordered    = gmvl('user_ordered', 'DESC');
            $is_page    = (empty($role) ? 'all' : $role);

            $ambassadors = User::where('status', 'active')->whereNotNull('email_verified_at')->where('role', '!=', 'admin')->where('ambassador', '1')->get();
            

            // Now you can use $dateCondition to filter the users

            
            $query = User::whereNotNull('referral')->groupBy('referral');

            if (!empty($dateCondition)) {
                $query->whereBetween('created_at', $dateCondition);
            }

            $refered = $query->get(['referral']);

            $referedUsers = [];

            foreach ($refered as $refer) {
                
                $referredQuery = User::where('referral', $refer->referral);

                if (!empty($dateCondition)) {
                    $referredQuery->whereBetween('created_at', $dateCondition);
                }

                $referredUsersById = $referredQuery->get(['id', 'name', 'created_at', 'tokenBalance', 'equity', 'note', 'email']);

                $tokenBalance = $referredUsersById->sum('tokenBalance');
                $equity = $referredUsersById->sum('equity');

                // Getting the referrer's details
                $referrer = User::find($refer->referral);
                
                if ($request->s) {
                    $searchTerm = $request->s;
                    
                    
                    $referrer = User::where('name', 'like', '%' . $searchTerm . '%')
                                ->orWhere('id', 'like', '%' . $searchTerm . '%')
                                ->orWhere('email', 'like', '%' . $searchTerm . '%')
                                ->find($refer->referral);

    

                }

                if (auth()->user()->role == 'user' || $role == "ambassadors") {
                    if ($referrer->ambassador != 1) {
                        continue;
                    }
                }

                $referedUsers[] = [
                    'id' => $referrer->id,
                    'name' => $referrer->name,
                    'tokenBalance' => $tokenBalance,
                    'equity' => $equity,
                    'users' => $referredUsersById,
                    'count' => $referredUsersById->count(),
                    'note' => $referrer->note,
                    'email' => $referrer->email
                ];
            }

            usort($referedUsers, function ($a, $b) {
                return $b['tokenBalance'] - $a['tokenBalance'];
            });

            return view('admin.referrals', compact('referedUsers', 'role_data', 'is_page', 'ambassadors'));



        } else {
            abort(404);
        }
    }

    // process CSV
    public function getBonusesTransactions(Request $request) {
        $phones = $request->input('phones');
        $user_bonus_id = $request['user_bonus'];
        $user_bonus_transactions = Transaction::where('user', $user_bonus_id)
                            ->where('tnx_type', 'referral')
                            ->get();
        $results = [];
        $totalBonuses = 0.0;
        $processedPhones = []; // Tracking the processed phone numbers

        foreach ($phones as $phone) {
            $cleanedPhone = $this->cleanPhoneNumber($phone);

            if (isset($processedPhones[$cleanedPhone])) { // Check if phone was already processed
                continue; // Skip this phone number
            }

            $processedPhones[$cleanedPhone] = true; // Mark the phone as processed

            $user = User::where('email', $cleanedPhone)->first();

            if (!$user) {
                $results[] = ['phone' => $cleanedPhone, 'user' => "NOT FOUND!!!"];
                continue;
            }

            $referredUser = User::find($user->referral);
            $referredByName = $referredUser ? $referredUser->name : "NULL";

            $transactions = Transaction::where('user', $user->id)
                ->where('status', 'approved')
                ->where('tnx_type', 'purchase')
                ->where('payment_method', '<>', 'reinvestment')
                ->get();

            $transactionsGroupedByDate = $transactions->groupBy(function($date) {
                return Carbon::parse($date->tnx_time)->format('Y-m-d');
            });

            foreach ($transactions as $transaction) {
                $result = [
                    'phone' => $cleanedPhone,
                    'user' => $user->name,
                    'referred_by' => $referredByName,
                    'transaction_number' => $transaction->id,
                    'transaction_date' => $transaction->tnx_time,
                    'amount' => $transaction->tokens,
                    'currency' => $transaction->currency,
                    'referral_bonus_received' => "No"
                ];

                $transactionDate = Carbon::parse($transaction->tnx_time)->format('Y-m-d');
                $currentIndex = $transactionsGroupedByDate->keys()->search($transactionDate);

                $result['transaction_index'] = $currentIndex === false ? -1 : $currentIndex;
                if ($currentIndex > 2) {
                    $result['bonus_amount'] = $transaction->tokens * 2 / 100;
                    $result['bonus_percentage'] = "2%";
                } else {
                    $result['bonus_amount'] = $transaction->tokens * 5 / 100;
                    $result['bonus_percentage'] = "5%";
                }

                // Only loop through $user_bonus_transactions if it's not empty
                if (!$user_bonus_transactions->isEmpty()) {
                    foreach ($user_bonus_transactions as $bonus_user_transaction) {
                        $extraDecoded = json_decode($bonus_user_transaction->extra) ?: (object)[];
                        if (($extraDecoded->who ?? null) == $user->id && ($extraDecoded->tnx_id ?? null) == $transaction->tnx_id) {
                            $result['referral_bonus_received'] = "Yes";
                            $result['bonus_amount'] = 0;
                            $result['bonus_percentage'] = "0%";
                            break;
                        }
                    }
                }
                $totalBonuses += $result['bonus_amount'];
                $results[] = $result;
            }
        }

        return response()->json([
            'message' => 'Data processed successfully.',
            'data' => $results,
            'total_bonuses' => $totalBonuses
        ]);
        }


    // clean phone numbers
    private function cleanPhoneNumber($phone) {
        $cleanedPhone = preg_replace('/[^0-9+]/', '', $phone); // Remove all non-numeric characters except '+'

        if (substr($cleanedPhone, 0, 2) == '00' && (isset($cleanedPhone[2]) && $cleanedPhone[2] !== '0')) {
            $cleanedPhone = '+' . substr($cleanedPhone, 2); 
        }

        return $cleanedPhone;
    }
    
    // add bonuses for csv file
    public function addBonuses(Request $request) {
        $phones = $request->input('phones');
        $user_bonus = User::where('id', $request['user_bonus'])->first();

        $processedPhones = []; // Tracking the processed phone numbers

        foreach ($phones as $phone) {
            $cleanedPhone = $this->cleanPhoneNumber($phone);
            if (isset($processedPhones[$cleanedPhone])) { // Check if phone was already processed
                continue; // Skip this phone number
            }
            $processedPhones[$cleanedPhone] = true; // Mark the phone as processed

            $user = User::where('email', $cleanedPhone)->first();

            if ($user) {
                if ($user->referral == NULL) {
                    $user->referral = $user_bonus->id;
                    $user->referralInfo = '{"user":'.$user_bonus->id.',"name":"'.$user_bonus->name.'","time":[]}';
                    $user->save();
                }

                $transactions = Transaction::where('user', $user->id)
                    ->where('status', 'approved')
                    ->where('tnx_type', 'purchase')
                    ->where('payment_method', '<>', 'reinvestment')
                    ->get();

                foreach ($transactions as $transaction) {
                    $bonus_user_transactions = Transaction::where('user', $user_bonus->id)
                        ->where('tnx_type', 'referral')
                        ->get();

                    $transactionsGroupedByDate = $transactions->groupBy(function($date) {
                        return Carbon::parse($date->tnx_time)->format('Y-m-d');
                    });
                    $currentIndex = $transactionsGroupedByDate->keys()->search(Carbon::parse($transaction->tnx_time)->format('Y-m-d'));

                    if ($currentIndex > 2) {
                        $bonus_amount = $transaction->tokens * 2 / 100;
                    } else {
                        $bonus_amount = $transaction->tokens * 5 / 100;
                    }

                    $bonusExists = false;
                    foreach ($bonus_user_transactions as $bonus_user_transaction) {
                        $extraDecoded = json_decode($bonus_user_transaction->extra);
                        if ($extraDecoded !== null && $extraDecoded->who == $user->id && $extraDecoded->tnx_id == $transaction->tnx_id) {
                            $bonusExists = true;
                            $bonus_amount = 0;
                            break;
                        }
                    }

                    if (!$bonusExists) {
                        $tc = new TC();
                        $base_currency = strtolower(base_currency());
                        $base_currency_rate = Setting::exchange_rate($tc->get_current_price(), $base_currency);
                        $all_currency_rate = json_encode(Setting::exchange_rate($tc->get_current_price(), 'except'));
                        $added_time = Carbon::now()->toDateTimeString();
                        $save_data = [
                            'created_at' => $added_time,
                            'tnx_id' => set_id(rand(100, 999), 'trnx'),
                            'tnx_type' => "referral",
                            'tnx_time' => $added_time,
                            'tokens' => $bonus_amount,
                            'bonus_on_base' => 0,
                            'bonus_on_token' => 0,
                            'total_bonus' => 0,
                            'total_tokens' => $bonus_amount,
                            'stage' => 1,
                            'user' => $user_bonus->id,
                            'amount' => $bonus_amount,
                            'receive_amount' => $bonus_amount,
                            'receive_currency' => $user_bonus->base_currency,
                            'base_amount' => $bonus_amount,
                            'base_currency' => $user_bonus->base_currency,
                            'base_currency_rate' => $base_currency_rate,
                            'currency' => $user_bonus->base_currency,
                            'currency_rate' => "1",
                            'all_currency_rate' => $all_currency_rate,
                            'payment_method' => "referral_bonus_csv",
                            'payment_to' => '',
                            'payment_id' => '',
                            'details' => "Referral Bonus on Token Purchase",
                            'extra' => '{"level":"level1","bonus":'.$bonus_amount.',"calc":"fixed","who":'.$user->id.',"type":"invite","allow":-1,"count":'.$bonus_amount.',"tokens":'.$transaction->tokens.',"tnx_by":'.$user->id.',"tnx_id":"'.$transaction->tnx_id.'"}',
                            'status' => "approved",
                            'plan' => "Bonus",
                        ];

                        $iid = Transaction::insertGetId($save_data);
                        if ($iid != null) {
                            $transaction = Transaction::where('id', $iid)->first();
                            $transaction->save();
                            IcoStage::token_add_to_account($transaction, 'add');
                            $transaction->checked_by = json_encode(['name' => Auth::user()->name, 'id' => Auth::id()]);
                            $transaction->added_by = set_added_by(Auth::id(), Auth::user()->role);
                            $transaction->checked_time = now();
                            $transaction->save();
                            IcoStage::token_add_to_account($transaction, '', 'add');
                        }
                    }
                }
            }
        }
        return response()->json(['message' => 'Bonuses added successfully']);
    }


    
 
}
