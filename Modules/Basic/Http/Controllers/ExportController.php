<?php

namespace Modules\Basic\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Basic\Entities\JobTracking;
use Modules\Basic\Actions\Export\ExportAction;
use Modules\Basic\Actions\Export\DownloadAction;
//todo change
class ExportController extends Controller
{
    public function export(Request $request)
    {
        (new ExportAction(
            job: $request->job
        ))->execute();

        return response()->json(['success' => true]);
    }

    public function download(Request $request)
    {
        return (new DownloadAction(
            request: $request,
        ))->execute();
    }

    public function getJobStatus(Request $request)
    {
        $status = 'pending';
        $tracking = JobTracking::where('user_id', user()->id ?? auth()->id())->latest()->first();

        if (!$tracking) {
            return response()->json(['status' => 'complete']);
        }

        return response()->json(['status' => $status]);
    }
}
