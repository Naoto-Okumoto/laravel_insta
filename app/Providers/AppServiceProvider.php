<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// ページ割り付けのために追記！
use Illuminate\Pagination\Paginator;
// 下の２つは、ミドルウェアのために追記！
use Illuminate\Support\Facades\Gate;
use App\Models\User;

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
        // ページ割り付けのために追記！
        Paginator::useBootstrap();

        // アドミン（ミドルウェア）のために追記！
        Gate::define('admin', function($user){
            return $user->role_id == User::ADMIN_ROLE_ID;
            // $user->role_id はアドミンのみ１
            //  ADMIN_ROLE_ID は１（constで定義済み）
        });
    }
}
