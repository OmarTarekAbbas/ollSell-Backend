<?php

namespace Modules\Order\Service;

use Modules\Order\Enums\OrderEnum;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\FollowUp;
use Modules\Basic\Service\BasicService;
use Modules\Order\Repositories\FollowUpRepository;

class FollowUpService extends BasicService
{
    protected $repo;

    public function __construct(FollowUpRepository $repository)
    {
        $this->repo = $repository;
    }


    public function getOrdersWithFollowup()
    {
        return Order::whereNotNull('follow_order')
            ->where('status_id', '!=', OrderEnum::COMPLETED_STATUS)
            ->get();
    }

    public function getFollowUps($id)
    {
        $followUps = FollowUp::where('order_id', $id)->with('user:id,name,email')->latest()->get();

        // if (!$followUps->count() && request('allow')) {
        //     $this->initiateFollowUp($id);

        //     $followUps = FollowUp::where('order_id', $id)->with('user:id,name,email')->latest()->get();
        // }

        return $followUps->sortByDesc('activity_date')->values()->all();
    }

    public function saveFollowUp($validatedData, $id)
    {
        $validatedData['order_id'] = $id;
        $validatedData['user_id'] = user()->id;

        $followUps = FollowUp::where('order_id', $id)->count();

        if (!$followUps) {
            $this->initiateFollowUp($id);
        }

        return FollowUp::create($validatedData);
    }

    private function initiateFollowUp($id)
    {
        $firstFollowUp = new FollowUp();
        $firstFollowUp->title = 'Follow-up Initiated';
        $firstFollowUp->content = 'Follow-up Initiated';
        $firstFollowUp->activity_type = 'Initiated';
        $firstFollowUp->order_id = $id;
        $firstFollowUp->user_id = user()->id;
        $firstFollowUp->save();
    }
}
