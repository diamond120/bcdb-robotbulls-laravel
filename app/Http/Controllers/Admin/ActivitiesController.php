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
use Carbon\Carbon;



class ActivitiesController extends Controller
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
    if(auth()->user()->role == 'admin') {
        
        $role_data  = '';
        $per_page   = gmvl('user_per_page', 10);
        $order_by   = (gmvl('user_order_by', 'id')=='token') ? 'tokenBalance' : gmvl('user_order_by', 'id');
        $ordered    = gmvl('user_ordered', 'DESC');
        $is_page    = (empty($role) ? 'all' : $role);

        $dateCondition = [];
        switch ($role) {
            case 'today':
                $dateCondition = [Carbon::now()->subDay(), Carbon::now()];
                break;
            case 'last-week':
                $dateCondition = [Carbon::now()->subWeek(), Carbon::now()];
                break;
            case 'last-two-week':
                $dateCondition = [Carbon::now()->subWeeks(2), Carbon::now()];
                break;
            case 'last-month':
                $dateCondition = [Carbon::now()->subMonth(), Carbon::now()];
                break;
            case 'last-three-months':
                $dateCondition = [Carbon::now()->subMonths(3), Carbon::now()];
                break;
            case 'last-six-months':
                $dateCondition = [Carbon::now()->subMonths(6), Carbon::now()];
                break;
            case 'last-year':
                $dateCondition = [Carbon::now()->subYear(), Carbon::now()];
                break;
        }

        $users = User::whereNotIn('status', ['deleted']);

        if($request->s){
            $users = User::AdvancedFilter($request);
        }

        if ($request->filter) {
            $users = User::AdvancedFilter($request);
        }

        // Add orderBy with activities count
        // if (!empty($dateCondition)) {
        //     $users = $users->withCount(['activities as activities_count' => function($query) use ($dateCondition){
        //         $query->whereBetween('created_at', $dateCondition);
        //     }])->orderBy('activities_count', 'desc');
        // } else {
        //     $users = $users->withCount('activities as activities_count')->orderBy('activities_count', 'desc');
        // }

        if ($request->filled('exclude_user_ids')) {
            $users->where('role', 'user');
        }

        $users = $users->paginate($per_page);
        $pagi = $users->appends($request);

        return view('admin.users', compact('users', 'role_data', 'is_page', 'pagi'));
    
    } else {
        return redirect()->route('admin.transactions', 'expiring');
    }
}






}
