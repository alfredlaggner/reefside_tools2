<?php

namespace App\Http\Controllers;

use App\Models\PhoneNumber;
use Illuminate\Http\Request;
use Exception;
use Twilio\Rest\Client;
use App\ModelsPhoneNumber;
use Twilio\TwiML\MessagingResponse;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PhoneNumberImport;

{
    class  TwilioSMSController extends Controller
    {
        /**
         * Write code on Method
         *
         * @return response()
         */

        public function send_mass_sms()
        {
            $number = '+15555555555';
            $account_sid = getenv("TWILIO_ACCOUNT_SID");
            $auth_token = getenv("TWILIO_AUTH_TOKEN");
            $twilio_number = getenv("TWILIO_FROM");
            echo $account_sid . "<br>";
            echo $auth_token . "<br>";
            echo $twilio_number . "<br>";
            $client = new Client($account_sid, $auth_token);
            $phones = PhoneNumber::get();

            foreach ($phones as $phone) {
                $receiverNumber = "+1" . $phone->number;
                echo $receiverNumber;
                $message = "All xx About Laravel";

                try {

                    $client->messages->create($receiverNumber, [
                        'from' => $twilio_number,
                        'body' => $message]);

                    dd('SMS Sent Successfully.');

                } catch (Exception $e) {
                    dd("Error: " . $e->getMessage());
                }
            }
        }

        public function index()
        {
            $receiverNumber = "+18313341553";
            $message = "All About Laravel";

            $sid = "ACd5e0b63852a8908567e8a5ebb0b02ef0";
            $token = "84b8eec387ea7aac54b8e03c6727157e";
            $twilio_number = "+19785033017";
            $client = new Client($sid, $token);
            $message = $client->messages->create(
                $receiverNumber,
                    [
                        "body" => "Hi there",
                        "from" => $twilio_number
                    ]
                );

            print($message->sid);
        }

        public function import()
        {
            ini_set('max_execution_time', '0');

            Excel::import(new PhoneNumberImport, 'reefside_phones.xlsx');

            return redirect('/')->with('success', 'All good!');
        }

        public function receive_messages(Request $request)
        {
            $response = new MessagingResponse;
            $body = $_REQUEST['Body'];
            $default = "I just wanna tell you how I'm feeling - Gotta make you understand";
            $options = [
                "give you up",
                "let you down",
                "run around and desert you",
                "make you cry",
                "say goodbye",
                "tell a lie, and hurt you"
            ];

            if (strtolower($body) == 'never gonna') {
                $response->message($options[array_rand($options)]);
            } else {
                $response->message($default);
            }
            print $response;
        }

    }
}
