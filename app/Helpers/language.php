<?php

use Illuminate\Support\Facades\App;
use Modules\CoreData\Service\LanguageService;
use Illuminate\Http\Request;

/**
 * @Target this file to make function to help about language for all system
 * @note can call it in all system
 */
/**
 * @throws Exception
 * @note cache this query 60*60*60
 * @result get all language in database
 */
function languageAll()
{
    return app()->make(LanguageService::class)->findBy(new Request());
}

/**
 * @result get locale from app file
 */
function languageLocale()
{
    return App::getLocale();
}
/**
 * @result getLocal lang as object
 */
function languageLocaleObject(){
    return app()->make(LanguageService::class)->findBy(new Request(['code'=>languageLocale()]))->first();
}

/**
 * @result get all language sort by order column in table
 * @throws Exception
 */
function language()
{
    return languageAll()->sortBy('order');
}


/**
 * @result get id language by code
 * @throws Exception
 */
function languageId()
{
    return languageAll()->where('code',languageLocale())->first()->id ?? "";
}
