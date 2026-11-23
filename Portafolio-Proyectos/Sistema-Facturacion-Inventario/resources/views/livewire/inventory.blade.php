<?php

use Livewire\Volt\Component;
use App\Models\Product;
use App\Models\Category;

new class extends Component
{
    public $showForm = false;
    public $name = '';
    public $sale_price = '';
    public $stock = '';

    public function save()
    {
        $category = Category::first();
        
        Product::create([
            'category_id' => $category->id,
            'name' => $this->name,
            'sale_price' => $this->sale_price,
            'stock' => $this->stock,
            'purchase_price' => 0,
            'min_stock' => 5,
        ]);

        $this->reset('name', 'sale_price', 'stock', 'showForm');
    }

    public function with()
    {
        return [
            'products' => Product::latest()->get()
        ];
    }
}; ?>

<div>
    <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow mt-10">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">📦 Inventario de Bodega</h1>
        
        <div class="mb-6">
            <button wire:click="$toggle('showForm')" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 transition">
                @if($showForm) Cancelar @else + Agregar Producto @endif
            </button>
        </div>

        @if($showForm)
            <div class="bg-gray-50 p-4 rounded mb-6 border">
                <h2 class="text-lg font-bold mb-4">Nuevo Producto</h2>
                <form wire:submit="save" class="flex gap-4 items-end">
                    <div class="flex-1">
                        <label class="block text-sm text-gray-600">Nombre</label>
                        <input type="text" wire:model="name" class="w-full border rounded p-2" required placeholder="Ej. Coca Cola 2L">
                    </div>
                    <div class="w-32">
                        <label class="block text-sm text-gray-600">Precio</label>
                        <input type="number" step="0.01" wire:model="sale_price" class="w-full border rounded p-2" required placeholder="0.00">
                    </div>
                    <div class="w-24">
                        <label class="block text-sm text-gray-600">Stock</label>
                        <input type="number" wire:model="stock" class="w-full border rounded p-2" required placeholder="0">
                    </div>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 h-10">
                        Guardar
                    </button>
                </form>
            </div>
        @endif

        <table class="w-full text-left border-collapse bg-white">
            <thead>
                <tr class="bg-gray-100 border-b">
                    <th class="p-3 font-semibold text-gray-700">Nombre</th>
                    <th class="p-3 font-semibold text-gray-700">Precio Venta</th>
                    <th class="p-3 font-semibold text-gray-700">Stock</th>
                    <th class="p-3 font-semibold text-gray-700">Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3">{{ $product->name }}</td>
                        <td class="p-3">${{ number_format($product->sale_price, 2) }}</td>
                        <td class="p-3">{{ $product->stock }}</td>
                        <td class="p-3">
                            @if($product->stock <= $product->min_stock)
                                <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-sm font-bold">Bajo Stock</span>
                            @else
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-sm font-bold">Normal</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-6 text-center text-gray-500">
                            No hay productos registrados todavia. Agrega el primero!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>