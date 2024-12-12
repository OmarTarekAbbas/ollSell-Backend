<?php

namespace Modules\Basic\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CoreData\Service\CityService;
use Modules\MasterCatalog\Service\AttributeService;
use Modules\Order\Entities\Order;
//todo change
class AjaxController extends Controller
{
    protected $cityService, $attributeService;

    public function __construct(CityService $cityService, AttributeService $attributeService)
    {
        $this->cityService = $cityService;
        $this->attributeService = $attributeService;
    }
    /**
     * Display a listing of the resource.
     * return Renderable
     */
    public function getCitiesBaedOnCountryId(Request $request)
    {
        $request->merge(['status' => activeType()['as']]);

        return $this->cityService->findBy($request);
    }

    public function getAttributesVariation(Request $request)
    {

        $attributes = $this->attributeService->findBy($request, moreConditionForFirstLevel: ['whereIn' => ['id' => $request['attributes']]]);
        $options = [];
        foreach($attributes as $attribute){
            $options[] =  $attribute->options;
        }
        $variations = $this->generateProductVariations($options);
        return view('basic::attributes_variation', get_defined_vars());
    }

    function generateProductVariations(array $arrays, $currentVariation = array(), $currentIndex = 0)
    {
        $variations = array();

        if ($currentIndex >= count($arrays)) {
            $variations[] = $currentVariation;
            return $variations;
        }

        $currentArray = $arrays[$currentIndex];
        foreach ($currentArray as $index => $attribute) {
            $newVariation = $currentVariation;
            $newVariation[$currentIndex]['name'] = $attribute->name;
            $newVariation[$currentIndex]['id'] = $attribute->id;
            $newVariation[$currentIndex]['attribute_id'] = $attribute->attribute_id;
            $variations = array_merge($variations, $this->generateProductVariations($arrays, $newVariation, $currentIndex + 1));
        }

        return $variations;
    }

    public function checkOrder(Request $request, $id)
    {
        $order = Order::find($id);

        if($order) return response()->json(['exists' => true]);

        return response()->json(['exists' => false]);
    }
}
