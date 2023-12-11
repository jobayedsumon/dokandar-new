<?php

namespace App\Providers;

use App\Models\CustomerInvestment;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\CentralLogics\Helpers;

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

        try
        {
            $locked_in_investments = CustomerInvestment::where('redeemed_at', null)
                ->whereHas('package', function ($q) {
                    $q->where('type', 'locked-in');
                })->get();

            foreach ($locked_in_investments as $investment) {
                $created_at         = $investment->created_at;
                $duration_in_months = $investment->package->duration_in_months;
                $redeemable         = $created_at->addMonths($duration_in_months);
                if ($redeemable->isPast())
                {
                    $investment->redeemed_at = now();
                    $investment->save();
                }
            }

            Paginator::useBootstrap();
            foreach(Helpers::get_view_keys() as $key=>$value)
            {
                view()->share($key, $value);
            }
        }
        catch(\Exception $e)
        {

        }

    }
}
