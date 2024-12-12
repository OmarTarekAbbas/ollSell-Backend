<?php

namespace Modules\Basic\Actions\Export;

use Illuminate\Http\Request;

class DownloadAction
{
    protected $request;

    /**
     * Create a new Repository instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    public function execute()
    {
        $filename = str_replace('/', DIRECTORY_SEPARATOR, $this->request->filename);
        $path = storage_path('app' . DIRECTORY_SEPARATOR . $filename);

        if (file_exists($path)) {
            return response()->download($path);
        }

        abort(404);
    }
}
