<?php

namespace App\Providers;

use App\Models\CreativityType;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View as ViewInstance;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function (ViewInstance $view): void {
            try {
                $types = Schema::hasTable('creativity_types')
                    ? CreativityType::query()->orderBy('title')->get()
                    : collect();
            } catch (QueryException) {
                $types = collect();
            }

            $view->with('menuCreativityTypes', $types);
        });
    }
}
