<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class PurchaseIndex extends Component
{
    #[Layout('layouts.app')]
    #[Title('Purchases')]

    public $search = '';

    public function deletePurchase(Purchase $purchase)
    {
        if (Auth::user()->isAdmin()) {
            $purchase->purchaseItems()->delete();
            $purchase->delete();
            session()->flash('message', 'Purchase deleted successfully.');
        }
    }

    public function render()
    {
        $query = Purchase::with(['purchaseItems.item', 'purchaseItems.brand'])->latest();

        if ($this->search) {
            $query->where('total', 'like', "%{$this->search}%")
                  ->orWhere('created_at', 'like', "%{$this->search}%");
        }

        return view('livewire.purchase-index', [
            'purchases' => $query->get()
        ]);
    }
}
