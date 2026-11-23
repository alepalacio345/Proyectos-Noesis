<?php

namespace App\Livewire\Inventory;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Category;
use App\Models\ExchangeRate;

class ProductManager extends Component
{
    use WithPagination;

    public $search = '';
    public $isEditing = false;
    
    // Form fields
    public $productId;
    public $name;
    public $barcode;
    public $category_id;
    public $purchase_price;
    public $sale_price;
    public $stock;
    public $min_stock;

    protected $rules = [
        'name' => 'required|string|max:255',
        'barcode' => 'nullable|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'purchase_price' => 'required|numeric|min:0',
        'sale_price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'min_stock' => 'required|integer|min:0',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function createNew()
    {
        $this->reset(['productId', 'name', 'barcode', 'category_id', 'purchase_price', 'sale_price', 'stock', 'min_stock']);
        $this->isEditing = true;
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $this->productId = $product->id;
        $this->name = $product->name;
        $this->barcode = $product->barcode;
        $this->category_id = $product->category_id;
        $this->purchase_price = $product->purchase_price;
        $this->sale_price = $product->sale_price;
        $this->stock = $product->stock;
        $this->min_stock = $product->min_stock;
        
        $this->isEditing = true;
    }

    public function save()
    {
        $this->validate();

        Product::updateOrCreate(
            ['id' => $this->productId],
            [
                'name' => $this->name,
                'barcode' => $this->barcode,
                'category_id' => $this->category_id,
                'purchase_price' => $this->purchase_price,
                'sale_price' => $this->sale_price,
                'stock' => $this->stock,
                'min_stock' => $this->min_stock,
            ]
        );

        $this->isEditing = false;
        session()->flash('message', $this->productId ? 'Producto actualizado con éxito.' : 'Producto creado con éxito.');
    }

    public function delete($id)
    {
        Product::findOrFail($id)->delete();
        session()->flash('message', 'Producto eliminado.');
    }

    public function render()
    {
        $products = Product::with('category')
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('barcode', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);
            
        $categories = Category::all();

        // Get the latest exchange rate (fallback to 1 if none exists to avoid division by zero errors, though it's mainly for multiplication)
        $latestRate = ExchangeRate::orderBy('date', 'desc')->first();
        $bcvRate = $latestRate ? (float) $latestRate->rate : 0;

        return view('livewire.inventory.product-manager', [
            'products' => $products,
            'categories' => $categories,
            'bcvRate' => $bcvRate
        ]);
    }
}