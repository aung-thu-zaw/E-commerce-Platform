<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('brand:delete')->daily();
        $schedule->command('collection:delete')->daily();
        $schedule->command('category:delete')->daily();
        $schedule->command('product:delete')->daily();
        $schedule->command('coupon:delete')->daily();
        $schedule->command('slider_banner:delete')->daily();
        $schedule->command('campaign_banner:delete')->daily();
        $schedule->command('product_banner:delete')->daily();
        $schedule->command('country:delete')->daily();
        $schedule->command('region:delete')->daily();
        $schedule->command('city:delete')->daily();
        $schedule->command('township:delete')->daily();
        $schedule->command('language:delete')->daily();
        $schedule->command('product_review:delete')->daily();
        $schedule->command('shop_review:delete')->daily();
        $schedule->command('inactive_vendor:delete')->daily();
        $schedule->command('user:delete')->daily();
        $schedule->command('role:delete')->daily();
        $schedule->command('blog_category:delete')->daily();
        $schedule->command('blog_post:delete')->daily();
        $schedule->command('faq_category:delete')->daily();
        $schedule->command('faq_sub_category:delete')->daily();
        $schedule->command('faq:delete')->daily();
        $schedule->command('subscriber:delete')->daily();
        $schedule->command('suggestion:delete')->daily();
        $schedule->command('website_feedback:delete')->daily();
        $schedule->command('vendor_product_banner:delete')->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
