<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Order\Entities\Fail;
use App\Mail\SendFailOrder;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;

class SendOrderFailToMail extends Command
{
   /**
    * The name and signature of the console command.
    *
    * @var string
    */
   protected $signature = 'app:send-order-fail-to-mail';

   /**
    * The console command description.
    *
    * @var string
    */
   protected $description = 'Command description';

   /**
    * Execute the console command.
    */
   public function handle()
   {
      $emails = [];
      $faill = Fail::where('active', 0)->get();
      if ($faill) {
         $this->info('Request sent successfully.');
         $path = storage_path('emailssendfail.json'); // or resource_path('data.json');
         $jsonContent = File::get($path);

         // Decode the JSON data into an associative array
         $data = json_decode($jsonContent, true);
         foreach ($data['emails'] as $row) {
            $emails[] = $row['email'];
         }
      
       Mail::to($emails)->send(new SendFailOrder($faill));
      }
   }
}
