<?php

namespace App\Listeners;

use App\Models\Login_attempt;
use App\Events\LoguinAttemptEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;


class LoguinAttemptListener implements ShouldQueue
{
    use InteractsWithQueue;
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(LoguinAttemptEvent $event): void
    {
        $cacheKey = "login_attempt_{$event->email}_{$event->status}";

        if (Cache::has($cacheKey)) {
            return;
        }

        Cache::put($cacheKey, true, now()->addSeconds(10));


        Login_attempt::create(['user_id' => $event->id, 'email' => $event->email, 'ip_address' => $event->ip ,'user_agent' => $event->user_agent, 'status' => $event->status, 'created_at' => now()]);
    }
}
