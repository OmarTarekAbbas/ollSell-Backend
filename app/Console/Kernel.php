<?php

namespace App\Console;

use Modules\Basic\Console\GenerateSeeders;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        GenerateSeeders::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('easy_order:failed');
        $schedule->command('app:synchronize-product-quantities');
        $schedule->command('notify:TransferProfitToTheProfitBallance');
        $schedule->command('telescope:prune --hours=27');
    }

    /**
     * Register the commands for the application.
     *
     * return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
