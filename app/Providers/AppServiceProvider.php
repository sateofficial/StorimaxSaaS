<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;

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
        // ── Auto-seed jika database masih kosong (hanya local) ──
        if ($this->app->environment('local')) {
            try {
                if (!User::where('email', 'admin@storimax.id')->exists()) {
                    Artisan::call('db:seed', ['--force' => true]);
                }

                // Pastikan symlink storage ada
                if (!file_exists(public_path('storage'))) {
                    Artisan::call('storage:link', ['--force' => true]);
                }

                // Pastikan folder avatars ada
                $avatarsPath = storage_path('app/public/avatars');
                if (!is_dir($avatarsPath)) {
                    mkdir($avatarsPath, 0755, true);
                }
            } catch (\Throwable $e) {
                // Abaikan error — mungkin tabel belum ada
            }
        }
    }
}
