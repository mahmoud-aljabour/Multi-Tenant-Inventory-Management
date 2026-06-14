<?php

namespace App\Providers;

use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\PermissionRegistrar;

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
        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(6)->by($request->ip());
        });

        Scramble::configure()
            ->withDocumentTransformers(function (OpenApi $openApi) {
                $openApi->secure(
                    SecurityScheme::http('bearer', 'Sanctum')
                );

                $openApi->info->title = 'Multi-Tenant Inventory API';
                $openApi->info->description = 'REST API for multi-tenant inventory management with Sanctum authentication and role-based access control.';
                $openApi->info->version = '1.0.0';
            });

        app(PermissionRegistrar::class)->teams = true;
        if (Auth::check()) {
            app(PermissionRegistrar::class)->setPermissionsTeamId(
                Auth::user()->tenant_id
            );
        }
    }
}
