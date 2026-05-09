<div x-data="{
    showDeleteModal: false,
    itemToDelete: null,
    confirmDelete(id) {
        this.itemToDelete = id;
        this.showDeleteModal = true;
    },
    deleteAction() {
        $wire.deletePurchase(this.itemToDelete);
        this.showDeleteModal = false;
    }
}">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Purchases') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if (session()->has('message'))
                    <div class="bg-green-100 font-medium mb-4 p-3 rounded text-green-700 text-sm">
                        {{ session('message') }}
                    </div>
                @endif

                <div class="mb-6 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <h3 class="text-lg font-bold text-gray-700 hidden sm:block">Purchase List</h3>

                    <div class="flex items-center gap-4 w-full sm:w-auto">

                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('purchases.create') }}"
                                class="whitespace-nowrap px-4 py-2 bg-gray-800 text-white font-medium rounded-md hover:bg-gray-700 transition">
                                Add Purchase <i class="fa-solid fa-plus ml-1"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 w-10"></th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Sr No</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total</th>
                                @if (auth()->user()->isAdmin())
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions</th>
                                @endif
                            </tr>
                        </thead>

                        @foreach ($purchases as $index => $purchase)
                            <tbody x-data="{ expanded: false }" class="bg-white divide-y divide-gray-200">
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-gray-500 cursor-pointer"
                                        @click="expanded = !expanded">
                                        <i class="fa-solid transition-transform duration-200"
                                            :class="expanded ? 'fa-chevron-down' : 'fa-chevron-right'"></i>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $purchase->created_at->format('M d, Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                        ${{ number_format($purchase->total, 2) }}</td>
                                    @if (auth()->user()->isAdmin())
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('purchases.edit', $purchase->id) }}"
                                                class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>
                                            <button type="button" @click="confirmDelete({{ $purchase->id }})"
                                                class="text-red-600 hover:text-red-900">
                                                <i class="fa-solid fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    @endif
                                </tr>
                                <tr x-show="expanded" style="display: none;" x-transition>
                                    <td colspan="{{ auth()->user()->isAdmin() ? 5 : 4 }}" class="px-8 py-4 bg-gray-50">
                                        <div
                                            class="rounded-lg border border-gray-200 bg-white overflow-hidden shadow-sm">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-100">
                                                    <tr>
                                                        <th
                                                            class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                            Item
                                                        </th>
                                                        <th
                                                            class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                            Brand
                                                        </th>
                                                        <th
                                                            class="px-4 py-2 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                            Qty
                                                        </th>
                                                        <th
                                                            class="px-4 py-2 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                            Price
                                                        </th>
                                                        <th
                                                            class="px-4 py-2 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                            Subtotal</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-100 bg-white">
                                                    @foreach ($purchase->purchaseItems as $pItem)
                                                        <tr class="hover:bg-gray-50">
                                                            <td class="px-4 py-2 text-sm text-gray-800">
                                                                {{ $pItem->item->name ?? '-' }}</td>
                                                            <td class="px-4 py-2 text-sm text-gray-800">
                                                                {{ $pItem->brand->name ?? '-' }}</td>
                                                            <td class="px-4 py-2 text-sm text-gray-600 text-right">
                                                                {{ $pItem->qty }}</td>
                                                            <td class="px-4 py-2 text-sm text-gray-600 text-right">
                                                                ${{ number_format($pItem->price, 2) }}</td>
                                                            <td
                                                                class="px-4 py-2 text-sm font-medium text-gray-900 text-right">
                                                                ${{ number_format($pItem->qty * $pItem->price, 2) }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        @endforeach

                        @if ($purchases->isEmpty())
                            <tbody class="bg-white">
                                <tr>
                                    <td colspan="{{ auth()->user()->isAdmin() ? 5 : 4 }}"
                                        class="px-6 py-4 text-center text-sm text-gray-500">No purchases found.</td>
                                </tr>
                            </tbody>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>

    <x-delete-modal title="Delete Purchase"
        message="Are you sure you want to delete this purchase? All associated items will be permanently removed. This action cannot be undone." />
</div>
