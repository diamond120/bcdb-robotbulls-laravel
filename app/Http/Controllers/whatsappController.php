<?php


namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use GuzzleHttp\Exception\RequestException;
// use GuzzleHttp\Client;
use DateTime;
use DateInterval;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use App\Mail\EmailToUser;


class whatsappController extends Controller
{
    


    public function sendMessage(Request $request) {

        $sid    = $account_sid;
        $token  = $auth_token;

        $twilio = new Client($sid, $token);

        $from = 'RobotBulls';
        $channel = 'whatsapp';
        $from_phone = '+41795778777';
        $created_at = date('Y-m-d H:i:s');
    
        $user = User::find($request->input('user_id'));

        if ($user) {
            $to_phone = $user->email;
        } else {
            $to_phone = $request->input('user_id');
            if (substr($to_phone, 0, 1) !== '+') {
                $to_phone = '+' . $to_phone;
            }
        }

        $message = $twilio->messages->create(
            'whatsapp:'. $to_phone, // recipient WhatsApp number
            [
                'from' => 'whatsapp:' . $from_phone,
                'body' => $request->input('message')
            ]
        );

        DB::table('client_messages')->insert([
            'user' => $request->input('user_id'),
            'user_phone' => Crypt::encryptString($to_phone),
            'created_at' => $created_at,
            'message' => Crypt::encryptString($request->input('message')),
            'from' => Crypt::encryptString($from),
            'channel' => Crypt::encryptString($channel),
            'from_phone' => Crypt::encryptString($from_phone),
            'sid' => $message->sid,
        ]); 

        return back();
    }


    public function receiveMessage(Request $request) {

        $senderNumber = $request->input('From');
        $phoneNumber = str_replace('whatsapp:', '', $senderNumber);
        $body = $request->input('Body');
        $user = User::where('email', $phoneNumber)->first();
        $from = 'user';
        $channel = 'whatsapp';
        $from_phone = '+41795778777';
        $created_at = date('Y-m-d H:i:s');
        $messageSid = $request->input('MessageSid');

        if ($user) { // Check if the user is null
            $user_id = $user->id;
            $phoneNumber = Crypt::encryptString($phoneNumber);
            $userinfo = $user->name;
        } else {
            $user_id = str_replace("+", "", $phoneNumber);
            $phoneNumber = $phoneNumber;
            $userinfo = $phoneNumber;
        }
        
        DB::table('client_messages')->insert([
            'user' => $user_id,
            'user_phone' => Crypt::encryptString($phoneNumber),
            'created_at' => $created_at,
            'message' => Crypt::encryptString($body),
            'from' => Crypt::encryptString($from),
            'channel' => Crypt::encryptString($channel),
            'from_phone' => Crypt::encryptString($from_phone),
            'sid' => $messageSid,
        ]); 
        
        $emailTo = 'robinkrambroeckers@gmail.com';
        
        $data = (object) [
            'user' => (object) ['name' => 'RobotBulls', 'email' => $emailTo],
            'subject' => 'New Whatsapp Message Received',
            'greeting' => 'Hello,',
            'text' => "You have a new WhatsApp message from: {$userinfo}.\n\nMessage: {$body}"
        ];
        $when = now()->addMinutes(2);

        try {
            Mail::to($emailTo)
                ->later($when, new EmailToUser($data));
            $ret['msg'] = 'success';
            $ret['message'] = __('messages.mail.send');
        } catch (\Exception $e) {
            $ret['errors'] = $e->getMessage();
            $ret['msg'] = 'warning';
            $ret['message'] = __('messages.mail.issues');
        }

    }

    
    public function handleStatus(Request $request) {
        $messageSid = $request->input('MessageSid');
        $messageStatus = $request->input('MessageStatus');
    
        DB::table('client_messages')
            ->where('sid', $messageSid)
            ->update([
                'status' => $messageStatus,
            ]); 
    }

}
