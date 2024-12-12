<?php

namespace Modules\Basic\Actions\Export;

use Modules\Basic\Entities\JobTracking;

class ExportAction
{
    protected $request;

    protected $job;

    /**
     * Create a new Repository instance.
     *
     * @return void
     */
    public function __construct(string $job)
    {
        $this->job = $job;
    }


    public function execute()
    {
        $jobClassName = '\Modules\Basic\Jobs\\' . $this->job;
        // Generate a unique token for the operation
        $token = uniqid('export_', true);

        $jobClassName::dispatch(
            request: request()->all(),
            token: $token
        )->onQueue('exports');
        //todo change
        JobTracking::create([
            'user_id' => user()->id ?? auth()->id(),
            'token' => $token,
            'type' => $this->job
        ]);
    }
}
