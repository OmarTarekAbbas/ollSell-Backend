<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;
use Setting;
use Config;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * return void
     */
    public function register()
    {
        //
        config([
            'mail.mailers.smtp.transport' => setting('MAIL_MAILER'),
            'mail.mailers.smtp.host' => setting('MAIL_HOST'),
            'mail.mailers.smtp.port' => setting('MAIL_PORT'),
            'mail.mailers.smtp.username' => setting('MAIL_USERNAME'),
            'mail.mailers.smtp.password' => setting('MAIL_PASSWORD'),
            'mail.mailers.smtp.encryption' => setting('MAIL_ENCRYPTION'),
            'mail.from.address' => setting('MAIL_USERNAME'),
            'mail.from.name' => setting('MAIL_FROM_NAME'),


            'services.salla.base_url' => setting('SALLA_BASE_URL'),
            'services.salla.client_id' => setting('SALLA_OAUTH_CLIENT_ID'),
            'services.salla.client_secret' => setting('SALLA_OAUTH_CLIENT_SECRET'),
            'services.salla.redirect' => setting('SALLA_OAUTH_CLIENT_REDIRECT_URI'),
            'services.salla.webhook_secret' => setting('SALLA_WEBHOOK_SECRET'),
            'services.salla.authorization_mode' => setting('SALLA_AUTHORIZATION_MODE'),


            'services.ollops.environment' => setting('OLLOPS_ENV'),
            'services.ollops.base_url' => setting('OLLOPS_BASE_URL'),
            'services.ollops.live_api_key' => setting('OLLOPS_API_KEY'),
            'services.ollops.dev_api_key' => setting('OLLOPS_API_KEY'),

            'services.aymakan.environment' => setting('AYMAKAN_ENV'),
            'services.aymakan.base_url' => setting('AYMAKAN_BASE_URL'),
            'services.aymakan.live_api_key' => setting('AYMAKAN_API_KEY'),
            'services.aymakan.dev_api_key' => setting('AYMAKAN_API_KEY'),
            'services.aymakan.AYMKAN_DEBUG' => setting('AYMKAN_DEBUG'),
            'services.aymakan.AYMAKAN_API_URL' => setting('AYMAKAN_API_URL'),
            'services.aymakan.AYMAKAN_SECRET_API_KEY' => setting('AYMAKAN_SECRET_API_KEY'),

        ]);
    }

    /**
     * Bootstrap any application services.
     *
     * return void
     */
    public function boot()
    {

        Schema::defaultStringLength(191);
        Blade::if('permission', function ($expression) {
            return in_array($expression, user()->role->role->permissions->pluck('name', 'name')->toArray());
        });
        Paginator::useBootstrap();
    }
}
