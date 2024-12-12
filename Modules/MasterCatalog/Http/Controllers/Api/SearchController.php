<?php

namespace Modules\MasterCatalog\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



/**
 * @group Product management
 *
 * APIs for managing products
 */
class SearchController extends Controller
{


    public function index(Request $request)
    {
        $data = DB::table('products');
        $data = $data->select('*');

        $data = $data->
        where('isApproved',1)->
        whereRaw('1 = 1');

        if (request()->has('search') && !empty(request()->search)) {
            $data = $data->where('id', 'like', '%' . request()->search . '%')
                ->orWhere('sku', 'like', '%' . request()->search . '%');
        }
        $data = $data->get();
        $request->merge(['isApproved' => 1, 'orderBy' => ['column' => 'quantity', 'order' => 'desc']]);
        $array = [
            'data' => $data,
     
        ];
        return response($array);
    
    }
}
