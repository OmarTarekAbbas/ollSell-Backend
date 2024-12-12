<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Setting\Entities\Setting;

class SettingLiveSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'uploading_products' => false,
            'shipping_fee' => 25,
            'vat_product' => 0.15,
            'vat_profit' => 0.15,
            'vat_suppler' => 0.15,
            'bundle_discount' => 20,
            'email_password' => 'Mariam2710',
            'validation_type' => 'automatic',
            'fake_number' => 5,
            'project_name' => 'Olldrop',
            'version' => '',
            'logo' => '',
            'return_policy_ar' => '',
            'return_policy_en' => '',
            'encryption' => '',
            'app_debug' => 'live',

            'CASH_ON_DELIVERY' => true,
            'ONLINE_METHOD' => true,
            'WALLET_METHOD' => true,

            'MAIL_MAILER' => 'smtp',
            'MAIL_HOST' => 'smtp.gmail.com',
            'MAIL_PORT' => '587',
            'MAIL_USERNAME' => 'olldrop@olldrop.com',
            'MAIL_PASSWORD' => 'wheobrchwgpdhriw',
            'MAIL_ENCRYPTION' => 'tls',
            'MAIL_FROM_ADDRESS' => 'olldrop@olldrop.com',
            'MAIL_FROM_NAME' => 'Olldrop',

            'SALLA_BASE_URL' => 'https://api.salla.dev/admin/v2',
            'SALLA_OAUTH_CLIENT_ID' => 'd9d9db86-8ea2-48f2-8229-986506b4d6aa',
            'SALLA_OAUTH_CLIENT_SECRET' => '5dcd4d5391662cc6699ec4d4c98e9b67',
            'SALLA_OAUTH_CLIENT_REDIRECT_URI' => 'https://beta.olldrop.com/dashboard/store',
            'SALLA_WEBHOOK_SECRET' => '07df9e7dfe7127b3775cb2a333a27901',
            'SALLA_AUTHORIZATION_MODE' => 'custom',

            'OLLOPS_BASE_URL' => 'https://api.ollops.com',
            'OLLOPS_APP_ID' => '663dd3a10b6d745b8638cb43',
            'VITE_OLLOPS_BASE_FRONT_URL' => 'https://app.ollops.com',
            'OLLOPS_ENV' => 'live',
            'OLLOPS_API_KEY' => 'b295912c8369f4b351bdcc7593608c0a10ab282895cb6abc37c0c55895ffe4ec5f22302cda04c47bf0367334c211a4cd',

            'AYMAKAN_ENV' => 'dev',
            'AYMAKAN_BASE_URL' => 'https://fulfillment.aymakan.net/api/v1/',
            'AYMAKAN_API_KEY' => 'GEPpGxeONxNtBGx4kZc5TWP7vtflBvUp',
            'AYMKAN_DEBUG' => 'false',
            'AYMAKAN_API_URL' => 'https://api.aymakan.net/v2',
            'AYMAKAN_SECRET_API_KEY' => '767278fb55db48b2620dc3bbde89b765-5c828234-3ce7-4987-9ec2-4cccc7817642-5de6ddf1a3d10d3b941f4134380b31da/657b12835fe346a0f950b0fcb06e0a11/51dbdd4d-5532-443a-950f-3a9664d743e5',

            //esay order
            'WEBHOOK_CLIENT_SECRET' => 'VzBJM0J3dVg5Ug==',


            'CLICKPAY_PAYMENTS_MODE' => 'LIVE',
            'CLICKPAY_PAYMENTS_CURRENCY' => 'SAR',
            'CLICKPAY_MERCHANT_ID' => 44597,
            'CLICKPAY_SERVER_KEY' => 'SWJNLWLBZR-JHTTTD2LGD-NJ9HWZW6WN',
            'CLICKPAY_CLIENT_KEY' => 'CDKMDK-TTGV6H-777DMB-B67B9G',
            'CLICKPAY_SECRET_KEY' => 'your_secret_key',
            'CLICKPAY_API_KEY' => 'your_api_key',
            'CLICKPAY_ENDPOINT' => 'https://secure.clickpay.com.sa/payment/request',

            'WMS_BASE_URL' => 'https://prodapi.omniful.com',
            'WMS_SECRET_KEY' => 'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJleHAiOjE3MzIxMzY0NjEsImp0aSI6ImM1M2RhZDMyLTU2YzEtNDEwYi04OGQ5LTU0ZGRlNGRhNTUzZSIsInVzZXJfZGV0YWlscyI6eyJTZWxsZXJTYWxlc0NoYW5uZWxJRCI6MzE4LCJTZWxsZXJJRCI6IjIxMSIsIlRlbmFudElEIjoiMSIsIlVzZXJJRCI6IjM4MSIsIlVzZXJOYW1lIjoiTW9oYW1tYWQgVGFsaGEifSwidG9rZW5fdHlwZSI6IkJlYXJlciJ9.MBljhmRny-xELeAEfksBWIh_5RpdDlDvw1SrnpV2ZM2gHTPDk3LfBv0jpae_i1NA019X6aC1-3lOwIMFULhEPXD-BOWcZdkwBPcZSMXQ7FZejj_Sb2L6WDCDbpLbyBbnrOzp9MGGBK2BQGWBsdBdbZKp7A25BmjCEUMnoyVVZoYRlcizlLkEcFJBflGFX35MM2hdp6kOCuZdpFyo4k8ujkD1aY1elt4J4xpDsYjwuDsquMO-QZcN6ooWtUni7LxZlHCnHrAbZuVgXUS26Rtf6DChoBUZQF36RyalTCUNjVgwwLASy5q2QPhTmDcOSfkJlS11SEA1TN8p9jGU1DrlMmk96M9NVeR5E8mpfQXRdzeXtRHmbf8xEWvA1TQ2yp5ipBiup_Nr1s1xKTRplDuu-yW1psVNoj21uWGsJGLassJoqMzhavhb_gW39u2SGv7UCpzKlQ6tju7ZbLHZ0dUGPKQQ-VZTC0M00dFwHP5qto_2-Z2Rb44A2gYqp_CVwYb-TJYznUfyJfNWh2Csr8qRsrCE6Op7cZmjJHut2UseDJt3mOzLYe1oMnYIy2nmzkKpl34QETji8Pm73SVWeEPvlwCgyzFCQvoI9XLWsjW9xT4etuDdJ1chK2dctkDbztnRhWPDE3-O0VsLGLoMcFFHsPUwFhO--VZ_LjbxY-0t6yY'
        ];

        foreach ($data as $index => $value) {
            Setting::updateOrCreate(
                ['key' => $index],    // The condition to check for an existing record
                ['value' => $value]    // The data to update if found, or insert if not found
            );
        }
    }
}
