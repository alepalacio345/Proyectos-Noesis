<?php

namespace App\Livewire\Inventory;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ProductMovement;

class KardexManager extends Component
{
    use WithPagination;

    public $search = '';
    public $typeFilter = '';
    
    // Reset pagination when searching/filtering
    public function updatedSearch() { $this->resetPage(); }
    public function updatedTypeFilter() { $this->resetPage(); }

    public function render()
    {
        $query = ProductMovement::with('product')->orderBy('created_at', 'desc');

        if ($this->search) {
            $query->whereHas('product', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('barcode', 'like', '%' . $this->search . '%');
            })->orWhere('reason', 'like', '%' . $this->search . '%');
        }

        if ($this->typeFilter) {
            $query->where('type', $this->typeFilter);
        }

        $movements = $query->paginate(20);

        return view('livewire.inventory.kardex-manager', [
            'movements' => $movements
        ]);
    }
}