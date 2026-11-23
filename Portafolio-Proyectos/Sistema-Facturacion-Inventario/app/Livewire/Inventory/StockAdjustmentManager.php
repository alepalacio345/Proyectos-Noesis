<?php

namespace App\Livewire\Inventory;

use Livewire\Component;
use App\Models\Product;
use App\Models\ProductMovement;
use App\Models\ProductBatch;

class StockAdjustmentManager extends Component
{
    public $product_id;
    public $type = 'merma'; 
    public $reason = 'Dañado';
    public $quantity = 1;
    public $notes = '';
    public $expires_at = null;
    
    public $is_perishable_selected = false;

    public $mermaReasons = ['Dañado', 'Vencido', 'Pérdida/Robo', 'Otro'];
    public $devolucionReasons = ['Devolución de Cliente', 'Error de Inventario', 'Otro'];

    public function updatedProductId($value)
    {
        if ($value) {
            $product = Product::find($value);
            $this->is_perishable_selected = $product ? (bool) $product->is_perishable : false;
        } else {
            $this->is_perishable_selected = false;
        }
    }

    public function updatedType()
    {
        $this->reason = $this->type === 'merma' ? 'Dañado' : 'Devolución de Cliente';
    }

    public function save()
    {
        $this->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:merma,devolucion',
            'reason' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            // Laravel doesn't support complex required_if natively without closures sometimes, we will handle it manually.
        ]);

        if ($this->type === 'devolucion' && $this->is_perishable_selected && !$this->expires_at) {
            $this->addError('expires_at', 'La fecha de vencimiento es obligatoria para devoluciones de productos perecederos.');
            return;
        }

        $product = Product::findOrFail($this->product_id);
        $oldStock = $product->stock;

        if ($this->type === 'merma') {
            if ($this->quantity > $oldStock) {
                $this->addError('quantity', 'No hay suficiente stock para descontar esa cantidad.');
                return;
            }
            
            $product->decrement('stock', $this->quantity);
            
            if ($product->is_perishable) {
                $remaining = $this->quantity;
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

            ProductMovement::create([
                'product_id' => $product->id,
                'type' => 'out',
                'quantity' => $this->quantity,
                'previous_stock' => $oldStock,
                'new_stock' => $product->stock,
                'reason' => "Merma: " . $this->reason . ($this->notes ? " - " . $this->notes : "")
            ]);
            
        } else {
            $product->increment('stock', $this->quantity);

            if ($product->is_perishable) {
                ProductBatch::create([
                    'product_id' => $product->id,
                    'batch_number' => 'DEV-'.date('Ymd'),
                    'initial_stock' => $this->quantity,
                    'current_stock' => $this->quantity,
                    'expires_at' => $this->expires_at
                ]);
            }

            ProductMovement::create([
                'product_id' => $product->id,
                'type' => 'in',
                'quantity' => $this->quantity,
                'previous_stock' => $oldStock,
                'new_stock' => $product->stock,
                'reason' => "Devolución: " . $this->reason . ($this->notes ? " - " . $this->notes : "")
            ]);
        }

        session()->flash('message', 'Ajuste de inventario registrado con éxito.');
        $this->reset(['product_id', 'quantity', 'notes', 'expires_at', 'is_perishable_selected']);
    }

    public function render()
    {
        $products = Product::orderBy('name')->get();
        $recentMovements = ProductMovement::where('reason', 'like', 'Merma%')
            ->orWhere('reason', 'like', 'Devolución%')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('livewire.inventory.stock-adjustment-manager', compact('products', 'recentMovements'));
    }
}