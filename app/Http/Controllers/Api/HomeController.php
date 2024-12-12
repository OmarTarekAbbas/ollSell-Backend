<?php

namespace App\Http\Controllers\Api;

use Maatwebsite\Excel\Facades\Excel;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\MasterCatalog\Exports\Favorite\FavoriteExport;

class HomeController extends BasicController
{
    //todo why this here magad
    /**
     * The function is called export, it takes a request as a parameter, and it returns an Excel file
     * called favoriteProducts.xlsx
     *
     * param Request request The request object.
     *
     * return The export function is returning an Excel file.
     */
    public function export()
    {
        return Excel::download(new FavoriteExport,  'favoriteProducts.xlsx');
    }
}
