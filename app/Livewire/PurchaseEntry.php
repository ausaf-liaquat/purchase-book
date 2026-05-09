<?php
namespace App\Livewire;

use App\Models\Brand;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class PurchaseEntry extends Component
{
    #[Layout('layouts.app')]
    #[Title('Purchase Entry')]

    public ?Purchase $purchaseRecord = null;

    public array $rows = [];

    public function mount(Purchase $purchase)
    {
        if ($purchase && $purchase->exists) {
            $this->purchaseRecord = $purchase->load('purchaseItems');
            $this->rows           = $purchase->purchaseItems->map(function ($item) {
                return [
                    'id'       => uniqid(),
                    'item_id'  => $item->item_id,
                    'brand_id' => $item->brand_id,
                    'qty'      => $item->qty,
                    'price'    => $item->price,
                ];
            })->toArray();
        } else {
            $this->rows = [
                ['id' => uniqid(), 'item_id' => '', 'brand_id' => '', 'qty' => 1, 'price' => 0],
            ];
        }
    }

    #[Computed]
    public function itemsList()
    {
        return Item::select('id', 'name')->orderBy('name')->get();
    }

    #[Computed]
    public function brandsList()
    {
        return Brand::select('id', 'name')->orderBy('name')->get();
    }

    #[Computed]
    public function total()
    {
        return collect($this->rows)->reduce(function ($carry, $row) {
            $qty   = is_numeric($row['qty']) ? (float) $row['qty'] : 0;
            $price = is_numeric($row['price']) ? (float) $row['price'] : 0;
            return $carry + ($qty * $price);
        }, 0);
    }

    public function rules()
    {
        return [
            'rows'            => ['required', 'array', 'min:1'],
            'rows.*.item_id'  => ['bail', 'required', 'exists:items,id'],
            'rows.*.brand_id' => ['bail', 'required', 'exists:brands,id'],
            'rows.*.qty'      => ['bail', 'required', 'numeric', 'min:1'],
            'rows.*.price'    => ['bail', 'required', 'numeric', 'min:0'],
        ];
    }

    public function validationAttributes()
    {
        return [
            'rows.*.item_id'  => 'Item',
            'rows.*.brand_id' => 'Brand',
            'rows.*.qty'      => 'Quantity',
            'rows.*.price'    => 'Price',
        ];
    }

    public function addRow()
    {
        $this->rows[] = ['id' => uniqid(), 'item_id' => '', 'brand_id' => '', 'qty' => 1, 'price' => 0];
    }

    public function removeRow($rowId)
    {
        if (count($this->rows) > 1) {
            $this->rows = array_filter($this->rows, fn($row) => $row['id'] !== $rowId);
            $this->rows = array_values($this->rows);
        }
    }

    public function save()
    {
        $this->validate();

        $combinations = [];
        foreach ($this->rows as $index => $row) {
            $key = $row['item_id'] . '-' . $row['brand_id'];
            if (in_array($key, $combinations)) {
                $this->addError('rows.' . $index . '.item_id', 'Duplicate item and brand combination.');
                return;
            }
            $combinations[] = $key;
        }

        DB::transaction(function () {
            if ($this->purchaseRecord && $this->purchaseRecord->exists) {
                $purchase = $this->purchaseRecord;
                $purchase->update(['total' => $this->total()]);
                $purchase->purchaseItems()->delete();
            } else {
                $purchase = Purchase::create([
                    'total' => $this->total(),
                ]);
            }

            foreach ($this->rows as $row) {
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'item_id'     => $row['item_id'],
                    'brand_id'    => $row['brand_id'],
                    'qty'         => $row['qty'],
                    'price'       => $row['price'],
                ]);
            }
        });

        session()->flash('message', $this->purchaseRecord ? 'Purchase updated successfully.' : 'Purchase created successfully.');

        return $this->redirect(route('purchases.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.purchase-entry');
    }
}
