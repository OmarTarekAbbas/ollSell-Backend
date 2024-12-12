<?php

namespace Modules\Order\Exports\Order\Admin;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Modules\Order\Entities\AttemptsLog;
use Modules\Order\Repositories\AttemptsLogRepository;
use Illuminate\Support\Facades\App;

class AttemptsLogExport implements FromCollection, WithHeadings
{

    protected $repo;



    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Query to get the data
        $exportData = [];
        $request = request();

        if ($request->order_id || $request->fromDate || $request->toDate) {
            $moreConditionForFirstLevel = [];
            if ($request->fromDate && $request->toDate) {
                $moreConditionForFirstLevel += ['whereBetween' => ['created_at' => [Carbon::parse($request->fromDate)
                    ->startOfDay(), Carbon::parse($request->toDate)->endOfDay()]]];
            } elseif ($request->fromDate) {
                $moreConditionForFirstLevel += ['where' => ['created_at' => ['>=', Carbon::parse($request->fromDate)
                    ->startOfDay()]]];
            } elseif ($request->toDate) {
                $moreConditionForFirstLevel += ['where' => ['created_at' => ['<=', Carbon::parse($request->toDate)
                    ->endOfDay()]]];
            }
            $attemptsLogs = App::make(AttemptsLogRepository::class)->findBy(
                $request,
                moreConditionForFirstLevel: $moreConditionForFirstLevel,
                pagination: true,
                perPage: false,
                orderBy: ['column' => 'id', 'order' => 'desc']
            );
        } else {
            $attemptsLogs = AttemptsLog::orderBy('created_at', 'desc')->get();
        }
        
        foreach ($attemptsLogs as $d) {
            // Gather data for each order item
            $exportData[] = [
                'No.' => $d->id,
                'Timestamp' => $d->created_at->format('Y-m-d H:i:s'),
                'Order No.' => $d->order_id,
                'No.Attempte' => $d->attempts_count,
                'Validation Status' => $d->subStatus->name ?? $d->status->name->value,
                'Validation Remark' => ($d->remarks) ? $d->remarks->name : '----',
                'Validation First Attempt' => $d->first_validation ?? '----',
                'Validated At' => $d->validated_at ??  '----',
                'Last Edit' => $d->last_edit_order ??  '----',
                'Notes' => $d->notes ??  '----',

            ];
        }
        return collect($exportData);
    }

    /**
     * Define the headings for the columns
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'No.',
            'Timestamp',
            'Order No.',
            'No.Attempte',
            'Validation Status',
            'Validation Remark',
            'Validation First Attempt',
            'Validated At',
            'Last Edit',
            'Notes'
        ];
    }
}
