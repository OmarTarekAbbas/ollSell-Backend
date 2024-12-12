<?php
/**
 * @Target  this file to make enum for all system
 * @note can call it in all system if give it key we return only we send
 */
/**
 * @Target this all status for all system
 * @note 1 : active
 * @note 0 : un active
 */
function activeType()
{
    return ['as' => 1, 'us' => 0];
}

/**
 * @Target this all type media in system
 * avatar @uses  user , image
 * file @uses   user , ad , task
 * document @uses  ad , task , user
 */
function mediaType()
{
    return ['am' => 'avatar', 'fm' => 'file', 'dm' => 'document', 'lm' => 'logo', 'im' => 'image', 'th' => 'thumbnail'];
}

/**
 * @Target this path type we can save in it
 */
function pathType()
{
    return ['ip' => 'images', 'up' => 'uploads'];
}

function modelPermission()
{
    return [
        'users',
        'roles',
        "language",
        "dropshipper",
        'country',
        'city',
        'product',
        'target_market',
        "categories",
        "onboarding_categories",
        "status",
        "RedeemRequest",
        'state',
        'suppliers',
        'warehouse',
        'attribute',
        'events',
        'order',
        'report',
        'SubStatus',
        'dropshipper_segmentation',
        'shipping_companies',
        'shipping_company_city_times',
        'bundle',
        'shipping_company_vacation',
        'log',
        'setting',
        'withdrawal_request'
    ];
}

/**
 * It returns an array of size text.
 *
 * return An array with keys 1, 2, and 3.
 */
function sizeText($size)
{
    if($size === 1)
    {
        return 'L';
    }elseif($size === 2)
    {
        return 'M';
    }elseif($size === 3)
    {
        return 'XL';
    }else
    {
        return 'L';
    }
}

/**
 * It returns the currency symbol for the Saudi Riyal.
 */
function currency()
{
    return trans('orders.SAR');
}

if(!function_exists('get_days'))
{
    function get_days()
    {
        $days[1] = 'Saturday';
        $days[2] = 'Sunday';
        $days[3] = 'Monday';
        $days[4] = 'Tuesday';
        $days[5] = 'Wednesday';
        $days[6] = 'Thursday';
        $days[7] = 'Friday';
        return $days;
    }
}
if(!function_exists('getDay'))
{
    function getDay($key)
    {
        $days[1] = 'Saturday';
        $days[2] = 'Sunday';
        $days[3] = 'Monday';
        $days[4] = 'Tuesday';
        $days[5] = 'Wednesday';
        $days[6] = 'Thursday';
        $days[7] = 'Friday';
        return $days[$key];
    }
}


