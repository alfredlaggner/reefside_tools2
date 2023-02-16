<?php

namespace App\Http\Controllers;

use App\Imports\SmsLogImport;
use App\Jobs\sendBulk;
use App\Models\SmsCampaign;
use App\Models\SmsLog;
use App\Models\TwilioResultLog;
use Illuminate\Http\Request;
use App\Models\SmsReturnMessage;
use App\Models\UsersPhoneNumber;
use App\Models\PhoneNumber;
use App\Models\PhoneNumberTest;
use Twilio\Rest\Client;
use App\Imports\PhoneNumberImport2;
use App\Exports\PhoneNumberExport;
use Maatwebsite\Excel\Facades\Excel;
use Twilio\Exceptions\RestException;
use Illuminate\Support\Facades\Artisan;
use App\Tables\SmsCampaignTable;
use Twilio\TwiML\MessagingResponse;
class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        //  $smsCampaignTable = new SmsCampaignTable();
        $users = UsersPhoneNumber::get(); //query db with model
        $campagnes = SmsCampaign::orderBy('id', 'desc')->get();
        //  return livewire('users-table', compact("users", "campagnes"));
        return view('welcome', compact("users", "campagnes")); //return view with data
    }

    public function create()
    {
        return view('campaign.create');
    }


    public function bulkMessaging(Request $request)
    {
        $id = $request->input('id');
        dispatch(new sendBulk($id));
        return view('progress');
    }
    public function viewBulkMessaging()
    {
        return view('progress');
    }

    public
    function bulkVerify(Request $request)
    {
        $id = $request->input('id');
        $exit_code = Artisan::call('sms:validate');
        return back();
    }

    private
    function send_MMS_Message($message, $to)
    {
      //  dd($to);
        /* test mode on */
        $account_sid = getenv("TWILIO_SID");
        $auth_token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_number = getenv("TWILIO_NUMBER");
        $imageUrl = asset('storage/' . "20230128_022646014_iOS.jpg");
//dd($imageUrl);
        $client = new Client($account_sid, $auth_token);
        try {
            $result = $client->messages->create(
                $to,
                [
                    'from' => $twilio_number,
                    'body' => $message,
                    "mediaUrl" => [],
                    "statusCallback" => "https://tools.reefside.us/sms-status-callback",
                ]
            );
          //    dd($result);
            SmsReturnMessage::create([
                'phone_to' => $to,
                'from' => '+15005550006',
                'body' => $message,
                'date_created' => $result->dateCreated,
                'date_sent' => $result->dateSent,
                'status' => $result->status,
                'sid' => $result->sid
            ]);
        } catch (RestException $e) {
            if ($e->getStatusCode() == 400) {
                echo "Other error: ", $e->getMessage();
                $from = substr($twilio_number, -10);
                PhoneNumber::where('phone', '=', $to)
                    ->update(['is_cancelled' => true]);

            } else {
                echo "Other error: ", $e->getMessage();
            }
        }
        return back()->with(['success' => "done"]);
    }


    private
    function sendMessage($message, $to)
    {
        /* test mode on */
        $account_sid = getenv("TWILIO_TEST_SID");
        $auth_token = getenv("TWILIO_TEST_AUTH_TOKEN");
        $twilio_number = getenv("TWILIO_TEST_NUMBER");


        $client = new Client($account_sid, $auth_token);
        try {
            $result = $client->messages->create(
                $to,
                [
                    'from' => $twilio_number,
                    'body' => $message,
                    "statusCallback" => 'https://enhme59jbxdu.x.pipedream.net'
                ]
            );
            //  dd($result);
            SmsReturnMessage::create([
                'phone_to' => $to,
                'from' => '+15005550006',
                'body' => $message,
                'date_created' => $result->dateCreated,
                'date_sent' => $result->dateSent,
                'status' => $result->status,
                'sid' => $result->sid
            ]);
        } catch (RestException $e) {
            if ($e->getStatusCode() == 400) {
                echo "Other error: ", $e->getMessage();
                $from = substr($twilio_number, -10);
                PhoneNumber::where('phone', '=', $to)
                    ->update(['is_cancelled' => true]);

            } else {
                echo "Other error: ", $e->getMessage();
            }
        }
        return back()->with(['success' => "done"]);
    }


    public
    function validate_phone_numbers()
    {
        $sid = getenv("TWILIO_SID");
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio = new Client($sid, $token);
        //   $phones = PhoneNumber::where('id',"<", 1000)->get();

        foreach (PhoneNumber::where('id', "<", 1000)->lazy() as $phone) {
            $result = $twilio->lookups->v2->phoneNumbers($phone->phone)->fetch();
            print($phone->phone . " tested . $result->valid. <br>");

            if (!$result->valid) {
                PhoneNumber::where('phone', '=', $phone->phone)->update(['is_valid' => false]);
                print($result->phoneNumber . "is not a valid phone number . <br>");
            } else {
                PhoneNumber::where('phone', '=', $phone->phone)->update(['is_valid' => true]);
                print($result->phoneNumber . "is valid . <br>");
            }
        }
    }

    public
    function storePhoneNumber(Request $request)
    {
        //run validation on data sent in
        $validatedData = $request->validate([
            'phone_number' => 'required|unique:users_phone_number|numeric',
        ]);
        $user_phone_number_model = new UsersPhoneNumber($request->all());
        $user_phone_number_model->save();
        $this->sendMessage('User registration successful!!', $request->phone_number);
        return back()->with(['success' => "{$request->phone_number} registered"]);
    }

    /*
     *
     * 200 OK: The message was successfully sent.
    201 Created: A message instance resource has been successfully created.
    400 Bad Request: There was an error in the request, such as an invalid phone number or missing required parameter.
    401 Unauthorized: The Twilio account credentials provided in the request are invalid.
    404 Not Found: The phone number provided in the request could not be found.
    429 Too Many Requests: The Twilio account has exceeded its sending limit.
    500 Internal Server Error: An error occurred on Twilio's server while processing the request.
     * */
    public
    function twilio_error(Request $request)
    {
        dd($request);
    }

    public
    function sendCustomMessage(Request $request)
    {
        $validatedData = $request->validate([
            'users' => 'required|array',
            'body' => 'required',
        ]);
        $recipients = $validatedData["users"];
        // iterate over the array of recipients and send a twilio request for each
        foreach ($recipients as $recipient) {
            $this->send_MMS_Message($validatedData["body"], $recipient);
        }
        return back()->with(['success' => "Messages on their way!"]);
    }

    public
    function fileUploadPost(Request $request)
    {
        $file = $request->file('phones');
        $phoneNumberFile = $file->getClientOriginalName();
        $destinationPath = storage_Path();
        $file->move($destinationPath, $file->getClientOriginalName());
        Excel::import(new PhoneNumberImport2, storage_Path($phoneNumberFile));

        return back()
            ->with('success', 'You have successfully uploaded file: ' . $phoneNumberFile);
    }

    public
    function fileUploadSmsLog(Request $request)
    {
        //   dd(phpinfo());
        $file = $request->file('smslogs');
        $smsLogFileFile = $file->getClientOriginalName();
        $destinationPath = storage_Path();
        $file->move($destinationPath, $file->getClientOriginalName());
        Excel::import(new SmsLogImport, storage_Path($smsLogFileFile));

        return back()
            ->with('success', 'You have successfully uploaded file: ' . $smsLogFileFile);
    }

    public
    function export()
    {
        return Excel::download(new PhoneNumberExport, 'phoneNumbers.xlsx');
    }

    public function twilio_log_results(Request $request)
    {
        $id = $request->input('id');
     //   dd($id);
     //   $id = 7;
        $undelivered = TwilioResultLog::where('error_code','>', 0)->count();
        SmsCampaign::where('id',$id)->update(['total_rejected' => $undelivered]);

        $delivered = TwilioResultLog::where('error_code',0)->count();
        SmsCampaign::where('id',$id)->update(['total_delivered' => $delivered]);

        SmsCampaign::where('id',$id)->update(['number_sent' =>  $delivered + $undelivered]);

        $cancelled = TwilioResultLog::where('body','like', '%stop%')->count();
        SmsCampaign::where('id',$id)->update(['total_cancelled' => $cancelled]);

        $price = TwilioResultLog::sum('price');
        SmsCampaign::where('id',$id)->update(['total_price' => $price]);

        $results = SmsCampaign::where('id',$id)->first();

return view ('campaign.show_results',compact('results'));


        $twilios = TwilioResultLog::with('phone_to')->where('error_code','>', 0)->get();
     //   $twilios = TwilioResultLog::with('phone_to')->where('id',9033)->get();
// Loop through each order
        $count = 0;
        foreach ($twilios as $twilio) {
            $count++;
            // Check the condition
                // Update the related model
                $twilio->phone_to->update([
                    'is_valid' => false,
                ]);
            }


        $twilios = TwilioResultLog::with('phone_from')->where('body','like', '%stop%')->get();
        //   $twilios = TwilioResultLog::with('phone_to')->where('id',9033)->get();
        $count = 0;
        foreach ($twilios as $twilio) {
            $count++;
            // Check the condition
            // Update the related model
            $twilio->phone_from->update([
                'is_cancelled' => true,
            ]);
        }

        dd("cancelled = " . $count);
   }
}
