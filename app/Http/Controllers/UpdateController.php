<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class UpdateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the update page.
     */
    public function index()
    {
        $pendingMigrations = $this->getPendingMigrations();
        $lastMigration = SystemSetting::get('last_migration');

        return view('update.index', compact('pendingMigrations', 'lastMigration'));
    }

    /**
     * Execute migrations.
     */
    public function migrate(Request $request)
    {
        try {
            // Capture the output
            Artisan::call('migrate', ['--force' => true]);
            $output = Artisan::output();

            // Update last migration timestamp
            SystemSetting::set('last_migration', now()->toDateTimeString());

            return redirect()->back()
                ->with('success', '迁移执行成功！')
                ->with('output', $output);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', '迁移失败：' . $e->getMessage());
        }
    }

    /**
     * Rollback migrations.
     */
    public function rollback(Request $request)
    {
        try {
            Artisan::call('migrate:rollback', ['--force' => true]);
            $output = Artisan::output();

            return redirect()->back()
                ->with('success', '回滚成功！')
                ->with('output', $output);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', '回滚失败：' . $e->getMessage());
        }
    }

    /**
     * Show migration status.
     */
    public function status()
    {
        try {
            Artisan::call('migrate:status');
            $output = Artisan::output();

            return response()->json([
                'success' => true,
                'output' => $output,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get pending migrations.
     */
    private function getPendingMigrations()
    {
        $migrationPath = database_path('migrations');
        $allMigrations = collect(File::files($migrationPath))
            ->map(function ($file) {
                return $file->getFilename();
            })
            ->sort()
            ->values();

        try {
            $ranMigrations = DB::table('migrations')->pluck('migration');
            return $allMigrations->filter(function ($migration) use ($ranMigrations) {
                $migrationName = str_replace('.php', '', $migration);
                return !$ranMigrations->contains($migrationName);
            })->values();
        } catch (\Exception $e) {
            return $allMigrations;
        }
    }
}
