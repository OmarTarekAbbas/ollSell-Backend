<?php

namespace Modules\Order\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Order\Service\NoteService;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\Order\Entities\Note;
use Modules\Order\Entities\Order;
use Modules\Order\Http\Resources\Order\Admin\OrderResource;

class NoteController extends BasicController
{
    protected $service;


    public function __construct(NoteService $noteService)
    {
        $this->service = $noteService;
    }


    public function list(Request $request)
    {
        // return $this->apiResponse(
        //     data: $this->service->totalOrder($request),
        //     message:trans('orders.Order Refund Request sent!')
        // );
    }

    public function storeNote(Request $request, $id)
    {
        $order = Order::find($id);

        Note::create([
            'order_id' => $id,
            'content' => $request->content,
            'user_id' => user()->id ?? null
        ]);

        return response()->json(new OrderResource($order->refresh()));
    }

    public function update(Request $request, $id)
    {
        $note = Note::find($id);

        $note->update(['content' => $request->content,
            'user_id' => user()->id ?? null]);

        return response()->json(new OrderResource($note->order->refresh()));
    }

    public function destroy($id)
    {
        $note = Note::find($id);
        $order = $note->order;
        $note->delete();

        return response()->json(new OrderResource($order->refresh()));
    }
}
