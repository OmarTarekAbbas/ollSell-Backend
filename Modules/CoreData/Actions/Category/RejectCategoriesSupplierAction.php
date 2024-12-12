<?php

namespace Modules\CoreData\Actions\Category;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\CoreData\Entities\Category;

class RejectCategoriesSupplierAction
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
        DB::beginTransaction();

        try {
            $category = Category::find($this->id);

            $title = json_encode([
                'en' => 'Category Rejected',
                'ar' => 'الفئة مرفوضة',
            ]);

            $content = json_encode([
                'en' =>  ' Category suggestion ` ' . $category->name->value . ' ` has been rejected for the following reason: ' . $this->request->reason,
                'ar' =>  'اقتراح الفئة'  . $category->name->value . ' ` تم رفضه للسبب التالي: ' . $this->request->reason,
            ]);


            $urlType = 'category';
            $urlId = $category->id;
            $color = '#FFD700';
            $supplier_id=$category->supplier_id;
   

            $category->delete();
            return array(
                'title'=>$title,
                'content'=>$content,
                'supplier_id'=>$supplier_id,
                'urlType'=>$urlType,
                'urlId'=>$urlId,
                'color'=>$color
               );

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollback();

            return false;
        }
    }
}
