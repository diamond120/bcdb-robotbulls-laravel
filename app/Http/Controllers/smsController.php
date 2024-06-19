<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;

class smsController extends Controller {

    public function sendMessage(Request $request) {

        $twilio = new Client($account_sid, $auth_token);

        $from = 'RobotBulls';
        $channel = 'sms';
        $from_phone = '+41798079541';
        $created_at = date('Y-m-d H:i:s');
    
        $user = User::find($request->input('user_id'));
        
        if ($user) {
            $to_phone = $user->email; // Assuming you have a 'phone' column in your User model.
        } else {
            $to_phone = $request->input('user_id');
            if (substr($to_phone, 0, 1) !== '+') {
                $to_phone = '+' . $to_phone;
            }
        }
            
        $message = $twilio->messages->create(
            $to_phone, // recipient phone number
            [
                'from' => $from,
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
        $phoneNumber = $request->input('From');
        $body = $request->input('Body');
        $user = User::where('email', $phoneNumber)->first();
        $from = 'user';
        $channel = 'sms';
        $from_phone = '+41798079541';
        $created_at = date('Y-m-d H:i:s');
        $messageSid = $request->input('MessageSid');

        if ($user) { // Check if the user is null
            $user_id = $user->id;
            $phoneNumber = Crypt::encryptString($phoneNumber);
        } else {
            $user_id = str_replace("+", "", $phoneNumber);
            $phoneNumber = $phoneNumber;
        }
        
        DB::table('client_messages')->insert([
            'user' => $user_id,
            'user_phone' => $phoneNumber,
            'created_at' => $created_at,
            'message' => Crypt::encryptString($body),
            'from' => Crypt::encryptString($from),
            'channel' => Crypt::encryptString($channel),
            'from_phone' => Crypt::encryptString($from_phone),
            'sid' => $messageSid,
        ]); 
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
