<div x-data="{
    showDeleteModal: false,
    itemToDelete: null,
    confirmDelete(id) {
        this.itemToDelete = id;
        this.showDeleteModal = true;
    },
    deleteAction() {
        $wire.delete(this.itemToDelete);
        this.showDeleteModal = false;
    }
}">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Items') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="mb-4 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-700">Item List</h3>
                    <button wire:click="create"
                        class="px-4 py-2 bg-gray-800 text-white font-medium rounded-md hover:bg-gray-700 transition">Add
                        Item <i class="fa-solid fa-plus"></i></button>
                </div>

                @if (session()->has('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                        <span class="block font-medium sm:inline text-sm">{{ session('error') }}</span>
                    </div>
                @endif

                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Sr No</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Name</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($items as $index => $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button wire:click="edit({{ $item->id }})"
                                            class="text-indigo-600 hover:text-indigo-900 mr-3"><i
                                                class="fa-solid fa-pen"></i></button>
                                        <button type="button" @click="confirmDelete({{ $item->id }})"
                                            class="text-red-600 hover:text-red-900"><i
                                                class="fa-solid fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                            @if ($items->isEmpty())
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">No items
                                        found.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <x-form-modal :title="$editId ? 'Edit Item' : 'Add Item'" saveText="Save Item">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Item Name</label>
            <input type="text" id="name" wire:model="name"
                class="block w-full rounded-md border-gray-300 px-3 py-2 border shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            @error('name')
                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
            @enderror
        </div>
    </x-form-modal>

    <x-delete-modal title="Delete Item"
        message="Are you sure you want to delete this item? This action cannot be undone." />
</div>
