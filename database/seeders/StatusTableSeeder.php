<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\CoreData\Entities\Status;

class StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'is_report' => 1,
                'status' => 1,
                'name' => ['ar' => "جديد", 'en' => "new"],
            ],
            [
                'id' => 2,
                'is_report' => 1,
                'status' => 1,
                'name' => ['ar' => 'قيد الانتظار', 'en' => "pending"],
            ],
            [
                'id' => 3,
                'is_report' => 0,
                'status' => 1,
                'name' => ['ar' => 'مرفوض', 'en' => "rejected"],
            ],
            [
                'id' => 4,
                'is_report' => 1,
                'status' => 1,
                'name' => ['ar' => 'تم التوصيل', 'en' => "delivered"],
            ],
            [
                'id' => 5,
                'is_report' => 0,
                'status' => 1,
                'name' => ['ar' => 'تم الإلغاء', 'en' => "Cancelled"],
            ],
            [
                'id' => 6,
                'is_report' => 0,
                'status' => 1,
                'name' => ['ar' => "طلب استرداد او تبديل", 'en' => "refundOrReplacementRequested"],
            ],
            [
                'id' => 7,
                'is_report' => 0,
                'status' => 1,
                'name' => ['ar' => "العمل على الاسترداد", 'en' => "refundProgressing"],
            ],
            [
                'id' => 8,
                'is_report' => 0,
                'status' => 1,
                'name' => ['ar' => "تم رفض طلب لاستبدال او الاسترداد", 'en' => "refundOrReplacementRejected"],
            ],
            [
                'id' => 9,
                'is_report' => 0,
                'status' => 1,
                'name' => ['ar' => "جارى العمل على الاستبدال", 'en' => "ReplacementProgressing"],
            ],
            [
                'id' => 10,
                'is_report' => 0,
                'status' => 1,
                'name' => ['ar' => 'في عملية الشحن', 'en' => "shipping"],
            ],
            [
                'id' => 11,
                'is_report' => 0,
                'status' => 1,
                'name' => ['ar' => 'قيد الانتظار للدفع', 'en' => "payPending"],
            ],
            [
                'id' => 12,
                'is_report' => 0,
                'status' => 1,
                'name' => ['ar' => 'اعاده الرصيد', 'en' => "refundBalance"],
            ],
            [
                'id' => 13,
                'is_report' => 0,
                'status' => 1,
                'name' => ['ar' => 'جاري تجهيز الطلب', 'en' => "preparing"],
            ],
            [
                'id' => 14,
                'is_report' => 0,
                'status' => 1,
                'name' => ['ar' => 'جاهز', 'en' => "ready"],
            ],
            [
                'id' => 15,
                'is_report' => 0,
                'status' => 1,
                'name' => ['ar' => 'مرتجع', 'en' => "returned"],
            ],
            [
                'id' => 16,
                'is_report' => 0,
                'status' => 1,
                'name' => ['ar' => "استبدال", 'en' => "replacement"],
            ],
            [
                'id' => 17,
                'is_report' => 0,
                'status' => 1,
                'name' => ['ar' => "انتظار المخزون", 'en' => "pending inventory"],
            ],
            [
                'id' => 18,
                'is_report' => 0,
                'status' => 1,
                'name' => ['ar' => "انتظار المراجعة", 'en' => "on hold"],
            ],
            [
                'id' => 19,
                'is_report' => 0,
                'status' => 1,
                'name' => ['ar' => "مراجعه ماليه", 'en' => "RETURN BALANCE"],
            ],
        ];
        foreach ($data as $value) {
            $check = Status::find($value['id']);
            if (!$check) {
                $data = Status::create(['status' => 1]);
                foreach (language() as $lang) {
                    if (isset($value['name'][$lang->code])) {
                        $data->translation()
                            ->create(['key' => 'name', 'value' => $value['name'][$lang->code], 'language_id' => $lang->id]);
                    }
                }
            } else {
                foreach (language() as $lang) {
                    $translation = $check->translation->where('language_id', $lang->id)->where('key', 'name')->first();
                    if ($translation) {
                        $translation->update(['value' => $value['name'][$lang->code]]);
                    } elseif (!$translation && isset($value['name'][$lang->code])) {
                        $check->translation()
                            ->create(['key' => 'name', 'value' => $value['name'][$lang->code], 'language_id' => $lang->id]);
                    }
                }
            }
        }
        $status = Status::where('id', '>=', 20)->get();
        foreach ($status as $s) {
            $s->translation()->delete();
            $s->delete();
        }
    }
}
