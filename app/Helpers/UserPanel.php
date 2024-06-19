<?php
/**
 * UserPanel Helper 
 *
 * This class for manage user panel data etc.
 *
 * @package TokenLite
 * @author Softnio
 * @version 1.1.6
 */
namespace App\Helpers;

use DB;
use Auth;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\User\TokenController;
use Illuminate\Http\Request;

//eth
use Sop\CryptoTypes\Asymmetric\EC\ECPublicKey;
use Sop\CryptoTypes\Asymmetric\EC\ECPrivateKey;
use Sop\CryptoEncoding\PEM;
use kornrunner\Keccak;

/**
 * UserPanel Class
 */
class UserPanel
{

    /**
     * user_info()
     *
     * @version 1.3
     * @since 1.0
     * @return void
     */
    public static function user_info($lang, $data = null, $atttr = '')
    {
        $user = (empty($data)) ? auth()->user() : $data; 
        $atttr_def = array('id' => '', 'class' => '', 'vers' => '');
        $opt_atttr = parse_args($atttr, $atttr_def);
        extract($opt_atttr);
        $g_id = ($id) ? ' id="' . $id . '"' : '';
        $g_cls = ($class) ? css_class($class) : '';

        $return = '<div' . $g_id . ' class="user-dropdown-head' . $g_cls . '">
        <h6 class="user-dropdown-name">' . $user->name . '<span>(' . set_id($user->id) . ')</span></h6>
        <span class="user-dropdown-email">' . $user->email . '</span>
        </div>

        <div class="user-status">
        <h6 class="user-status-title">' . $lang['token_balance'] . '</h6>
        <div class="user-status-balance">' . to_num_token($user->tokenBalance) . ' <small>' . token('symbol') . '</small></div>
        </div>';

        return $return;
    }

    /**
     * user_balance()
     *
     * @version 1.3
     * @since 1.0
     * @return void
     */
    public static function user_balance($lang, $data = null, $atttr = '')
    {
        $user = (empty($data)) ? auth()->user() : $data; 
        $atttr_def = array('id' => '', 'class' => '', 'vers' => '');
        $opt_atttr = parse_args($atttr, $atttr_def);
        extract($opt_atttr);
        $g_id = ($id) ? ' id="' . $id . '"' : '';
        $g_cls = ($class) ? css_class($class) : '';

        $return = '<div' . $g_id . ' class="user-status' . $g_cls . '">
        <h6 class="text-white">'.$user->email.' <small class="text-white-50">('.set_id($user->id).')</small></h6>
        </div>';
         /**
     * <h6 class="user-status-title">' . __('Token Balance') . '</h6>
        <div class="user-status-balance">' . to_num_token($user->tokenBalance) . ' <small>' . token('symbol') . '</small></div>
        </div>';
     */

        return $return;
    }

    
    /**
     * user_balance_card()
     * @version 1.3.1
     * @since 1.0
     * @return void
     */
    
    public static function user_balance_card($lang, $current_price, $data = null, $atttr = '', $trnxs, $user, $balance, $equity) {     
        $exchange_rate_rbc = 1/$current_price;
        
        
        $atttr_def = array('id' => '', 'class' => '', 'vers' => '');
        $opt_atttr = parse_args($atttr, $atttr_def);
        extract($opt_atttr);
        $g_id = ($id) ? ' id="' . $id . '"' : '';
        $g_cls = ($class) ? css_class($class) : '';

        $ver_cls = ($vers == 'side') ? ' token-balance-with-icon' : '';
        $ver_icon = ($vers == 'side') ? '<div class="token-balance-icon"><img src="' . asset('images/token-symbol-light.png') . '" alt=""></div>' : '';

        $base_cur = $user->base_currency;
        $pmc_auto_rate = 'pmc_auto_rate_';
        $pmc_auto_rate .= strval($base_cur);
        
        $pmc_auto_rate_site = 'pmc_auto_rate_';
        $pmc_auto_rate_site .= base_currency();
        
        $balance_crypto = 0;
        $trnxs_index = count($trnxs);
        
        $balance_rbc = 0;
        for($i = 0; $i < $trnxs_index; $i++){
            if($trnxs[$i]->receive_currency == 'rbc'){
                $balance_rbc = $balance_rbc + $trnxs[$i]->amount;
            }
        }
        
        $base_con = isset($data->$base_cur) ? to_num($data->$base_cur, 'auto')  : 0;
        $base_out = '<li class="token-balance-sub"><span class="lead">' . ($equity > 0 ? round($equity * $exchange_rate_rbc,3) : '~') . '</span><span class="sub">' . token('symbol') . '</span></li>';

        $balance_eth = 0;
        for($i = 0; $i < $trnxs_index; $i++){
            if($trnxs[$i]->receive_currency == 'eth'){
                $balance_eth = $balance_eth + $trnxs[$i]->amount;
            }
        }
        
        $eth = 'pmc_auto_rate_';
        $eth .= 'eth';
        
        $cur1_out = $cur2_out = '';
        if(gws('user_in_cur1', 'eth')!='hide') {
            $cur1 = gws('user_in_cur1', 'eth');
            $cur1_con = (gws('pmc_active_'.$cur1) == 1) ? to_num($data->$cur1, 'auto') : 0;
            $cur1_out = ($cur1 != $base_cur) ? '<li class="token-balance-sub"><span class="lead">' . ($equity > 0 ? round($equity * get_setting($eth), 3) : '~') . '</span><span class="sub">' . strtoupper($cur1) . '</span></li>' : '';
        }
        
        
        $balance_btc = 0;
        for($i = 0; $i < $trnxs_index; $i++){
            if($trnxs[$i]->receive_currency == 'btc'){
                $balance_btc = $balance_btc + $trnxs[$i]->amount;
            }
        }
        
        $btc = 'pmc_auto_rate_';
        $btc .= 'btc';

        if(gws('user_in_cur2', 'btc')!='hide') {
            $cur2 = gws('user_in_cur2', 'btc');
            $cur2_con = (gws('pmc_active_'.$cur2) == 1) ? to_num($data->$cur2, 'auto') : 0;
            $cur2_out = ($cur2 != $base_cur) ? '<li class="token-balance-sub"><span class="lead">' . ($equity > 0 ? round($equity * get_setting($btc), 3) : '~') . '</span><span class="sub">' . strtoupper($cur2) . '</span></li>' : '';
        }

        if(get_setting($btc) != 1) {
        $contribute = ($base_out || $cur1_out || $cur2_out) ? '<div class="token-balance token-balance-s2"><h6 class="card-sub-title">' . $lang['your_equity_in'] . '</h6><ul class="token-balance-list">' . $base_out . $cur1_out . $cur2_out . '</ul></div>' : '';
        } else {
            $contribute = "";
        }

        $return = '<div' . $g_id . ' class="token-statistics card card-token' . $g_cls . '">
        <div class="card-innr">
        <div class="token-balance' . $ver_cls . '">' . $ver_icon . '
        <div class="token-balance-text">
        <h6 class="card-sub-title">' . $lang['total_account_equity'] . '</h6>
        <span class="lead"><span id="equity" style="font-size:inherit;transition:all .5s ease-out">' .  number_format(strval(round($equity,2)), 2) . '</span> <span>' . strtoupper($base_cur) . ' </span></span>
        
        <h6 class="card-sub-title">' . $lang['total_account_balance'] . '</h6>
        <span class="lead">' . number_format(strval(round($balance,2)), 2) . ' <span>' . strtoupper($base_cur) . ' </span></span>
        
        </div>';
        
        if(count($trnxs)>0) {
            $return .= '<div class="card-opt float-right d-block d-lg-none" style="margin-left: auto;margin-bottom: auto;">
                <a href="'.route('user.token.balance').'" class="link ucap view_all card-sub-title card-text-light" style="color: white;">' . (count($trnxs) > 0 ? $lang['my_portfolio'] : " ") . '<em class="fas fa-angle-right ml-2"></em></a>
            </div>';
        }
        
        $return .= '</div>' . $contribute . '</div></div>';
        
        return $return;
    }
    
    public static function user_balance_card_rbc($lang, $data = null, $atttr = '', $symbol, $current_price)
    {
        
        $trnxs = Transaction::where('user', Auth::id())
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
        
        
        $current_equity = 0;
        $equity_crypto = 0;
        $balance_crypto = 0;
        $trnxs_index = count($trnxs);
        
        for($i = 0; $i < $trnxs_index; $i++){
            if($trnxs[$i]->receive_currency == 'rbc'){
                $balance_crypto = $balance_crypto + $trnxs[$i]->receive_amount;
            }
        }
                    
        $user = auth()->user();

        $atttr_def = array('id' => '', 'class' => '', 'vers' => '');
        $opt_atttr = parse_args($atttr, $atttr_def);
        extract($opt_atttr);
        $g_id = ($id) ? ' id="' . $id . '"' : '';
        $g_cls = ($class) ? css_class($class) : '';

        $ver_cls = ($vers == 'side') ? ' token-balance-with-icon' : '';
        $ver_icon = ($vers == 'side') ? '<div class="token-balance-icon token-balance-icon-black"><img src="' . asset('images/token-symbol-light.png') . '" alt=""></div>' : '';
        
        $balance_eur = 0;
        for($i = 0; $i < $trnxs_index; $i++){
            if($trnxs[$i]->receive_currency != 'eur'){
                $balance_eur = $balance_eur + $trnxs[$i]->amount;
            }
        }
        
        $base_cur = $user->base_currency;
        $pmc_auto_rate = 'pmc_auto_rate_';
        $pmc_auto_rate .= strval($base_cur);
        $base_con = isset($data->$base_cur) ? to_num($data->$base_cur, 'auto')  : 0;
        $base_out = '<li class="token-balance-sub"><span class="lead lead_black">' . ($balance_crypto > 0 ? round($balance_eur,3) : '~') . '</span><span class="sub">' . strtoupper($base_cur) . '</span></li>';

        $balance_eth = 0;
        for($i = 0; $i < $trnxs_index; $i++){
            if($trnxs[$i]->receive_currency != 'eth'){
                $balance_eth = $balance_eth + $trnxs[$i]->amount;
            }
        }
        
        $cur1_out = $cur2_out = '';
        if(gws('user_in_cur1', 'eth')!='hide') {
            $cur1 = gws('user_in_cur1', 'eth');
            $cur1_con = (gws('pmc_active_'.$cur1) == 1) ? to_num($data->$cur1, 'auto') : 0;
            $cur1_out = ($cur1 != $base_cur) ? '<li class="token-balance-sub"><span class="lead lead_black">' . ($balance_crypto > 0 ? round($balance_eth, 3) : '~') . '</span><span class="sub">' . strtoupper($cur1) . '</span></li>' : '';
        }
        
        $balance_btc = 0;
        for($i = 0; $i < $trnxs_index; $i++){
            if($trnxs[$i]->receive_currency != 'btc'){
                $balance_btc = $balance_btc + $trnxs[$i]->amount;
            }
        }

        if(gws('user_in_cur2', 'btc')!='hide') {
            $cur2 = gws('user_in_cur2', 'btc');
            $cur2_con = (gws('pmc_active_'.$cur2) == 1) ? to_num($data->$cur2, 'auto') : 0;
            $cur2_out = ($cur2 != $base_cur) ? '<li class="token-balance-sub"><span class="lead lead_black">' . ($balance_crypto > 0 ? round($balance_btc, 3) : '~') . '</span><span class="sub">' . strtoupper($cur2) . '</span></li>' : '';
        }

        $contribute = ($base_out || $cur1_out || $cur2_out) ? '<div class="token-balance token-balance-s2"><h6 class="card-sub-title card-sub-title_black">' . $lang['your_balance_in'] . '</h6><ul class="token-balance-list">' . $base_out . $cur1_out . $cur2_out . '</ul></div>' : '';

        $return = '<div' . $g_id . ' class="token-statistics card card-token card-token_black' . $g_cls . '">
        <div class="card-innr">
        <div class="token-balance' . $ver_cls . '">' . $ver_icon . '
        <div class="token-balance-text">
        <h6 class="card-sub-title card-sub-title_black">' . $lang['total_rbc_balance'] . '</h6>
        <span class="lead lead_black">' . to_num_token($balance_crypto) . ' <span>' . token('symbol') . '</span></span>
        
        </div>
        </div>
        </div></div>';

        return $return;
    }
    
    /**
     * user_token_block()
     *
     * @version 1.2
     * @since 1.0
     * @return void
     */
    public static function user_token_block($lang, $data = 'null', $atttr = '')
    {
        $user = (empty($data)) ? auth()->user() : $data; 
        $atttr_def = array('id' => '', 'class' => '', 'vers' => '');
        $opt_atttr = parse_args($atttr, $atttr_def);
        extract($opt_atttr);
        $g_id = ($id) ? ' id="' . $id . '"' : '';
        $g_cls = ($class) ? css_class($class) : '';
        $_CUR = base_currency(true);
        $_SYM = token_symbol();
        $base_currency = base_currency();
        $token_1price = token_calc(1, 'price')->$base_currency;
        $token_1rate = token_rate(1, token('default_in_userpanel', 'eth'));
        $token_ratec = token('default_in_userpanel', 'ETH');

        
        $current_url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $return = ''; 
        $status = ucfirst(active_stage_status());
        
            $user = auth()->user();
        
            if($user->robot == "pending") {
//                $robot_activated_time = date("H:i:s",strtotime($user->robot_last_activated));
//                $now = date("H:i:s",strtotime(now()));
                
                $robot_activated_time = strtotime($user->robot_last_activated);
                $robot_activated_time2 = strtotime('2021-02-27 23:32:46');
                $robot_activated_time3 = strtotime('2021-02-27 22:32:46');
                
                $robot_activated_time_h = date("H",strtotime($user->robot_last_activated));
                $robot_activated_time_i = date("i",strtotime($user->robot_last_activated));
                $robot_activated_time_s = date("s",strtotime($user->robot_last_activated));
                
                $now = strtotime(now());
//                $now2 = new DateTime('2016-11-30 11:55:06');
                $now_h = date("H",strtotime(now()));
                $now_i = date("i",strtotime(now()));
                $now_s = date("s",strtotime(now()));
                
//                $test = new DateTime("now");
                
                
                
                $diffx = ($robot_activated_time2 - $robot_activated_time3);
//                $interval = $robot_activated_time->diff($now);              
//                $interval = $now2 - $robot_activated_time2;              
                
//                $diff = strtotime($robot_activated_time) - strtotime($now);
//                $diff = strtotime(now()) - strtotime($user->robot_last_activated);
//                $diff = $robot_activated_time->diff($now);
                $diff = $now - $robot_activated_time;
                $diff2 = $now - $robot_activated_time;
//                $diff2 = strtotime($user->robot_last_activated) - strtotime(now());
//                $diff3 = $user->robot_last_activated - now();
       
                
        
                if(strtotime($user->robot_last_activated) < strtotime("-15 minutes") && $user->robot_last_activated != NULL) {
                    $test = "true";
                    $user->robot = "active";
                } else {
                    $test = "false";
                }
            }
        
           
           if($user->robot == "active" && $user->gb_stocks == NULL && $user->gb_real_estate == NULL && $user->gb_crypto == NULL && $user->gb_green_bonds == NULL) {
            $user->gb_stocks = "active";
            $user->gb_real_estate = "active";
            $user->gb_crypto = "active";
            $user->gb_green_bonds = "active";
        }
        
            
            $return .= '<div class="card card-full-height"><div class="card-innr">';
            $return .= '<h6 class="card-title card-title-sm">Trading Robot';
            
            if($user->robot == "active") {
                $return .= '<span id="robot_status" class="badge badge-success ucap">Running</span>';
            } else if($user->robot == "pending") {
                $return .= '<span id="robot_status" class="badge badge-warning ucap">Initiating <div class="dot-elastic"></div></span>';
            } else {
                $return .= '<span id="robot_status" class="badge badge-danger ucap">Disabled</span>';
            }
                
            $return .= '</h6>';
            
//            $return .= '<div class="d-flex justify-content-between">';
            
            $return .= '<form action="'. route('user.activate.robot') .'" id="robot_activation_form" method="get" onsubmit="return validateMyForm();"> ';
            
            $return .= '<div class="d-flex justify-content-between">';
        
            $return .= '<div>';
            
        if($user->robot == "active" || $user->robot == "pending") {
            $return .= '<input type="checkbox" checked class="input-switch input-switch-sm robot_slider" id="robot_slider" name="robot_slider" value="1"> ';
        } else {
            $return .= '<input type="checkbox" class="input-switch input-switch-sm robot_slider" id="robot_slider" name="robot_slider" value="0"> ';
        }
            
        if($user->robot == "active" || $user->robot == "pending") {
            $return .= '<label for="robot_slider">Disable</label> ';
        } else {
            $return .= '<label for="robot_slider">Enable</label> ';
        }
        
            $return .= '</div>'; 
            
            $return .= '<button href="javascript:void(0)" class="robot_submit_btn link link-ucap" type="submit" id="robot_toggle_save_btn">Save Changes</button> ';
        
             $return .= '</div>'; 
        
            $return .= '</form>';
        
//             $return .= '</div>';    
        
            $return .= '<div class="gaps-2x"></div>';
            
//            $return .= '<h6 class="card-title card-title-sm">' . __('Your Account Status') . '</h6><div class="gaps-1-5x"></div>';
            
            
            
        $email_status = $kyc_staus = '';
//        if (isset($user->kyc_info->status) && $user->kyc_info->status != 'approved') {
        if (!isset($user->kyc_info->status)) {
        
        $return .= '<h6 class="card-title card-title-sm your_account_status">' . __('Your Account Status') . '</h6><div class="gaps-1-5x"></div>
                    <a href="'.route('user.investment').'" class="btn btn-md btn-primary">Invest Now</a>';
        
        // if ($user->email_verified_at == null && $user->kyc_info->status != 'approved') {
        //     $email_status = '<li><a href="' . route('verify.resend') . '" class="btn btn-xs btn-auto btn-info">' . __('Resend Email') . '</a></li>';
        // } else {
        //     $email_status = '<li><a href="javascript:void(0)" class="btn btn-xs btn-auto btn-success">' . __('Email Verified') . '</a></li>';
        // }
           
        
           
        if(!is_kyc_hide()) {
            if (isset($user->kyc_info->status) && $user->kyc_info->status == 'approved') {
                $kyc_staus = '<li><a href="javascript:void(0)" class="btn btn-xs btn-auto btn-success">' . __('KYC Approved') . '</a></li>';
            } elseif (isset($user->kyc_info->status) && $user->kyc_info->status == 'pending') {
                $kyc_staus = '<li><a href="' . route('user.kyc') . '" class="btn btn-xs btn-auto btn-warning">' . __('KYC Pending') . '</a></li>';
            } else {
                $kyc_staus = '<li><a href="' . route('user.kyc') . '" class="btn btn-xs btn-auto btn-info"><span>' . __('Submit KYC') . '</span></a></li>';
            }
        }
            
        }
           
           if (isset($user->kyc_info->status) && $user->kyc_info->status == 'approved') {
               
               $return .= '<h6 class="card-title card-title-sm">Grow Your Portfolio 
                <a href="#" class=""><div href="#" class="dot-elastic dot-elastic-gray"></div></a>
               </h6>';
              $return .= '<div class="gaps-1-5x"></div>';
              $return .= '<div class="user-account-status"><ul class="btn-grp">';
               
            //   if($user->gb_stocks == "active") {
                  $return .= '<li><a href="#" class="btn btn-xs btn-auto btn-danger new_investment">New Investment</a></li>';
            //   }
               
            //   if($user->gb_real_estate == "active") {
                  $return .= '<li><a href="#" class="btn btn-xs btn-auto btn-info referral">Referral</a></li>';
            //   }
               
            //   if($user->gb_crypto == "active") {
                //   $return .= '<li><a href="#" class="btn btn-xs btn-auto btn-danger btn-investment">Crypto</a></li>';
            //   }
               
            //   if($user->gb_green_bonds == "active") {
                //   $return .= '<li><a href="#" class="btn btn-xs btn-auto btn-success btn-investment">Green Bonds</a></li>';
            //   }
               
              $return .= '</ul></div>';
               
           }
            
//        $return = ($email_status || $kyc_staus) ? '<div' . $g_id . ' class="user-account-status' . $g_cls . '">' . $heading . '<ul class="btn-grp">' . $email_status . $kyc_staus . '</ul></div>' : '';
        
        $return .= '<div class="user-account-status"><ul class="btn-grp">' . $email_status . $kyc_staus . '</ul></div>';
            
            
//            $return .= '<div id="active_robot_text_container"><h3 class="text-dark" id="active_robot_text">Initiate your AI investment with 10% of your wallet.</span></h3></div>';
//            $return .= '<div class="gaps-0-5x"></div><div class="d-flex align-items-center justify-content-between mb-0">
//            <a id="active_robot_button" href="' . route('user.activate.robot') . '" class="btn btn-md btn-primary activate-robot">'.__('Activate Robot').'</a>
//            </div>';
            $return .= '</div></div>';
//            $return .= '</div>';

//        }

        $user->save();
        
        return $return;
    }

    /**
     * add_wallet_alert()
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public static function add_wallet_alert($lang)
    {
        return '<a href="javascript:void(0)" class="btn btn-primary btn-xl btn-between w-100 mgb-1-5x user-wallet">' . $lang['add_your_wallet_address'] . ' <em class="ti ti-arrow-right"></em></a>
        <div class="gaps-1x mgb-0-5x d-lg-none d-none d-sm-block"></div>';
    }
    
    public static function questionnaire_popup($lang)
    {
        return '<a href="javascript:void(0)" class="btn btn-primary btn-xl btn-between w-100 mgb-1-5x user-wallet questionnaire_title">' . $lang['take_the_investment_questionnaire'] . ' <em class="ti ti-arrow-right"></em></a>
        <div class="gaps-1x mgb-0-5x d-lg-none d-none d-sm-block"></div>';
    }
    
    public static function kyc_popup($lang)
    {
        return '<a href="'.route('user.kyc').'" class="btn btn-primary btn-xl btn-between w-100 mgb-1-5x submit_your_kyc">' . $lang["submit_your_kyc"] . ' <em class="ti ti-arrow-right"></em></a>
        <div class="gaps-1x mgb-0-5x d-lg-none d-none d-sm-block"></div>';
    }
    
    
    
    /**
     * prediction()
     *
     * @version 1.1
     * @since 1.0
     * @return void
     */
    public static function prediction($lang, $data = null, $atttr = '')
    {
        $atttr_def = array('id' => '', 'class' => '', 'vers' => '');
        $opt_atttr = parse_args($atttr, $atttr_def);
        extract($opt_atttr);
        $g_id = ($id) ? ' id="' . $id . '"' : '';
        $g_cls = ($class) ? css_class($class) : '';

        $user = auth()->user();
        
        $return = '<div class="card-head has-aside">
                            <h4 class="card-title card-title-sm account_prediction">'.$lang['account_predictions'].'</h4>
                            <div class="card-opt">
                                <div href="'. url()->current() .'" class="link ucap link-light toggle-tigger toggle-caret" id="months_prediction_button">'.$lang['12_months'].'</div>
                                <div class="toggle-class dropdown-content">
                                    <ul class="dropdown-list">
                                        <li><a href="'. url()->current() .'?months=3" class="six_months">'.$lang['3_months'].'</a></li>
                            			<li><a href="'. url()->current() .'?months=6" class="twelve_months">'.$lang['6_months'].'</a></li>
                            			<li><a href="'. url()->current() .'?months=12" class="eighteen_months">'.$lang['12_months'].'</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="chart-statistics mb-0">
                            <canvas id="prediction"></canvas>
                        </div>';
        
        
        return $return;
    }
     
    
    public static function portfolio_org($lang, $trnxs, $data = null, $atttr = '')
    {
        $atttr_def = array('id' => '', 'class' => '', 'vers' => '');
        $opt_atttr = parse_args($atttr, $atttr_def);
        extract($opt_atttr);
        $g_id = ($id) ? ' id="' . $id . '"' : '';
        $g_cls = ($class) ? css_class($class) : '';

        $user = auth()->user();
        
        $return = '
        <div class="card-head has-aside">
                            <h4 class="card-title card-title-sm account_prediction card-sub-title-s2">'.$lang['my_portfolio'].'</h4>
                            
                            <div class="card-opt" style="margin-left: auto;margin-bottom: auto;">
                                <a href="'.route('user.token.balance').'" class="link ucap view_all">' . (count($trnxs) > 0 ? $lang['my_portfolio'] : " ") . '<em class="fas fa-angle-right ml-2"></em></a>
                            </div>
                            
                            
                        </div>
                        <div class="chart-statistics mb-0">
                            <canvas id="portfolio_org"></canvas>
                        </div>';
        
        
        return $return;
    }
    
    
    public static function portfolio($lang, $data = null, $atttr = '')
    {
        $atttr_def = array('id' => '', 'class' => '', 'vers' => '');
        $opt_atttr = parse_args($atttr, $atttr_def);
        extract($opt_atttr);
        $g_id = ($id) ? ' id="' . $id . '"' : '';
        $g_cls = ($class) ? css_class($class) : '';

        $user = auth()->user();
        
        $return = '
        <style>#chart-legend ul {
    list-style-type: none;
}

#chart-legend ul li {
    display: inline-block;
    margin-right: 10px;
    cursor: pointer;
}

#chart-legend ul li span {
    display: inline-block;
    width: 12px;
    height: 12px;
    margin-right: 5px;
    vertical-align: middle;
}</style>
        <div class="card-head has-aside">
                            <h4 class="card-title card-title-sm account_prediction card-sub-title-s2">'.$lang['my_portfolio'].'</h4>
                            
                        </div>
                        <div class="chart-statistics mb-0">
                            <canvas id="portfolio"></canvas>
                        </div>
                        <div id="chart-legend"></div>';
        
        
        return $return;
    }

    /**
     * user_account_wallet()
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public static function user_account_wallet($lang, $data = null, $atttr = '')
    {
        $atttr_def = array('id' => '', 'class' => '', 'vers' => '');
        $opt_atttr = parse_args($atttr, $atttr_def);
        extract($opt_atttr);
        $g_id = ($id) ? ' id="' . $id . '"' : '';
        $g_cls = ($class) ? css_class($class) : '';

        $user = auth()->user();

    }

    /**
     * user_kyc_info()
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public static function user_kyc_info($lang, $data = null, $atttr = '')
    {
        $atttr_def = array('id' => '', 'class' => '', 'vers' => '');
        $opt_atttr = parse_args($atttr, $atttr_def);
        extract($opt_atttr);
        $g_id = ($id) ? ' id="' . $id . '"' : '';
        $g_cls = ($class) ? css_class($class) : '';

        $user = auth()->user();
        $title_cls = ' card-title-sm';

        $heading = '<h6 class="kyc_verifiation card-title' . $title_cls . '">' . __($lang['identity_verification_kyc']) . '</h6>';
        $ukyc = $heading . '<p class="kyc_modal_under_text1">' . __($lang['to_comply_with_regulation']) . '</p>';
        if (!isset($user->kyc_info->status)) {
            $ukyc .= '<p class="lead text-light pdb-0-5x kyc_small_title1">' . __($lang['you_have_not_submitted_your_documents']) . '</p><a href="' . route('user.kyc.application') . '" class="btn btn-sm m-2 btn-icon btn-primary">' . __($lang['click_to_proceed']) . '</a>';
        }
        if (isset($user->kyc_info->status) && $user->kyc_info->status == 'pending') {
            $ukyc .= '<p class="lead text-info pdb-0-5x kyc_modal_under_text2_pending">' . __($lang['we_have_received_your_documents']) . '</p><p class="small">' . __($lang['we_will_review_your_information_and']) . '</p>';
        }
        if (isset($user->kyc_info->status) && ($user->kyc_info->status == 'rejected' || $user->kyc_info->status == 'missing')) {
            $ukyc .= '<p class="lead text-danger pdb-0-5x kyc_modal_under_text2_rejected">' . __($lang['kyc_application_rejected']) . '</p><p>' . __($lang['we_are_having_difficulties_verifying']) . '</p><a href="' . route('user.kyc.application') . '?state=resubmit" class="btn btn-sm m-2 btn-icon btn-primary">' . __($lang['resubmit']) . '</a><a href="' . route('user.kyc.application.view') . '" class="btn btn-sm m-2 btn-icon btn-secondary">' . __($lang['view_kyc']) . '</a>';
        }
        if (isset($user->kyc_info->status) && $user->kyc_info->status == 'approved') {
            $ukyc .= '<p class="lead text-success pdb-0-5x kyc_modal_under_text2_approved"><strong>' . __($lang['kyc_has_been_verified']) . '</strong></p><p>' . __($lang['one_of_our_team_verified_your_identity']) . '</p><a href="' . route('user.token') . '" class="btn btn-sm m-2 btn-icon btn-primary new_investment">' . __($lang['new_investment']) . '</a><a href="' . route('user.kyc.application.view') . '" class="btn btn-sm m-2 btn-icon btn-success">' . __($lang['view_kyc']) . '</a>';
        }
        if (token('before_kyc') == '1') {
            $ukyc .= '<h6 class="kyc-alert text-danger kyc_setion1_under_title">* ' . __($lang['kyc_verification_required']) . '</h6>';
        }

        $return = ($ukyc) ? '<div' . $g_id . ' class="kyc-info card' . $g_cls . '"><div class="card-innr">' . $ukyc . '</div></div>' : '';
        return $return;
    }
    
    
    public static function whitelist_wallet($lang, $user)
    {

        
        $return = '
            <div class="content-area card">
                <div class="card-innr">
                    <div class="card-head">
                        <h4 class="card-title two_fator_verifiation">' . $lang['whitelisting'] . '</h4>
                    </div>
                    <p class="two_fator_verifiation_under_text">' . $lang['whitelisting_text'] . '</p>
                    <div class="d-sm-flex justify-content-between align-items-center pdt-1-5x">';
        
        
                    if (isset($user->kyc_info) && isset($user->kyc_info->status)) {
                        if($user->whitelisting_comptete == 0 && $user->kyc_info->status == 'approved') {
                            $return .= '<button href="javascript:void(0)" class="btn btn-primary btn-between request-whitelisting" id="request-whitelisting" onclick="show_whitelisting()">' . $lang['start_whitelisting'] . '</button>';
                        }
                    } else {
                        $return .= '<a href="'.route('user.kyc').'" class="btn btn-primary btn-between request-whitelisting" id="request-whitelisting">' . $lang['submit_your_kyc'] . '</a>';
                    }

                    if(isset($user->whitelisting_comptete) && $user->whitelisting_comptete == 1) {
                        $return .= '<button href="javascript:void(0)" class="btn btn-success disabled btn-between request-whitelisting" id="request-whitelisting" disabled>' . $lang['whitelisting_complete'] . '</button>';
                    }
                   
                        
        $return .= '                
                    </div>
                </div>
            </div>
        ';
                    
        return $return;
    }
    
    
    
    public static function wallet_address($lang, $user)
    {
        
        $wallet_address = DB::table('wallets')
            ->where('user_id', $user->id)
            ->where('currency', "eth")
            ->first();
        if (!$wallet_address) {
            // create user wallet
            $request = new Request([
                'currency' => "eth"
            ]);
            
            $user = Auth::user();
            $allowedCurrencies = ['eth', 'usdt', 'usdc'];
            $currency = "eth";
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
                ['id' => intval(DB::table('wallets')->count()+1), 'custom_wallet_name' => $user->name . "'s " . strtoupper($currency) . ' Wallet', 'currency' => $currency, 'amount' => '0', 'user_id' => $user->id, 'publickey' => $publicKey, 'wallet_address' => $address, 'created_at' => Carbon::now()->toDateTimeString()],
            ); 
            
        }
        $wallet_address = DB::table('wallets')
            ->where('user_id', $user->id)
            ->where('currency', "eth")
            ->first();
        
        if ($wallet_address) {
            $return = '
                <div class="content-area card">
                    <div class="card-innr">

                        <div class="referral-form">
                            <h4 class="card-title card-title-sm">' . $lang['wallet_address'] . '</h4>
                            <div class="copy-wrap mgb-1-5x mgt-1-5x">
                                <span class="copy-feedback"></span>
                                <em class="copy-icon fas fa-link"></em>
                                <input type="text" class="copy-address" value="' . strval(Crypt::decryptString($wallet_address->wallet_address)) . '" disabled>
                                <button class="copy-trigger copy-clipboard" data-clipboard-text="' . strval(Crypt::decryptString($wallet_address->wallet_address)) . '"><em class="ti ti-files"></em></button>
                            </div>
                            <p class="text-light mgmt-1x"><em><small>' . $lang['wallet_address_text_ethereum'] . '</small></em></p>
                            <button href="javascript:void(0)" class="btn btn-primary" onclick="get_key()">' . $lang['get_private_key'] . '</button>
                        </div>

                    </div>
                </div>
            ';
        } else {
            $return = '';
        }
                    
        return $return;
    }

    /**
     * user_logout_link()
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public static function user_logout_link($lang, $data = null, $atttr = '')
    {
        $atttr_def = array('id' => '', 'class' => '', 'vers' => '');
        $opt_atttr = parse_args($atttr, $atttr_def);
        extract($opt_atttr);
        $g_id = ($id) ? ' id="' . $id . '"' : '';
        $g_cls = ($class) ? css_class($class) : '';

        $return = '<ul' . $g_id . ' class="user-links bg-light' . $g_cls . '">
        <li><a href="' . route('log-out') . '" onclick="event.preventDefault();document.getElementById(\'logout-form\').submit();"><i class="ti ti-power-off"></i><span class="logout">' . $lang['logout'] . '</span></a></li>
        </ul>
        <form id="logout-form" action="' . route('logout') . '" method="POST" style="display: none;"> <input type="hidden" name="_token" value="' . csrf_token() . '"> </form>';

        return $return;
    }

    /**
     * user_menu_links()
     *
     * @version 1.2
     * @since 1.0
     * @return void
     */
    public static function user_menu_links($lang, $data = null, $atttr = '')
    {
        $atttr_def = array('id' => '', 'class' => '', 'vers' => '');
        $opt_atttr = parse_args($atttr, $atttr_def);
        extract($opt_atttr);
        $g_id = ($id) ? ' id="' . $id . '"' : '';
        $g_cls = ($class) ? css_class($class) : '';

        // v1.0.3 > v1.1.1
        $referral_link = (get_page('referral', 'status') == 'active' && is_active_referral_system()) ? '<li><a href="' . route('user.referral') . '"><i class="ti ti-infinite"></i>' . $lang['referral'] . '</a></li>' : '';
        // v1.1.2
        $withdraw_link = (nio_module()->has('Withdraw') && has_route('withdraw:user.index')) ? '<li><a href="' . route('withdraw:user.index') . '"><i class="ti ti-wallet"></i>' . $lang['withdraw'] . '</a></li>' : '';
        $return = '<ul' . $g_id . ' class="user-links' . $g_cls . '"><li><a href="' . route('user.account') . '"><i class="ti ti-id-badge"></i> <span class="my_profile"> ' . $lang['my_profile'] . ' </span></a></li>'.$withdraw_link.$referral_link;
        $return .= '<li><a href="' . route('user.account.activity') . '"><i class="ti ti-eye"></i> <span class="activity">' . $lang['activity'] . '</span> </a></li>';
        $return .= '</ul>';

        return $return;
    }

    /**
     * kyc_footer_info()
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public static function kyc_footer_info($lang, $data = null, $atttr = '')
    {
        $atttr_def = array('id' => '', 'class' => '', 'vers' => '');
        $opt_atttr = parse_args($atttr, $atttr_def);
        extract($opt_atttr);
        $g_id = ($id) ? ' id="' . $id . '"' : '';
        $g_cls = ($class) ? css_class($class) : '';

        $email = (get_setting('site_support_email', get_setting('site_email'))) ? ' <a href="mailto:' . get_setting('site_support_email', get_setting('site_email')) . '">' . get_setting('site_support_email', get_setting('site_email')) . '</a>' : '';
        $gaps = '<div class="gaps-3x d-none d-sm-block"></div>';

        $return = ($email) ? '<p class="text-light text-center kyc_small_title1_contact_email_text">' . ( $lang['contact_support_via_email'] ) . ' - '.$email.'</p><div class="gaps-1x"></div>' . $gaps : '';

        return $return;
    }

    /**
     * language_switcher()
     *
     * @version 1.0.1
     * @since 1.0.2
     * @return string
     */

    
    public static function language_switcher_auth($lang)
    {
         $session_lang = (isset($_SESSION['lang']))?$_SESSION['lang']:'en';
         $l = str_replace('_', '-', current_lang());

         $text = '<div class="lang-switch relative"><div href="javascript:void(0)" class="lang-switch-btn toggle-tigger">'.$session_lang.'<em class="ti ti-angle-up"></em></div>';
         $text .= '<div class="toggle-class dropdown-content dropdown-content-up"><ul class="lang-list">';
             $text .= '<li><a style="cursor:pointer" class="setLanguage" name="en" href="?lang=en">EN</a></li>';
             $text .= '<li><a style="cursor:pointer" class="setLanguage" name="fr" href="?lang=fr">FR</a></li>';
             $text .= '<li><a style="cursor:pointer" class="setLanguage" name="de" href="?lang=de">DE</a></li>';
             $text .= '<li><a style="cursor:pointer" class="setLanguage" name="it" href="?lang=it">IT</a></li>';
             $text .= '<li><a style="cursor:pointer" class="setLanguage" name="es" href="?lang=es">ES</a></li>';
             $text .= '<li><a style="cursor:pointer" class="setLanguage" name="lt" href="?lang=lt">LT</a></li>';
             $text .= '<li><a style="cursor:pointer" class="setLanguage" name="no" href="?lang=no">NO</a></li>';
             $text .= '<li><a style="cursor:pointer" class="setLanguage" name="sv" href="?lang=sv">SV</a></li>';
             $text .= '<li><a style="cursor:pointer" class="setLanguage" name="ru" href="?lang=ru">RU</a></li>';
             $text .= '<li><a style="cursor:pointer" class="setLanguage" name="bg" href="?lang=bg">BG</a></li>';
             $text .= '<li><a style="cursor:pointer" class="setLanguage" name="ua" href="?lang=ua">UA</a></li>';
             $text .= '<li><a style="cursor:pointer" class="setLanguage" name="cn" href="?lang=cn">CN</a></li>';
             $text .= '<li><a style="cursor:pointer" class="setLanguage" name="tc" href="?lang=tc">TC</a></li>';
         $text .= '</ul></div></div>';
         
         return $text;
    }
    
    public static function language_switcher($lang)
    {
         $session_lang = (isset($_SESSION['lang']))?$_SESSION['lang']:'en';
         $l = str_replace('_', '-', current_lang());

         $text = '<div class="lang-switch relative"><div href="javascript:void(0)" class="lang-switch-btn toggle-tigger">'.$session_lang.'<em class="ti ti-angle-up"></em></div>';
         $text .= '<div class="toggle-class dropdown-content dropdown-content-up"><ul class="lang-list">';
             $text .= '<li><a style="cursor:pointer" class="setLanguage" name="en" href="?lang=en">EN</a></li>';
             $text .= '<li><a style="cursor:pointer" class="setLanguage" name="fr" href="?lang=fr">FR</a></li>';
             $text .= '<li><a style="cursor:pointer" class="setLanguage" name="de" href="?lang=de">DE</a></li>';
             $text .= '<li><a style="cursor:pointer" class="setLanguage" name="it" href="?lang=it">IT</a></li>';
             $text .= '<li><a style="cursor:pointer" class="setLanguage" name="es" href="?lang=es">ES</a></li>';
             $text .= '<li><a style="cursor:pointer" class="setLanguage" name="lt" href="?lang=lt">LT</a></li>';
             $text .= '<li><a style="cursor:pointer" class="setLanguage" name="no" href="?lang=no">NO</a></li>';
             $text .= '<li><a style="cursor:pointer" class="setLanguage" name="sv" href="?lang=sv">SV</a></li>';
             $text .= '<li><a style="cursor:pointer" class="setLanguage" name="ru" href="?lang=ru">RU</a></li>';
             $text .= '<li><a style="cursor:pointer" class="setLanguage" name="bg" href="?lang=bg">BG</a></li>';
             $text .= '<li><a style="cursor:pointer" class="setLanguage" name="ua" href="?lang=ua">UA</a></li>';
             $text .= '<li><a style="cursor:pointer" class="setLanguage" name="cn" href="?lang=cn">CN</a></li>';
             $text .= '<li><a style="cursor:pointer" class="setLanguage" name="tc" href="?lang=tc">TC</a></li>';
         $text .= '</ul></div></div>';
         
         return $text;


//        $l = str_replace('_', '-', current_lang());
//
//        $text = '<div class="lang-switch relative"><a href="javascript:void(0)" class="lang-switch-btn toggle-tigger">'.strtoupper($l).'<em class="ti ti-angle-up"></em></a>';
//        $text .= '<div class="toggle-class dropdown-content dropdown-content-up"><ul class="lang-list">';
//        foreach (config('icoapp.supported_languages') as $lng) {
//            $text .= '<li><a href="'.route('language').'?lang='.$lng.'">'.get_lang($lng).'</a></li>';
//        }
//        $text .= '</ul></div></div>';
//        return (is_lang_switch()) ? $text : '';
    }

    /**
     * social_links()
     *
     * @version 1.0.2
     * @since 1.0
     * @return void
     */
    public static function social_links($lang, $data = null, $atttr = '')
    {
        $atttr_def = array('id' => '', 'class' => '', 'vers' => '');
        $opt_atttr = parse_args($atttr, $atttr_def);
        extract($opt_atttr);
        $g_id = ($id) ? ' id="' . $id . '"' : '';
        $g_cls = ($class) ? css_class($class) : '';

        $link = json_decode(get_setting('site_social_links'));

        $fb = (isset($link->facebook) && $link->facebook != null) ? '<li><a href="' . $link->facebook . '"><em class="fab fa-facebook-f"></em></a></li>' : '';
        $tw = (isset($link->twitter) && $link->twitter != null) ? '<li><a href="' . $link->twitter . '""><em class="fab fa-twitter"></em></a></li>' : '';
        $in = (isset($link->linkedin) && $link->linkedin != null) ? '<li><a href="' . $link->linkedin . '"><em class="fab fa-linkedin-in"></em></a></li>' : '';
        $gh = (isset($link->github) && $link->github != null) ? '<li><a href="' . $link->github . '"><em class="fab fa-github-alt"></em></a></li>' : '';

        $yt = (isset($link->youtube) && $link->youtube != null) ? '<li><a href="' . $link->youtube . '"><em class="fab fa-youtube"></em></a></li>' : '';
        $md = (isset($link->medium) && $link->medium != null) ? '<li><a href="' . $link->medium . '"><em class="fab fa-medium-m"></em></a></li>' : '';
        $tg = (isset($link->telegram) && $link->telegram != null) ? '<li><a href="' . $link->telegram . '"><em class="fab fa-telegram-plane"></em></a></li>' : '';

        $social_exist = ($fb || $tw || $in || $gh || $yt || $md || $tg) ? true : false;
        $return = ($social_exist) ? '<ul' . $g_id . ' class="socials' . $g_cls . '">' . $fb . $tw . $in . $gh .  $yt . $md . $tg . '</ul>' : '';

        return ($data=='exists') ? $social_exist : $return;
    }

    /**
     * footer_links()
     *
     * @version 1.0.2
     * @since 1.0
     * @return void
     */
    public static function footer_links($lang, $data = null, $atttr = '')
    {
//         $atttr_def = array('id' => '', 'class' => '', 'vers' => '');
//         $opt_atttr = parse_args($atttr, $atttr_def);
//         extract($opt_atttr);
//         $g_id = ($id) ? ' id="' . $id . '"' : '';
//         $g_cls = ($class) ? css_class($class) : '';

// //        $how_to = (get_page('how_buy', 'status') == 'active') ? '<li><a href="' . route('public.pages', get_slug('how_buy')) . '">' . get_page('how_buy', 'menu_title') . '</a></li>' : '';
//         $how_to = "";
//         $cs_page = (get_page('custom_page', 'status') == 'active') ? '<li><a href="' . route('public.pages', get_slug('custom_page')) . '">' . get_page('custom_page', 'menu_title') . '</a></li>' : '';
// //        $faqs = (get_page('faq', 'status') == 'active') ? '<li><a href="' . route('public.pages', get_slug('faq')) . '">' . get_page('faq', 'menu_title') . '</a></li>' : '';
//         $faqs = "";
//         if (!auth()->check() || is_2fa_lock()) {
//             $how_to = $faqs = $cs_page = '';
//         }
//         $privacy = (get_page('privacy', 'status') == 'active') ? '<li><a class="privacy_and_policy" href="' . route('public.pages', get_slug('privacy')) . '">' . get_page('privacy', 'menu_title') . '</a></li>' : '';
//         $terms = (get_page('terms', 'status') == 'active') ? '<li><a class="terms_of_service" href="' . route('public.pages', get_slug('terms')) . '">' . get_page('terms', 'menu_title') . '</a></li>' : '';

//         $is_copyright = ( (isset($data['copyright']) && $data['copyright']==true) || $vers == 'copyright' ) ? true : false;
//         $copyrights = ($is_copyright) ? '<li>'.site_copyrights().'</li>' : '';

//         $is_lang = ( (isset($data['lang']) && $data['lang']==true) && is_lang_switch() ) ? true : false;
//         $lang =  ($is_lang) ? '<li>'.Userpanel::language_switcher().'</li>' : '';

//         $return = ($privacy || $terms) ? '<ul' . $g_id . ' class="footer-links' . $g_cls . '">' . $cs_page . $how_to . $faqs . $privacy . $terms . $copyrights . '</ul>' : '';

//         return (!is_maintenance() ? $return : '');


$atttr_def = array('id' => '', 'class' => '', 'vers' => '');
$opt_atttr = parse_args($atttr, $atttr_def);
extract($opt_atttr);
$g_id = ($id) ? ' id="' . $id . '"' : '';
$g_cls = ($class) ? css_class($class) : '';

$how_to = (get_page('how_buy', 'status') == 'active') ? '<li><a href="' . route('public.pages', get_slug('how_buy')) . '">' . get_page('how_buy', 'menu_title') . '</a></li>' : '';
$cs_page = (get_page('custom_page', 'status') == 'active') ? '<li><a href="' . route('public.pages', get_slug('custom_page')) . '">' . get_page('custom_page', 'menu_title') . '</a></li>' : '';
$faqs = (get_page('faq', 'status') == 'active') ? '<li><a href="' . route('public.pages', get_slug('faq')) . '">' . get_page('faq', 'menu_title') . '</a></li>' : '';
if (!auth()->check() || is_2fa_lock()) {
    $how_to = $faqs = $cs_page = '';
}
$privacy = (get_page('privacy', 'status') == 'active') ? '<li><a href="' . route('public.pages', get_slug('privacy')) . '">' . get_page('privacy', 'menu_title') . '</a></li>' : '';
$terms = (get_page('terms', 'status') == 'active') ? '<li><a href="' . route('public.pages', get_slug('terms')) . '">' . get_page('terms', 'menu_title') . '</a></li>' : '';

$session_lang = (isset($_SESSION['lang']))?$_SESSION['lang']:'en';
         $l = str_replace('_', '-', current_lang());

         $text = '<div class="lang-switch relative"><div href="javascript:void(0)" class="lang-switch-btn toggle-tigger">'.$session_lang.'<em class="ti ti-angle-up"></em></div>';
         $text .= '<div class="toggle-class dropdown-content dropdown-content-up"><ul class="lang-list">';
             $text .= '<li><a style="cursor:pointer" class="setLanguage" name="en" href="?lang=en">EN</a></li>';
             $text .= '<li><a style="cursor:pointer" class="setLanguage" name="fr" href="?lang=fr">FR</a></li>';
             $text .= '<li><a style="cursor:pointer" class="setLanguage" name="de" href="?lang=de">DE</a></li>';
             $text .= '<li><a style="cursor:pointer" class="setLanguage" name="it" href="?lang=it">IT</a></li>';
             $text .= '<li><a style="cursor:pointer" class="setLanguage" name="es" href="?lang=es">ES</a></li>';
             $text .= '<li><a style="cursor:pointer" class="setLanguage" name="lt" href="?lang=lt">LT</a></li>';
             $text .= '<li><a style="cursor:pointer" class="setLanguage" name="no" href="?lang=no">NO</a></li>';
             $text .= '<li><a style="cursor:pointer" class="setLanguage" name="sv" href="?lang=sv">SV</a></li>';
             $text .= '<li><a style="cursor:pointer" class="setLanguage" name="ru" href="?lang=ru">RU</a></li>';
             $text .= '<li><a style="cursor:pointer" class="setLanguage" name="bg" href="?lang=bg">BG</a></li>';
             $text .= '<li><a style="cursor:pointer" class="setLanguage" name="ua" href="?lang=ua">UA</a></li>';
             $text .= '<li><a style="cursor:pointer" class="setLanguage" name="cn" href="?lang=cn">CN</a></li>';
             $text .= '<li><a style="cursor:pointer" class="setLanguage" name="tc" href="?lang=tc">TC</a></li>';
         $text .= '</ul></div></div>';
        
        
$is_copyright = ( (isset($data['copyright']) && $data['copyright']==true) || $vers == 'copyright' ) ? true : false;
$copyrights = ($is_copyright) ? '<li>'.site_copyrights().'</li>' : '';

$is_lang = ( (isset($data['lang']) && $data['lang']==true) && is_lang_switch() ) ? true : false;
$lang = ($is_lang) ? '<li>'.Userpanel::language_switcher().'</li>' : '';

$return = ($privacy || $terms) ? '<ul' . $g_id . ' class="footer-links' . $g_cls . '">' . $cs_page . $how_to . $faqs . $privacy . $terms . $text . $copyrights . $lang . '</ul>' : '';

return (!is_maintenance() ? $return : '');


    }

    /**
     * copyrights()
     *
     * @version 1.0.1
     * @since 1.0.2
     * @return void
     */
    public static function copyrights($lang, $data = null, $atttr = '')
    {
        $atttr_def = array('id' => '', 'class' => '', 'vers' => '');
        $opt_atttr = parse_args($atttr, $atttr_def);
        extract($opt_atttr);
        $g_id = ($id) ? ' id="' . $id . '"' : '';
        $g_cls = ($class) ? css_class($class) : '';

        $copyrights = ($data=='div') ? '<div' . $g_id . ' class="copyright-text' . $g_cls . '">'.site_copyrights().'</div>' : site_copyrights();

        $return = $copyrights;

        return $return;
    }

    /**
     * content_block()
     *
     * @version 1.1
     * @since 1.0
     * @return void
     */
    public static function content_block($lang, $data = null, $atttr = '')
    {
        $atttr_def = array('id' => '', 'class' => '', 'vers' => '');
        $opt_atttr = parse_args($atttr, $atttr_def);
        extract($opt_atttr);
        $g_id = ($id) ? ' id="' . $id . '"' : '';
        $g_cls = ($class) ? css_class($class) : '';

        
        $return = '
        <div class="token-sale-graph card card-full-height">
        <div class="card-innr">
        <div class="card-head has-aside">
                            <h4 class="card-title card-title-sm equity_overview">'.$lang['equity_overview'].'</h4>
                            <div class="card-opt">
                                <div href="'. url()->current() .'" class="link ucap link-light toggle-tigger toggle-caret equity_text fiveteen_days">'.$lang['15days'].'</div>
                                <div class="toggle-class dropdown-content">
                                    <ul class="dropdown-list">
                                        <li><a href="'. url()->current() .'?equity=7" class="seven_days">'.$lang['7days'].'</a></li>
                                        <li><a href="'. url()->current() .'?equity=15" class="fiveteen_days">'.$lang['15days'].'</a></li>
                                        <li><a href="'. url()->current() .'?equity=30" class="thirty_days">'.$lang['30days'].'</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="chart-tokensale chart-tokensale-long">
                            <canvas id="tknSale"></canvas>
                        </div>
                        </div>
                        </div>';

        return $return;
    }

    /**
     * rbc_panel()
     *
     * @version 1.2
     * @since 1.0
     * @return void
     */
    public static function rbc_panel($lang, $data = null, $atttr = '')
    {

        $return = '
        
        <div class="card content-welcome-block card-full-height">
        <div class="card-innr">
        <div class="row guttar-vr-20px">
        <div class="col-sm-5 col-md-4">
        <div class="card-image card-image-sm">
        <img width="240" src="' . asset('assets/images/welcome.png') . '" alt="">
        </div>
        </div>
        <div class="col-sm-7 col-md-8">
        <div class="card-content">
        <h4>'.$lang['rb_coin_title'].'</h4>
        <p>'.$lang['rb_coin_text'].'</p>
        
        <a href="'. route('user.token') .'" target="_blank" class="btn btn-primary" style="min-width: 45px">
        '.$lang['rb_coin_cta'].'</a>
        </div></div></div>
        <div class="d-block d-md-none gaps-0-5x mb-0"></div></div></div>';
             
        return $return;
    }
    
        public static function token_sales_ad($lang, $data = null, $atttr = '')
    {

        $return = '
        
        <div class="card content-welcome-block">
        <div class="card-innr">
        <div class="row guttar-vr-20px">
        <div class="col-12">
        <div class="card-content">
        <h4>'.$lang['rb_coin_title'].'</h4>
        <p>'.$lang['rb_coin_text'].'</p>

        
        <a href="'. route('user.token') .'" target="_blank" class="btn btn-primary" style="min-width: 45px">'.$lang['rb_coin_cta'].'</a>
        </div></div></div>
        <div class="d-block d-md-none gaps-0-5x mb-0"></div></div></div>';
             
        return $return;
    }
    
    public static function token_sales_ad2($lang, $data = null, $atttr = '')
    {

        $return = '
        
        <div class="card content-welcome-block">
        <div class="card-innr">
        <div class="row guttar-vr-20px">
        <div class="col-12">
        <div class="card-content">
        <h4>'.$lang['rb_coin_title'].'</h4>
        <p>'.$lang['rb_coin_text'].'</p>

        <b>'.$lang['rb_coin_text2'].'</b>

        <br><br>

        <button type="button" data-toggle="modal" data-target="#google-auth" class="white_paper btn btn-primary" style="min-width: 45px; margin-right: 10px">'.$lang['download_whitepaper'].'</button>
        </div></div></div>
        <div class="d-block d-md-none gaps-0-5x mb-0"></div></div></div>';
             
        return $return;
    }
    
    
    
    public static function transactions($lang, $data = null, $atttr = '')
    {
        
        $trnxs = Transaction::where('user', Auth::id())
                    ->where('status', '!=', 'deleted')
                    ->where('status', '!=', 'new')
                    ->whereNotIn('tnx_type', ['withdraw'])
                    ->whereNotIn('tnx_type', ['demo'])
                    ->orderBy('created_at', 'DESC')->get();
        
        $user = auth()->user();
                    
        if(count($trnxs) > 0 ) {
            $user->robot = "active";
        }
        
        $user->save();
        
        
        $atttr_def = array('id' => '', 'class' => '', 'vers' => '');
        $opt_atttr = parse_args($atttr, $atttr_def);
        extract($opt_atttr);
        $g_id = ($id) ? ' id="' . $id . '"' : '';
        $g_cls = ($class) ? css_class($class) : '';

        $sales_raised = (token('sales_raised')) ? token('sales_raised') : 'token';
        $sales_total = (token('sales_total')) ? token('sales_total') : 'token';
        $sales_caps = (token('sales_cap')) ? token('sales_cap') : 'token';
        $title = $progress = $progress_bar = $sales_end_in = $sales_start_in = '';
        
        $return = "<div class='token-transaction card card-full-height'>
                    <div class='card-innr'>
                        <div class='card-head has-aside'>
                            <h4 class='card-title card-title-sm recent_transactions'>".$lang['recent_transactions']."</h4>
                            <div class='card-opt'>
                                <a href=". route('user.transactions') ." class='link ucap view_all'>".$lang['view_all']." <em class='fas fa-angle-right ml-2'></em></a>
                            </div>
                        </div>
                        <table class='table tnx-table'>
                            <tbody>";
                            	
                            	$trnxs_index = count($trnxs);
                            	    if(count($trnxs) >= 2) {
                            	        $trnxs_index = 2;
                            	    }
                            	
                            	for($i = 0; $i < $trnxs_index; $i++){
                                    $return .= '<tr>
                                    <td>
                                        <h5 class="lead mb-1">' . $trnxs[$i]->tnx_id . '</h5>
                                        <span class="sub">' .  _date($trnxs[$i]->tnx_time) .'</span>
                                    </td>
                                    <td class="d-none d-sm-table-cell">
                                        <h5 class="lead mb-1">
                                            '. (starts_with($trnxs[$i]->total_tokens, '-') ? '' : '+').to_round($trnxs[$i]->tokens, 'min') .'
                                        </h5>
                                        <span class="sub ucap">'. round(to_num($trnxs[$i]->amount, 'max'), 3).' '.$trnxs[$i]->currency .'</span>
                                    </td>
                                    <td class="text-right">
                                        <div class="data-state data-state-'. __status($trnxs[$i]->status, 'icon') .'"></div>
                                    </td>
								</tr>';
                            	}
                            	
                            	
                            	
                $return .= "</tbody>
                        </table>
                    </div>
                </div>";
        
        return $return;
        
    }
    
    
    public static function fulltransactions($lang, $data = null, $atttr = '')
    {
        
        $trnxs = Transaction::where('user', Auth::id())
                    ->where('status', '!=', 'deleted')
                    ->where('status', '!=', 'new')
                    ->whereNotIn('tnx_type', ['withdraw'])
                    ->orderBy('created_at', 'DESC')->get();
        
        $atttr_def = array('id' => '', 'class' => '', 'vers' => '');
        $opt_atttr = parse_args($atttr, $atttr_def);
        extract($opt_atttr);
        $g_id = ($id) ? ' id="' . $id . '"' : '';
        $g_cls = ($class) ? css_class($class) : '';

        $sales_raised = (token('sales_raised')) ? token('sales_raised') : 'token';
        $sales_total = (token('sales_total')) ? token('sales_total') : 'token';
        $sales_caps = (token('sales_cap')) ? token('sales_cap') : 'token';
        $title = $progress = $progress_bar = $sales_end_in = $sales_start_in = '';
        
        
        
        
    }
    
    
    
    public static function bulls($lang, $data = null, $atttr = '')
    {
        
        $return = '<div class="card-innr d-md-none">
                    
                    <a href="'. route('user.investment') .'">
                    
                        <div class="popup-body-innr">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="kyc-option-title mt-1 mb-1 our_plans">'.$lang['our_plans'].'</h5>
                                
                                <div class="d-flex align-items-center guttar-20px">
                                    <div class="flex-col d-sm-block d-none">
                                        <a href="'. route('user.investment') .'" class="btn btn-light btn-sm btn-auto btn-primary"><em class="fas fa-arrow-right mr-3"></em></a>
                                    </div>
                                    <div class="flex-col d-sm-none">
                                        <a href="'. route('user.investment') .'" class="btn btn-light btn-icon btn-sm btn-primary"><em class="fas fa-arrow-right"></em></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </a>
                        
                    </div>
                    
                    <div class="card-innr d-none d-sm-none d-md-block">
                    
                        <div class="user-account-status">
                            <h6 class="card-title card-title-sm your_account_status">'.$lang['our_investment_bulls'].'</h6>
                            <div class="gaps-1-5x"></div>
                            <p>'.$lang['bulls_info_text'].'</p>
                            <a href="'.route('user.investment').'" class="btn btn-md btn-primary">'.$lang['learn_more'].'</a>
                        </div>
                        
                    </div>';
                    
                    return $return;
        
    }
    
    
    public static function questionnaire($lang, $data = null, $atttr = '')
    {
        
        $return = '<div class="card card-sales-progress" id="chooseplansection">
                    <div class="card-innr">
                    
                    
                    <a href="'. route('user.token') .'">
                    
                        <div class="popup-body-innr">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="kyc-option-title mt-1 mb-1 questionnaire_title">'.$lang['investment_questionnaire'].'</h5>
                                
                                <div class="d-flex align-items-center guttar-20px">
                                    <div class="flex-col d-sm-block d-none">
                                        <a href="'. route('user.token') .'" class="btn btn-light btn-sm btn-auto btn-primary"><em class="fas fa-arrow-right mr-3"></em></a>
                                    </div>
                                    <div class="flex-col d-sm-none">
                                        <a href="'. route('user.token') .'" class="btn btn-light btn-icon btn-sm btn-primary"><em class="fas fa-arrow-right"></em></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </a>
                        
                    </div>
                    </div>';
                    
                    return $return;
        
    }
    

    /**
    * user_referral_info()
    *
    * @version 1.0.0
    * @since 1.0.3
    * @return void
    */
    public static function user_referral_info($lang, $data=null, $atttr='')
    {
        $atttr_def = array( 'id'    => '', 'class' => '', 'vers' => '' );
        $opt_atttr = parse_args( $atttr, $atttr_def ); extract($opt_atttr);
        $g_id = ($id) ? ' id="'.$id.'"' : ''; $g_cls = ($class) ? css_class($class) : '';

        $auth       = auth(); $refers = $heading = '';
        $ref_url    = route('public.referral').'?ref='.set_id($auth->id());
        $ref_page   = route('user.referral', get_slug('referral'));
        $more       = (isset($data['more']) && $data['more']=='hide') ? '' : '<div class="card-opt"><a href="'.$ref_page.'" class="link ucap more_referral">'.$lang['learn_more'].'<em class="fas fa-angle-right ml-1"></em></a></div>';
        $heading    .= '<div class="card-head has-aside"><h6 class="card-title card-title-sm earn_with">'.$lang['ambassador_program'].'</h6>'.$more.'</div>';
        $refers     .= '<p class="pdb-0-5x"><strong><span class="invite_friends_and_family">'.$lang['invite_your_friends_family'].'</span></strong></p>';
        $refers     .= '<div class="copy-wrap mgb-0-5x"><div type="text" class="" value="Get in touch with our team and earn up to 5% for referrals!" disabled="" style="
    padding: 2px 20px;
    resize: none;
    line-height: initial;
    min-height: 0;
    height: auto !important;
    margin: auto;
    max-height: 100px;
    border-left: 1px solid gray;
">'.$lang['ambassador_program_text'].'</div></div>';

        $return = ($refers) ? '<div'.$g_id.' class="referral-info card'.$g_cls.'"><div class="card-innr">'.$heading.$refers.'</div></div>' : '';
        return ( get_page('referral', 'status') == 'active' ? $return : '');
    }
    
    
    
    
    
    public static function how_to_buy_crypto($lang, $data=null, $atttr='')
    {
        $atttr_def = array( 'id'    => '', 'class' => '', 'vers' => '' );
        $opt_atttr = parse_args( $atttr, $atttr_def ); extract($opt_atttr);
        $g_id = ($id) ? ' id="'.$id.'"' : ''; $g_cls = ($class) ? css_class($class) : '';

        $auth       = auth(); $refers = $heading = '';
        $ref_url    = route('public.referral').'?ref='.set_id($auth->id());
        $ref_page   = route('user.buycrypto');
        $more       = (isset($data['more']) && $data['more']=='hide') ? '' : '<div class="card-opt pdt-0-5x"><a href="'.$ref_page.'" class="link ucap more_referral">'.$lang['learn_more'].'<em class="fas fa-angle-right ml-1"></em></a></div>';
        $heading    .= '<div class="card-head has-aside"><h6 class="card-title card-title-sm earn_with">'.$lang['buycrypto_title'].'</h6>'.$more.'</div>';
//        $refers     .= '<p class="pdb-0-5x"><strong><span class="invite_friends_and_family">'.$lang['invite_your_friends_family'].'</span></strong></p>';
        $refers     .= '<div class="copy-wrap mgb-0-5x"><div type="text" class="" value="Get in touch with our team and earn up to 5% for referrals!" disabled="" style="
    padding: 2px 20px;
    resize: none;
    line-height: initial;
    min-height: 0;
    height: auto !important;
    margin: auto;
    max-height: 100px;
    border-left: 1px solid gray;
">'.$lang['buycrypto_text_small'].'</div></div>';

        $return = ($refers) ? '<div class="card-innr">'.$heading.$refers.'</div>' : '';
        return ( get_page('referral', 'status') == 'active' ? $return : '');
    }
    
    

    
    
    
    
}
