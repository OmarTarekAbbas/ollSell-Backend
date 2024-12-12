<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class NotUsedBeforeSegmentation implements ValidationRule
{
    protected $id;

    /**
     * Run the validation rule.
     *
     */
    public function __construct($id = null)
    {
        $this->id = $id;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $check = $this->CheckInDataBase($this->id);
        if ($check['status'] == false) {
            $fail($check['message']);
        }
    }

    public function CheckInDataBase($id = null)
    {
        $from = request('from');
        $to = request('to');
        $dropshipper_segmentation = DB::table('dropshipper_segmentation');

        if ($id) {

            $dropshipper_segmentation = $dropshipper_segmentation->where('id', "==", $id);
        }


        $dropshipper_segmentation =  $dropshipper_segmentation->where('from', '<=', $from)->where('to', '>=', $from)->orwhere(function ($query) use ($from, $to) {
            $query->where('from', '<=', $to)->where('to', '>=', $to);
        })->orwhere(function ($query) use ($from, $to) {
            $query->where('to', '>=', $from)->where('to', '<=', $to);
        });
        if ($id) {

            $dropshipper_segmentation =  DB::table('dropshipper_segmentation')->where('id', $id)->first();
        }else{
            $dropshipper_segmentation = $dropshipper_segmentation->first();
        }


        if (@$dropshipper_segmentation->id == $id && !empty($id)) {
            $dropshipper_segmentation_max = DB::table('dropshipper_segmentation')->where('from', '>', $to)->where('to', '>', $to)
                ->where('from', '>', $from)
                ->where('to', '>', $from)
                ->where('id', "!=", $id)->orderBy('from', 'desc')->first();



            $dropshipper_segmentation_main = DB::table('dropshipper_segmentation')->where('from', '<', $to)->where('to', '<', $to)
                ->where('from', '<', $from)
                ->where('to', '<', $from)
                ->where('id', "!=", $id)->orderBy('from', 'desc')->first();




            if ($dropshipper_segmentation_main && $dropshipper_segmentation_max) {
               
                if ($to + 1 > $dropshipper_segmentation_max->from && $from - 1 > $dropshipper_segmentation_main->from) {
              
                    $dropshipper_segmentation = 1;
                } else {
                    $dropshipper_segmentation_max = DB::table('dropshipper_segmentation')->where('from', '>=', @$dropshipper_segmentation->to)
                    ->where('id', "!=", $id)->orderBy('from', 'asc')->first();

                    if ($to + 1 > @$dropshipper_segmentation_max->from) {

                        $dropshipper_segmentation = 1;
                    } else {
                        $dropshipper_segmentation_main = DB::table('dropshipper_segmentation')
                        ->where('id', "!=", $id)->where('to', "<", $from)->orderBy('from', 'desc')->first();
                           $from=$from - 1;
                   
                        if ($from  < @$dropshipper_segmentation_main->to) {
                     
                            $dropshipper_segmentation = 1;
                        }else{
                           
                            $dropshipper_segmentation = 0;
                        }
                      
                    }
                }
            } elseif ($dropshipper_segmentation_main) {
               
                $dropshipper_segmentation_main = DB::table('dropshipper_segmentation')
                ->where('id', "!=", $id)->orderBy('from', 'desc')->first();
            
                if ($from - 1 < @$dropshipper_segmentation_main->to) {

                    $dropshipper_segmentation = 1;
                } else {

                    $dropshipper_segmentation = 0;
                }
            } elseif ($dropshipper_segmentation_max) {

                $dropshipper_segmentation_max = DB::table('dropshipper_segmentation')->where('from', '>', $from)
                    ->where('id', "!=", $id)->orderBy('from', 'asc')->first();

                if ($dropshipper_segmentation_max) {
                    if ($to + 1 > @$dropshipper_segmentation_max->from) {
                        $dropshipper_segmentation = 1;
                    } else {

                        $dropshipper_segmentation = 0;
                    }
                } else {
                    $dropshipper_segmentation = 0;
                }
            }
        }


        if ($dropshipper_segmentation) {
            return ['status' => false, 'message' => 'This from or to is already used'];
        }

        return ['status' => true];
    }
}