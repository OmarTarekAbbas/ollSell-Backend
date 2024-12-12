<?php

namespace App\Jobs;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Order\Actions\Order\SendOrderToWMSAction;
class SendOrderToWMSJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    private $order;
    private $dropshipper_id;

 
    /**
     * Create a new job instance.
     *
     * return void
     */
    public function __construct($order)
    {
      
        $this->order = $order;
 
    }

    /**
     * Execute the job.
     *
     * return void
     */
    public function handle()
    {
        App(SendOrderToWMSAction::class)->execute($this->order);
    }



}
