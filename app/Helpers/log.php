<?php

use Illuminate\Http\Request;
use Modules\Setting\Service\RequestLogService;

function requestLog($request, $e)
{
    $startTime = microtime(true);
    $endTime = microtime(true);
    $responseTime = ($endTime - $startTime) * 1000;
    $status = null;
    if(method_exists('getStatisCode', $e))
    {
        $status = $e->getStatusCode();
    }elseif(isset($e->status))
    {
        if(in_array($e->status, [422]))
        {
            return null;
        }
        $status = $e->status;
    }
    app()->make(RequestLogService::class)->store(new Request([
        'user_id' => auth()->id() ?? null, // If authentication is used
        'ip_address' => $request->ip() ?? null,
        'method' => $request->method() ?? null,
        'url' => $request->fullUrl() ?? null,
        'request_body' => json_encode($request->all()) ?? null,
        'response_status' => $status,
        'response_time' => $responseTime,
        'user_agent' => $request->userAgent(),
        'referer' => $request->header('Referer'),
        'error' => $e->getMessage(),
    ]));
}

