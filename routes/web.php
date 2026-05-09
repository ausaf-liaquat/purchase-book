<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\PurchaseEntry;
use App\Livewire\PurchaseIndex;
use App\Livewire\AdminPanel;
use App\Livewire\Master\ItemIndex;
use App\Livewire\Master\BrandIndex;
use App\Http\Middleware\EnsureIsAdmin;

Route::get('/', function () {
    return view('welcome');
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');
require __DIR__ . '/auth.php';

Route::middleware(['auth'])->group(function () {
    Route::get('/purchases', PurchaseIndex::class)->name('purchases.index');
    
    Route::middleware([EnsureIsAdmin::class])->group(function () {
        Route::get('/purchases/create', PurchaseEntry::class)->name('purchases.create');
        Route::get('/purchases/{purchase}/edit', PurchaseEntry::class)->name('purchases.edit');
        Route::get('/admin/migration', AdminPanel::class)->name('admin.migration');
        
        Route::get('/master/items', ItemIndex::class)->name('master.items');
        Route::get('/master/brands', BrandIndex::class)->name('master.brands');
    });
});
