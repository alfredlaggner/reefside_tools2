<?php

namespace App\Console\Commands;

use App\Models\PhoneNumber;
use App\Models\PhoneNumberTest;
use Illuminate\Console\Command;
use Twilio\Rest\Client;

class phone_validate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:validate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Validate Reefside Phone Numbers';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $sid = getenv("TWILIO_TEST_SID");
        $token = getenv("TWILIO_TEST_AUTH_TOKEN");
        $twilio = new Client($sid, $token);
        //   $phones = PhoneNumber::where('id',"<", 1000)->get();
        $count = 0;
        $not_valid = 0;
        ini_set('max_execution_time', '0'); // for infinite time of execution
        foreach (PhoneNumberTest::where('is_valid', false )->lazy() as $phone) {
            $result = $twilio->lookups->v2->phoneNumbers($phone->phone)->fetch();
            if (!$result->valid) {
                PhoneNumberTest::where('phone', '=', $phone->phone)->update(['is_valid' => false]);
                $this->info($phone->phone . " is not a valid phone number " . $count);
                $not_valid++;
            } else {
                $count++;
                PhoneNumberTest::where('phone', '=', $phone->phone)->update(['is_valid' => true]);
                $this->info($phone->phone . " is a valid phone number " . $count);
            }
        }
        $this->info("valid: " . $count . " not_valid: " . $not_valid);
        return Command::SUCCESS;
    }
}
