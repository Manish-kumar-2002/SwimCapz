<?php

namespace App\Console;

use App\Helpers\OrderHelper;
use App\Traits\TrackingTrait;
use App\Traits\ShippingTrait;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    use TrackingTrait, ShippingTrait;

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\CrudGenerator::class,
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {

            OrderHelper::stockNotifications(); //stock check

        })->everyMinute();

        $schedule->call(function () {

            $this->setCurrencyExchangeRate();
        })->everyFiveMinutes();
        
        $schedule->call(function () {

            $this->createTracking('111', '22');
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require_once base_path('routes/console.php');
    }
}
