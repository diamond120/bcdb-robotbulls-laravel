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
use DB;
use App\Models\ClientMessage;
use Illuminate\Support\Facades\Crypt;



class MessagesController extends Controller
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
        
        $role_data  = '';
        $per_page   = gmvl('user_per_page', 10);
        $order_by   = (gmvl('user_order_by', 'id')=='token') ? 'tokenBalance' : gmvl('user_order_by', 'id');
        $ordered    = gmvl('user_ordered', 'DESC');
        $is_page    = (empty($role) ? 'all' : $role);
        
        
        
        // Retrieve latest message of each user
        $messages = ClientMessage::select('user', DB::raw('MAX(created_at) as latest_message_time'))
            ->groupBy('user')
            ->orderBy('latest_message_time', 'desc')
            ->get();

        $data = [];
        foreach ($messages as $message) {
            $latestMessage = ClientMessage::where('user', $message->user)
                ->where('created_at', $message->latest_message_time)
                ->first();



            $data[] = [
                'user_id' => $latestMessage->user,
                'from' => $this->safeDecrypt($latestMessage->from),
                'user_phone' => $this->safeDecrypt($latestMessage->user_phone),
                'channel' => $this->safeDecrypt($latestMessage->channel),
                'latest_message' => $this->safeDecrypt($latestMessage->message),
                'message_date' => $latestMessage->created_at
            ];

        }


        return view('admin.messages', compact('data', 'role_data', 'is_page'));
    }
    
    private function safeDecrypt($data) {
        try {
            return Crypt::decryptString($data);
        } catch (\Exception $e) {
            return $data;
        }
    }






}
