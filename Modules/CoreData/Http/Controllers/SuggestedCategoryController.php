<?php

namespace Modules\CoreData\Http\Controllers;

use Illuminate\Http\Request;
use Modules\CoreData\Entities\Category;
use Modules\CoreData\Service\CategoryService;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\CoreData\Http\Requests\Category\ApproveSuggestRequest;

class SuggestedCategoryController extends BasicController
{
    protected CategoryService $service;

    /**
     * This function constructs a CategoryService object and sets middleware permissions for various
     * category-related actions.
     *
     * param CategoryService Service The  parameter is an instance of the CategoryService
     * class, which is likely responsible for handling business logic related to categories in the
     * application. It is being injected into the constructor using dependency injection.
     */
    public function __construct(CategoryService $Service)
    {
        $this->service = $Service;
    }

    public function listCategoriesSupplier(Request $request)
    {
        $categories = $this->service->listCategoriesSupplier($request);

        if ($request->ajax()) {
            return view(
                'coredata::category.table',
                compact('categories', 'request')
            );
        }

        return $this->getDashboardView('coredata::category.suggested', compact('categories'));
    }

    public function showSuggestedCategory(Request $request, $id)
    {
        //todo change
        $data = Category::find($id);

        $category = Category::where('id', '!=', $id)
            ->where(function ($query) use ($id) {
                $query->where('parent_id', '!=', $id)
                    ->orWhereNull('parent_id');
            })
            ->where('isApproved', 1)
            ->get();

        return $this->getDashboardView('coredata::category.suggestedShow', compact('data', 'category'));
    }

    public function storeSuggested(ApproveSuggestRequest $request, $id)
    {
        $data = $this->service->storeSuggested($request, $id);

        if ($data) {
            return redirect(route('suggestedCategories.listCategoriesSupplier'))->with('Done');
        }
        return redirect(route('category.suggestedShow', $id))->with('problem');
    }

    public function rejectCategoriesSupplier(Request $request)
    {
        $data = $this->service->rejectCategoriesSupplier($request, request('categoryId'));

        if($data) {
            return redirect(route('suggestedCategories.listCategoriesSupplier'))->with("message", 'Done');
        }

        return redirect(route('suggestedCategories.listCategoriesSupplier'))->with('problem');
    }
}
