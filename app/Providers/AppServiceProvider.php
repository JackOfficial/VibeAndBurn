<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Queue;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use App\models\broadcast;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use App\Models\order;
use App\Models\User;
use App\Observers\OrderObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
               
        Queue::before(function (JobProcessing $event){
            // $event->connectionName;
            // $event->job;
            // $event->job->payload();
        });
        Schema::defaultStringLength(191);
        Queue::after(function (JobProcessed $event){
            // $event->connectionName;
            // $event->job;
            // $event->job->payload();
            
            // broadcast::where('id', $id)->update([
            //     'status' => 1
            // ]);
        });

        User::observe(\App\Observers\UserObserver::class);
    
         // New Order Observer
        order::observe(OrderObserver::class);
    }
}
