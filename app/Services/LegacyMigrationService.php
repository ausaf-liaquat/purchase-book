<?php
namespace App\Services;

use App\Models\Brand;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Support\Facades\DB;

class LegacyMigrationService
{
    public function run(array $legacyPurchases)
    {
        DB::transaction(function () use ($legacyPurchases) {
            foreach ($legacyPurchases as $data) {
                $item  = Item::firstOrCreate(['name' => $data['item_name']]);
                $brand = Brand::firstOrCreate(['name' => $data['brand_name']]);

                $exists = PurchaseItem::
                    where(function ($query) use ($item, $brand) {
                    $query->where('item_id', $item->id)
                        ->where('brand_id', $brand->id);
                })->exists();

                if (! $exists) {
                    $total    = $data['qty'] * $data['price'];
                    $purchase = Purchase::create([
                        'total' => $total,
                    ]);

                    PurchaseItem::create([
                        'purchase_id' => $purchase->id,
                        'item_id'     => $item->id,
                        'brand_id'    => $brand->id,
                        'qty'         => $data['qty'],
                        'price'       => $data['price'],
                    ]);
                }
            }
        });
    }
}
