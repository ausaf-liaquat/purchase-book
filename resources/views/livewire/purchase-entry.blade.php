<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            {{ $purchaseRecord ? 'Edit Purchase' : 'Add Purchase' }}
        </h2>
    </x-slot>

    <div class="py-10" x-data="{
        rows: @entangle('rows'),
        get calculateTotal() {
            return Object.values(this.rows).reduce((sum, row) => {
                let qty = parseFloat(row.qty) || 0;
                let price = parseFloat(row.price) || 0;
                return sum + (qty * price);
            }, 0);
        }
    }">
        <div class="max-w-5xl mx-auto bg-white shadow rounded-xl p-6">

            @if (session()->has('message'))
                <div class="mb-4 p-3 rounded bg-green-100 text-green-700">
                    {{ session('message') }}
                </div>
            @endif

            <form wire:submit.prevent="save">

                <div class="mb-4 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-700">Purchase Items</h3>
                    <button type="button" wire:click="addRow"
                        class="inline-flex items-center justify-center rounded-md bg-gray-800 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-700 transition focus:outline-none">
                        Add Row <i class="fa-solid fa-plus ml-2"></i>
                    </button>
                </div>

                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Item</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Brand</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                    Qty</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-40">
                                    Price</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                                    Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($rows as $index => $row)
                                <tr wire:key="row-{{ $row['id'] }}" class="hover:bg-gray-50">

                                    <td class="px-6 py-4 whitespace-nowrap align-top">
                                        <select wire:model="rows.{{ $index }}.item_id"
                                            class="block p-2 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            <option value="">Select</option>
                                            @foreach ($this->itemsList as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('rows.' . $index . '.item_id')
                                            <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap align-top">
                                        <select wire:model="rows.{{ $index }}.brand_id"
                                            class="block p-2 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            <option value="">Select</option>
                                            @foreach ($this->brandsList as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('rows.' . $index . '.brand_id')
                                            <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap align-top">
                                        <input type="number" min="1" wire:model.live="rows.{{ $index }}.qty"
                                            class="block p-2 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        @error('rows.' . $index . '.qty')
                                            <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap align-top">
                                        <input type="number" step="0.01" min="0" wire:model.live="rows.{{ $index }}.price"
                                            class="block p-2 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        @error('rows.' . $index . '.price')
                                            <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap align-top text-center">
                                        <button type="button" wire:click="removeRow({{ $row['id'] }})"
                                            class="text-red-600 hover:text-red-900 transition focus:outline-none mt-2">
                                            <i class="fa-solid fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 border-t border-gray-200">
                            <tr>
                                <td colspan="3"
                                    class="px-6 py-4 text-right text-sm font-bold text-gray-700 tracking-wider">
                                    Total Amount:
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    $<span x-text="calculateTotal.toFixed(2)"></span>
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-200 text-right">
                    <button type="submit"
                        class="inline-flex w-full justify-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 sm:ml-3 sm:w-auto">
                        {{ $purchaseRecord ? 'Update Purchase' : 'Save Purchase' }}
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
