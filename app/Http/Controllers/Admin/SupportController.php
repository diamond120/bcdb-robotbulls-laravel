<?php


namespace App\Http\Controllers\Admin;

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
use App\Http\Controllers\Controller;


class SupportController extends Controller
{

    public function sendsupportMessage(Request $request) {


        $from = 'RobotBulls';
        $channel = 'support';
        $from_phone = '-';
        $created_at = date('Y-m-d H:i:s');
        $message = $request->input('message');
    
        $user = User::find($request->input('user_id'));

        DB::table('client_messages')->insert([
            'user' => $request->input('user_id'),
            'user_phone' => "-",
            'created_at' => $created_at,
            'message' => Crypt::encryptString($request->input('message')),
            'from' => Crypt::encryptString($from),
            'channel' => Crypt::encryptString($channel),
            'from_phone' => Crypt::encryptString($from_phone),
            'sid' => "-",
        ]); 

        return back()->with(["success" => "Message Sent Successfully"]);
    }

}
