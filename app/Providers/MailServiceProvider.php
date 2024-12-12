<?php

namespace App\Providers;

use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Setting;
class MailServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * return void
     */
    public function register()
    {
        if (Schema::hasTable('settings')) {
            $config = [
                'driver' => 'SMTP',
                'host' =>Setting::get('MAIL_HOST'),
                'port' =>Setting::get('MAIL_PORT'),
                'encryption' =>Setting::get('MAIL_ENCRYPTION'),
                'from' => [
                    'address' =>Setting::get('MAIL_USERNAME'),
                    'name' =>Setting::get('MAIL_FROM_NAME')
                ],
                'password' =>Setting::get('MAIL_PASSWORD')
            ];
            Config::set('mail.mailers.smtp', $config);
        }
    }

    
    // public function boot()
    // {
    //     if (Schema::hasTable('settings')) {
    //         $config = [
    //             'driver' => 'SMTP',
    //             'host' =>Setting::get('MAIL_HOST'),
    //             'port' =>Setting::get('MAIL_PORT'),
    //             'encryption' =>Setting::get('MAIL_ENCRYPTION'),
    //             'from' => [
    //                 'address' =>Setting::get('MAIL_USERNAME'),
    //                 'name' =>Setting::get('MAIL_FROM_NAME')
    //             ],
    //             'password' =>Setting::get('MAIL_PASSWORD')
    //         ];
    //         Config::set('mail.mailers.smtp', $config);
    //     }

    // }
    /**
     * Bootstrap any application services.
     *
     * return void
     */
    public function resetConfig()
    {
        if (Schema::hasTable('settings')) {
            $config = [
                'driver' => 'SMTP',
                'host' =>Setting::get('MAIL_HOST'),
                'port' =>Setting::get('MAIL_PORT'),
                'encryption' =>Setting::get('MAIL_ENCRYPTION'),
                'from' => [
                    'address' =>Setting::get('MAIL_USERNAME'),
                    'name' =>Setting::get('MAIL_FROM_NAME')
                ],
                'password' =>Setting::get('MAIL_PASSWORD')
            ];
            Config::set('mail.mailers.smtp', $config);
        }
    }

    public static function modifyMail($mail)
    {

            $config = [
                'driver' => 'SMTP',
                'host' => $mail->host,
                'port' => $mail->port,
                'from' => [
                    'address' => $mail->email,
                    'name' => $mail->name
                ],
                'encryption' => $mail->encryption_type,
                'username' => $mail->email,
                'password' => $mail->password,
                'sendmail' => '/usr/sbin/sendmail -bs',
                'markdown' => [
                    'theme' => 'default',
                    'paths' => [
                        resource_path('views/vendor/mail'),
                    ],
                ],
            ];
            Config::set('mail', $config);
    }

}
