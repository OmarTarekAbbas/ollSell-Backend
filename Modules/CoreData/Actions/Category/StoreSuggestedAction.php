<?php

namespace Modules\CoreData\Actions\Category;

use Illuminate\Http\Request;
use Modules\CoreData\Repositories\CategoryRepository;

class StoreSuggestedAction
{
    protected Request $request;
    protected int $id;
    /**
     * Create a new Repository instance.
     *
     * @return void
     */
    public function __construct(Request $request,$id)
    {
        $this->request=$request;
        $this->id=$id;
    }

    /**
     * This function executes an order by calculating the total quantity, total price, total VAT, cost
     * price, weight, and shipping fees, and then saves the order data.
     * 
     * param Request request The  parameter is an instance of the Request class, which is
     * typically used to retrieve data from the HTTP request. It contains information such as the
     * request method, headers, and input data.
     * 
     * @return a boolean value. If the data is successfully saved, it will return true. Otherwise, it
     * will return false.
     */
    public function execute()
    {
        $category = Category::find($this->id);

        $category->update([
            'isApproved' => 1,
        ]);

        return App(CategoryRepository::class)->save($this->request,$this->id);

        return true;
    }
}
