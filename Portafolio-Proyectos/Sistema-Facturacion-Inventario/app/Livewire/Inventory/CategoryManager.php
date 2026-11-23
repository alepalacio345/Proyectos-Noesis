<?php

namespace App\Livewire\Inventory;

use App\Models\Category;
use Livewire\Component;

class CategoryManager extends Component
{
    public $categoryId;
    public $name;
    public $description;

    public $isEditing = false;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
    }

    public function createNew()
    {
        $this->resetFields();
        $this->isEditing = true;
    }

    public function resetFields()
    {
        $this->reset(['categoryId', 'name', 'description', 'isEditing']);
        $this->resetValidation();
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->isEditing = true;
        
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();

        Category::updateOrCreate(
            ['id' => $this->categoryId],
            [
                'name' => $this->name,
                'description' => $this->description,
            ]
        );

        $this->resetFields();
        // Here we could dispatch a browser event for toast notification
        $this->dispatch('category-saved');
    }

    public function delete($id)
    {
        $category = Category::findOrFail($id);
        
        // Block deletion if it has products attached
        if ($category->products()->count() > 0) {
            $this->addError('delete', 'No se puede eliminar la categoria porque tiene productos asociados.');
            return;
        }

        $category->delete();
        $this->dispatch('category-deleted');
    }

    public function render()
    {
        return view('livewire.inventory.category-manager', [
            'categories' => Category::latest()->get()
        ]);
    }
}
