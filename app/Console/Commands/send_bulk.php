<?php

namespace App\Console\Commands;

use App\Models\SmsCampaign;
use App\Models\PhoneNumber;
use App\Models\PhoneNumberTest;
use App\Models\SmsReturnMessage;
use Illuminate\Console\Command;
use Twilio\Rest\Client;
use Twilio\Exceptions\RestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class send_bulk extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:send_bulk {campaign_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $campaign_id = $this->argument('campaign_id');
      //  $campaign_id = $this->param1;
        $message = SmsCampaign::where('id', $campaign_id)->value('message');
        $valid_sms = PhoneNumberTest::where('is_valid', true)->where('is_cancelled', false)->get();
        $valid_sms_count = $valid_sms->count();
        $totalSteps = $valid_sms->count();
        $currentStep = 0;
        $progressFile = storage_path('app/long-running-command-progress.json');
        // Save the initial progress
        file_put_contents($progressFile, json_encode([
            'total_steps' => $totalSteps,
            'current_step' => $currentStep,
        ]));


        /* test mode on*/
        $phones = PhoneNumberTest::where('is_valid', true)->where('is_cancelled', false)->get();
        //   $message = "this is first test run";
        $result = [];
        $total_result = ['messaged', 'refused'];
        $total_result['messaged'] = 0;
        $total_result['refused'] = 0;
        foreach ($phones as $phone) {
            // sleep(1);
            $currentStep++;


            $count_messaged = 0;
            $count_refused = 0;
            $result = $this->sendMessage($message, $phone->phone, $count_messaged, $count_refused, $campaign_id);
            //        dd($result);
            $total_result['messaged'] = $total_result['messaged'] + $result['messaged'];
            $total_result['refused'] = $total_result['refused'] + $result['refused'];


            // Save the progress
            //      dd($totalSteps);
            file_put_contents($progressFile, json_encode([
                'total_steps' => $valid_sms_count,
                'current_step' => $currentStep,
            ]));
            SmsCampaign::where('id',$campaign_id)->update(['current_steps' => $currentStep]);
            time_nanosleep(0, 100000);
            if ($currentStep >= $valid_sms_count) {
                break;
            };
        }
        //     unlink($progressFile);
        //  dd($campaign_id);
        SmsCampaign::find($campaign_id)->update(
            [
                'number_valid' => $valid_sms_count,
                'number_sent' => $total_result['messaged'],
                'sent_date' => now(),
                'number_refused' => $total_result['refused'],
            ]
        );
        return $result;
    }

    private function sendMessage($message, $to, $count_messaged, $count_refused, $campaign_id)
    {

        $count_messaged = 1;

        $account_sid = getenv("TWILIO_TEST_SID");
        $auth_token = getenv("TWILIO_TEST_AUTH_TOKEN");
        $twilio_number = getenv("TWILIO_TEST_NUMBER");

        $client = new Client($account_sid, $auth_token);

        try {
            $result = $client->messages->create(
                $to,
                [
                    'from' => $twilio_number,
                    'body' => $message
                ]
            );

            SmsReturnMessage::create([
                'phone_to' => $to,
                'phone_from' => $twilio_number - 10000000000,
                'campaign_id' => $campaign_id,
                'from' => getenv("TWILIO_TEST_NUMBER"),
                'body' => $message,
                'date_created' => $result->dateCreated,
                'date_sent' => $result->dateSent,
                'status' => $result->status,
                'sid' => $result->sid
            ]);

        } catch (RestException $e) {
            $count_refused = 1;

            if ($e->getStatusCode() == 400) {
                echo "Other error: ", $e->getMessage();
                Log::info($e->getMessage());
                $from = substr($twilio_number, -10);
                PhoneNumber::where('phone', '=', $to)
                    ->update(['is_cancelled' => true]);

                SmsReturnMessage::create([
                    'phone_to' => $to,
                    'phone_from' => $twilio_number - 10000000000,
                    'campaign_id' => $campaign_id,
                    'from' => getenv("TWILIO_TEST_NUMBER"),
                    'body' => $e->getMessage(),
                    'date_sent' => now(),
                    'status' => 400
                ]);


            } else {
                echo "Other error: ", $e->getMessage();
            }
        }
        $result = [];
        $result['messaged'] = $count_messaged;
        $result['refused'] = $count_refused;

        return ($result);
    }
}
