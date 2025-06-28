<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DisableExpiredPlanWebsites implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Run in a day to keep validated already runing plans
     * @version
     * @author Utkarsh SHukla <email>
     */
     public function handle()
    {
        $users = User::with(['plans' => function($q) {
            $q->orderByDesc('pivot_start_date');
        }, 'websites'])->get();
        foreach ($users as $user) {
            $activePlan = $user->plans->first();
            if (!$activePlan || now()->greaterThan(optional($activePlan->pivot)->end_date)) {
                $user->websites()->update(['status' => false]);
                continue;
            }

            $limit = $activePlan->website_limit ?? 0;
            $websites = $user->websites()->orderBy('created_at')->get();

            // $count=0;
            foreach ($websites as $index => $website) {
                // disable the over limit applications
                $website->status = $index < $limit;
                $website->save();
            }
        }
    }
}
