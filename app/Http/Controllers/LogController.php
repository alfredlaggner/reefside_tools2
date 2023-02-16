<?php

namespace App\Http\Controllers;

use App\Models\PhoneNumber;
use Illuminate\Http\Request;
use Airtable;
use App\Models\SmsLog;
use Twilio\TwiML\MessagingResponse;

class LogController extends Controller
{

    public function handleSms(Request $request)
    {
        // Retrieve the incoming message from the request
        $message = $request->input('Body');
        $from = $request->input('From');
        $message_id = $request->input("message_id");
        // Do something with the message, for example, save it to the database
        $sms = new SmsLog;
        $sms->message = $request->input('Body');
        $sms->from = $request->input('From');
        $sms->message_id = $request->input("MessageSid");
        $sms->save();

        return response('Success', 200);
    }

    public function logSms(Request $request)
    {

        session(['stop_message' => substr(strtolower($request->input('Body')), 0, 4)]);
        session(['from' =>substr($request->input('From'), -10)]);


        $response = new MessagingResponse();
        $response->message("The Robots are coming! Head for the hills!");
        //  print $response;

        $id = SmsLog::table('smslogs')->insertGetId([
            'message_id' => $request->input('MessageSid'),
            'from' => $request->input('From'),
            'to' => $request->input('To'),
            'message' => $request->input('Body')
        ]);

        $stop_message = substr(strtolower($request->input('Body')), 0, 4);
        $from = substr($request->input('From'), -10);


        if ($stop_message == "stop")
        {
            $rv = PhoneNumber::where('phone', '=', $from)
                ->update(['is_cancelled' => true]);
            $request->session()->put('rv',$rv);

        }
        if ($stop_message == "start")
        {
            PhoneNumber::where('phone', '=', $from)
                ->update(['is_cancelled' => false]);
        }
    }


    public
    function cancel_subscription(Request $request)
    {

        echo $request->session()->get('stop_message');
        echo $request->session()->get('from');

        dd($request->session()->all());


        $smslog = SmsLog::orderby('id', 'desc')->first();

        $stop_message = substr(strtolower($smslog->message), 0, 4);
        $from = substr($smslog->from, -10);

        if ($stop_message == getenv("CANCEL")) {
            PhoneNumber::where('phone', '=', $from)
                ->update(['is_cancelled' => true]);
        }

    }

    public function oldlogSms(Request $request)
    {


        $response = new MessagingResponse();
        $response->message("The Robots are coming! Head for the hills!");
        //  print $response;


// use insertGetId!!!

        $smslog = SmsLog::table('smslogs')->create([
            'message_id' => $request->input('MessageSid'),
            'from' => $request->input('From'),
            'to' => $request->input('To'),
            'message' => $request->input('Body')
        ]);


        if ($smslog->wasRecentlyCreated === true) {
            $smslog = SmsLog::orderby('id', 'desc')->first();

            $stop_message = substr(strtolower($smslog->message), 0, 4);
            $from = substr($smslog->from, -10);

            if ($stop_message == getenv("CANCEL")) {
                PhoneNumber::where('phone', '=', $from)
                    ->update(['is_cancelled' => true]);
            }
        } else {
            // item was found and returned from the database
        }
        $smslog = SmsLog::orderby('id', 'desc')->first();

        $stop_message = substr(strtolower($smslog->message), 0, 4);
        $from = substr($smslog->from, -10);

        if ($stop_message == getenv("CANCEL")) {
            PhoneNumber::where('phone', '=', $from)
                ->update(['is_cancelled' => true]);
        }

    }



}
