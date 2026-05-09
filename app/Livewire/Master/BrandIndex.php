<?php
namespace App\Livewire\Master;
use Livewire\Component;
use App\Models\Brand;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Title('Brands')]
class BrandIndex extends Component
{
    public $name = '';
    public $editId = null;
    public $showModal = false;

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.master.brand-index', [
            'brands' => Brand::latest()->get()
        ]);
    }

    public function create()
    {
        $this->reset('name', 'editId');
        $this->resetValidation();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->resetValidation();
        $brand = Brand::findOrFail($id);
        $this->editId = $brand->id;
        $this->name = $brand->name;
        $this->showModal = true;
    }

    public function save()
    {
        if ($this->editId) {
            $this->validate(['name' => 'required|unique:brands,name,'.$this->editId]);
            Brand::findOrFail($this->editId)->update(['name' => $this->name]);
        } else {
            $this->validate(['name' => 'required|unique:brands,name']);
            Brand::create(['name' => $this->name]);
        }
        $this->showModal = false;
        $this->reset('name', 'editId');
    }

    public function delete($id)
    {
        if (\App\Models\PurchaseItem::where('brand_id', $id)->exists()) {
            session()->flash('error', 'Cannot delete Brand because it is used in existing purchases.');
            return;
        }
        Brand::findOrFail($id)->delete();
    }
}
