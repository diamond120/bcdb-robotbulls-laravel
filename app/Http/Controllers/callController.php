<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Twilio\Jwt\ClientToken;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VoiceGrant;

class callController extends Controller {

    private $account_sid;
    private $auth_token;
    private $api_key;
    private $api_secret;

    public function __construct() {

    }

    public function getToken(Request $request) {
        $identity = $request->get('identity');
        $accessToken = new AccessToken(
            $this->account_sid,
            $this->api_key,
            $this->api_secret,
            3600,
            $identity
        );

        $voiceGrant = new VoiceGrant();
        $voiceGrant->setOutgoingApplicationSid('YOUR_TWILIO_TWIML_APP_SID');
        $voiceGrant->setIncomingAllow(true);
        $accessToken->addGrant($voiceGrant);

        return response()->json(['token' => $accessToken->toJWT()]);
    }

    public function makeCall(Request $request) {
        $to = $request->input('to'); // This is the phone number to call
        $client = new Client($this->account_sid, $this->auth_token);

        $call = $client->calls->create(
            $to,
            'YOUR_TWILIO_PHONE_NUMBER', // This is your Twilio phone number
            ['url' => route('twilio.voice')]
        );

        return response()->json(['message' => 'Call initiated!']);
    }

    public function voice(Request $request) {
        $response = new \Twilio\TwiML\VoiceResponse();
        $dial = $response->dial('');
        $dial->client('default'); // This will ring the browser client

        return response($response->asXML())->header('Content-Type', 'text/xml');
    }
    
}
