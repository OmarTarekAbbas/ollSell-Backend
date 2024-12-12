<?php

namespace Modules\Order\Jobs;

use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Order\Actions\Order\StartValidationFlowAction;

class ValidateOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $orderIds;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($orderIds)
    {
        $this->orderIds = $orderIds;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $validationRequest = new Request(['orderIds' => $this->orderIds]);

        app(StartValidationFlowAction::class, ['request' => $validationRequest])->execute();
    }
}
