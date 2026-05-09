<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Panel') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto p-6 bg-white rounded-xl shadow-md">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Admin Panel</h2>

            @if (session()->has('status'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="p-6 border rounded-lg bg-gray-50 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">Legacy Data Migration</h3>
                    <p class="text-sm text-gray-500">Run the migration to import legacy purchases.</p>
                </div>
                <button wire:click="runMigration"
                    class="px-4 py-2 bg-gray-800 text-white font-medium rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 shadow-md transition">
                    <i class="fa-solid fa-play mr-1"></i> Run Migration
                </button>
            </div>
        </div>
    </div>
</div>
