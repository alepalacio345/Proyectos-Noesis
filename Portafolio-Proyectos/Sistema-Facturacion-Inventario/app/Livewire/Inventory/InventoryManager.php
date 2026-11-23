<?php

namespace App\Livewire\Inventory;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\ProductMovement;
use App\Models\Category;
use App\Models\ExchangeRate;

class InventoryManager extends Component
{
    use WithPagination;

    // Tab state - managed by Livewire, not Alpine
    public string $activeTab = 'products';

    // Shared modal state
    public bool $showModal = false;

    // Product form fields
    public $productId;
    public $name = '';
    public $barcode = '';
    public $category_id = '';
    public $purchase_price = '';
    public $sale_price = '';
    public $stock = 0;
    public $min_stock = 5;

    public bool $is_perishable = false;
    public $expires_at = null;

    // Category form fields
    public $categoryId;
    public $categoryName = '';
    public $categoryDescription = '';

    // Search
    public $search = '';

    protected function productRules(): array
    {
        return [
            'name'           => 'required|string|max:255',
            'barcode'        => 'nullable|string|max:255',
            'category_id'    => 'required|exists:categories,id',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price'     => 'required|numeric|min:0',
            'stock'          => 'required|integer|min:0',
            'min_stock'      => 'required|integer|min:0',
            'is_perishable'  => 'boolean',
            'expires_at'     => 'required_if:is_perishable,true|nullable|date',
        ];
    }

    protected function categoryRules(): array
    {
        return [
            'categoryName'        => 'required|string|max:255',
            'categoryDescription' => 'nullable|string|max:1000',
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->search    = '';
        $this->showModal = false;
        $this->resetPage();
    }

    // ─── Products ──────────────────────────────────────────────────────────────

    public function createProduct(): void
    {
        $this->resetProductForm();
        $this->showModal = true;
    }

    public function editProduct(int $id): void
    {
        $product = Product::findOrFail($id);
        $this->productId      = $product->id;
        $this->name           = $product->name;
        $this->barcode        = $product->barcode ?? '';
        $this->category_id    = $product->category_id;
        $this->purchase_price = $product->purchase_price;
        $this->sale_price     = $product->sale_price;
        $this->stock          = $product->stock;
        $this->min_stock      = $product->min_stock;
        $this->is_perishable  = (bool) $product->is_perishable;
        $this->expires_at     = null; // Reset input field for new batches
        $this->showModal      = true;
    }



        // Kardex Properties
    public $showKardexModal = false;
    public $kardexProduct = null;
    public $kardexMovements = [];

    public function showKardex($productId)
    {
        $this->kardexProduct = Product::findOrFail($productId);
        $this->kardexMovements = ProductMovement::where('product_id', $productId)
                                    ->orderBy('created_at', 'desc')
                                    ->get();
        $this->showKardexModal = true;
    }

    public function closeKardex()
    {
        $this->showKardexModal = false;
        $this->kardexProduct = null;
        $this->kardexMovements = [];
    }

    public function saveProduct(): void
    {
        $this->validate($this->productRules());

        $oldStock = 0;
        $isNew = true;

        if ($this->productId) {
            $product = Product::find($this->productId);
            if ($product) {
                $oldStock = $product->stock;
                $isNew = false;
            }
        }

        $product = Product::updateOrCreate(
            ['id' => $this->productId],
            [
                'name'           => $this->name,
                'barcode'        => $this->barcode ?: null,
                'category_id'    => $this->category_id,
                'purchase_price' => $this->purchase_price,
                'sale_price'     => $this->sale_price,
                'stock'          => $this->stock,
                'min_stock'      => $this->min_stock,
                'is_perishable'  => $this->is_perishable,
            ]
        );

        $newStock = $product->stock;

                // Kardex Logging & Batches
        if ($isNew && $newStock > 0) {
            ProductMovement::create([
                'product_id'     => $product->id,
                'type'           => 'in',
                'quantity'       => $newStock,
                'previous_stock' => 0,
                'new_stock'      => $newStock,
                'reason'         => "Inventario Inicial"
            ]);
            
            if ($this->is_perishable) {
                ProductBatch::create([
                    'product_id' => $product->id,
                    'batch_number' => 'INI-'.date('Ymd'),
                    'initial_stock' => $newStock,
                    'current_stock' => $newStock,
                    'expires_at' => $this->expires_at
                ]);
            }
        } elseif (!$isNew && $oldStock != $newStock) {
            $qty = abs($newStock - $oldStock);
            $type = $newStock > $oldStock ? 'in' : 'out';
            ProductMovement::create([
                'product_id'     => $product->id,
                'type'           => $type,
                'quantity'       => $qty,
                'previous_stock' => $oldStock,
                'new_stock'      => $newStock,
                'reason'         => "Ajuste Manual"
            ]);

            if ($this->is_perishable) {
                if ($type === 'in') {
                    ProductBatch::create([
                        'product_id' => $product->id,
                        'batch_number' => 'AJT-'.date('Ymd'),
                        'initial_stock' => $qty,
                        'current_stock' => $qty,
                        'expires_at' => $this->expires_at
                    ]);
                } else {
                    $remaining = $qty;
                    foreach($product->activeBatches as $batch) {
                        if ($remaining <= 0) break;
                        if ($batch->current_stock >= $remaining) {
                            $batch->decrement('current_stock', $remaining);
                            $remaining = 0;
                        } else {
                            $remaining -= $batch->current_stock;
                            $batch->update(['current_stock' => 0]);
                        }
                    }
                }
            }
        }

        $this->showModal = false;
        $this->resetProductForm();
        session()->flash('message', 'Producto guardado con éxito.');
    }

    public function saveCategory(): void
    {
        $this->validate($this->categoryRules());

        Category::updateOrCreate(
            ['id' => $this->categoryId],
            [
                'name'        => $this->categoryName,
                'description' => $this->categoryDescription ?: null,
            ]
        );

        $this->showModal = false;
        $this->resetCategoryForm();
        session()->flash('message', 'Categoría guardada con éxito.');
    }

    public function deleteCategory(int $id): void
    {
        Category::findOrFail($id)->delete();
        session()->flash('message', 'Categoría eliminada.');
    }

    // ─── Helpers ───────────────────────────────────────────────────────────────

    private function resetProductForm(): void
    {
        $this->productId      = null;
        $this->name           = '';
        $this->barcode        = '';
        $this->category_id    = '';
        $this->purchase_price = '';
        $this->sale_price     = '';
        $this->stock          = 0;
        $this->min_stock      = 5;
        $this->is_perishable  = false;
        $this->expires_at     = null;
        $this->resetValidation();
    }

    private function resetCategoryForm(): void
    {
        $this->categoryId          = null;
        $this->categoryName        = '';
        $this->categoryDescription = '';
        $this->resetValidation();
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetValidation();
    }

    public function render()
    {
        $latestRate = ExchangeRate::orderBy('date', 'desc')->first();
        $bcvRate    = $latestRate ? (float) $latestRate->rate : 0;

        $products   = Product::with('category')
            ->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('barcode', 'like', '%' . $this->search . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        $categories = Category::withCount('products')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.inventory.inventory-manager', compact('products', 'categories', 'bcvRate'));
    }
}