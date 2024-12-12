<?php

namespace Modules\Setting\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Config;
use Illuminate\Support\Facades\Schema;

class SettingServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Setting';
    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'setting';

    /**
     * Boot the application events.
     *
     * return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
        if (Schema::hasTable('settings')) {
            $config = config('service');
            $config['payments']['ClickPayments'] = [
                'mode' =>  setting('CLICKPAY_PAYMENTS_MODE'),
                'currency' =>  setting('CLICKPAY_PAYMENTS_CURRENCY'),
                'data' => [
                    'live' => [
                        'url' =>  setting('CLICKPAY_LIVE_ENDPOINT'),
                        'profileId' =>  setting('CLICKPAY_LIVE_MERCHANT_ID'),
                        'serverKey' =>  setting('CLICKPAY_LIVE_SERVER_KEY'),
                        'clientKey' =>  setting('CLICKPAY_LIVE_CLIENT_KEY'),
                        'secretKey' =>  setting('CLICKPAY_LIVE_SECRET_KEY'),
                        'apiKey' =>  setting('CLICKPAY_LIVE_API_KEY'),
                    ],
                    'sandbox' => [
                        'url' =>  setting('CLICKPAY_LIVE_ENDPOINT'),
                        'profileId' =>  setting('CLICKPAY_SANDBOX_MERCHANT_ID'),
                        'serverKey' =>  setting('CLICKPAY_SANDBOX_SERVER_KEY'),
                        'clientKey' =>  setting('CLICKPAY_SANDBOX_CLIENT_KEY'),
                        'secretKey' =>  setting('CLICKPAY_SANDBOX_SECRET_KEY'),
                        'apiKey' =>  setting('CLICKPAY_SANDBOX_API_KEY'),
                    ],
                ]
            ];
            Config::set('service.payments.ClickPayments', $config['payments']['ClickPayments']);
            $config = config('service');
            $config['salla'] = [
                'base_url' => setting('SALLA_BASE_URL', 'https://api.salla.dev/admin/v2'),
                'client_id'          => setting('SALLA_OAUTH_CLIENT_ID'),
                'client_secret'      => setting('SALLA_OAUTH_CLIENT_SECRET'),
                'redirect'           => setting('SALLA_OAUTH_CLIENT_REDIRECT_URI'),
                'webhook_secret'     => setting('SALLA_WEBHOOK_SECRET'),
                'authorization_mode' => setting('SALLA_AUTHORIZATION_MODE', 'custom')
            ];
            Config::set('service.salla', $config['salla']);
            $config = config('service');
            $config['ollops'] = [
                'environment' =>  setting('OLLOPS_ENV', 'dev'),
                'base_url' =>  setting('OLLOPS_BASE_URL', 'http://localhost:8000'),
                'live_api_key' =>  setting('OLLOPS_API_KEY_LIVE'),
                'dev_api_key' =>  setting('OLLOPS_API_KEY_DEV'),
            ];
            Config::set('service.ollops', $config['ollops']);
            $config = config('service');
            $config['aymakan'] = [
                'environment' =>   setting('AYMAKAN_ENV', 'dev'),
                'base_url' =>  setting('AYMAKAN_BASE_URL', 'http://localhost:8000/'),
                'live_api_key' =>  setting('AYMAKAN_API_KEY_LIVE'),
                'dev_api_key' =>  setting('AYMAKAN_API_KEY_DEV'),
                'AYMKAN_DEBUG' =>  setting('AYMKAN_DEBUG'),
                'AYMAKAN_API_URL' =>  setting('AYMAKAN_API_URL'),
                'AYMAKAN_SECRET_API_KEY' =>  setting('AYMAKAN_SECRET_API_KEY'),
            ];
            Config::set('service.aymakan', $config['aymakan']);
            $config = config('service');
            $config['wms'] = [
                'base_url' =>  setting('WMS_BASE_URL', 'http://localhost:8000/'),
                'Secret_Key' =>  setting('WMS_SECRET_KEY', 'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJleHAiOjE3MzIxMzY0NjEsImp0aSI6ImM1M2RhZDMyLTU2YzEtNDEwYi04OGQ5LTU0ZGRlNGRhNTUzZSIsInVzZXJfZGV0YWlscyI6eyJTZWxsZXJTYWxlc0NoYW5uZWxJRCI6MzE4LCJTZWxsZXJJRCI6IjIxMSIsIlRlbmFudElEIjoiMSIsIlVzZXJJRCI6IjM4MSIsIlVzZXJOYW1lIjoiTW9oYW1tYWQgVGFsaGEifSwidG9rZW5fdHlwZSI6IkJlYXJlciJ9.MBljhmRny-xELeAEfksBWIh_5RpdDlDvw1SrnpV2ZM2gHTPDk3LfBv0jpae_i1NA019X6aC1-3lOwIMFULhEPXD-BOWcZdkwBPcZSMXQ7FZejj_Sb2L6WDCDbpLbyBbnrOzp9MGGBK2BQGWBsdBdbZKp7A25BmjCEUMnoyVVZoYRlcizlLkEcFJBflGFX35MM2hdp6kOCuZdpFyo4k8ujkD1aY1elt4J4xpDsYjwuDsquMO-QZcN6ooWtUni7LxZlHCnHrAbZuVgXUS26Rtf6DChoBUZQF36RyalTCUNjVgwwLASy5q2QPhTmDcOSfkJlS11SEA1TN8p9jGU1DrlMmk96M9NVeR5E8mpfQXRdzeXtRHmbf8xEWvA1TQ2yp5ipBiup_Nr1s1xKTRplDuu-yW1psVNoj21uWGsJGLassJoqMzhavhb_gW39u2SGv7UCpzKlQ6tju7ZbLHZ0dUGPKQQ-VZTC0M00dFwHP5qto_2-Z2Rb44A2gYqp_CVwYb-TJYznUfyJfNWh2Csr8qRsrCE6Op7cZmjJHut2UseDJt3mOzLYe1oMnYIy2nmzkKpl34QETji8Pm73SVWeEPvlwCgyzFCQvoI9XLWsjW9xT4etuDdJ1chK2dctkDbztnRhWPDE3-O0VsLGLoMcFFHsPUwFhO--VZ_LjbxY-0t6yY'),
            ];
            Config::set('service.wms', $config['wms']);
        }
    }

    /**
     * Register the service provider.
     *
     * return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register config.
     *
     * return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower . '.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'),
            $this->moduleNameLower
        );
    }

    /**
     * Register views.
     *
     * return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);
        $sourcePath = module_path($this->moduleName, 'Resources/views');
        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', $this->moduleNameLower . '-module-views']);
        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    /**
     * Register translations.
     *
     * return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);
        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
            $this->loadJsonTranslationsFrom($langPath, $this->moduleNameLower);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
            $this->loadJsonTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * return array
     */
    public function provides()
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (\Config::get('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }
        return $paths;
    }
}
