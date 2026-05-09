<?php
namespace App\Livewire;

use App\Services\LegacyMigrationService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class AdminPanel extends Component
{
    #[Layout('layouts.app')]
    #[Title('Admin Panel')]
    
    public function runMigration(LegacyMigrationService $service)
    {
        $filePath = public_path('data/legacy_purchases.json');

        if (! file_exists($filePath)) {
            return back()->with(
                'error',
                'Legacy data file not found.'
            );
        }

        $jsonContent = file_get_contents($filePath);

        $legacyPurchases = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->with(
                'error',
                'Invalid JSON format.'
            );
        }

        if (! is_array($legacyPurchases) || empty($legacyPurchases)) {
            return back()->with(
                'error',
                'Legacy data must be a non-empty array.'
            );
        }

        try {

            $service->run($legacyPurchases);

            return back()->with(
                'status',
                'Legacy migration completed successfully!'
            );

        } catch (\Throwable $e) {

            return back()->with(
                'error',
                'Migration failed: ' . $e->getMessage()
            );
        }
    }

    public function render()
    {
        return view('livewire.admin-panel');
    }
}
