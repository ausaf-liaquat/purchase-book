<?php
namespace App\Livewire\Master;
use Livewire\Component;
use App\Models\Item;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Title('Items')]
class ItemIndex extends Component
{
    public $name = '';
    public $editId = null;
    public $showModal = false;

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.master.item-index', [
            'items' => Item::latest()->get()
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
        $item = Item::findOrFail($id);
        $this->editId = $item->id;
        $this->name = $item->name;
        $this->showModal = true;
    }

    public function save()
    {
        if ($this->editId) {
            $this->validate(['name' => 'required|unique:items,name,'.$this->editId]);
            Item::findOrFail($this->editId)->update(['name' => $this->name]);
        } else {
            $this->validate(['name' => 'required|unique:items,name']);
            Item::create(['name' => $this->name]);
        }
        $this->showModal = false;
        $this->reset('name', 'editId');
    }

    public function delete($id)
    {
        if (\App\Models\PurchaseItem::where('item_id', $id)->exists()) {
            session()->flash('error', 'Cannot delete Item because it is used in existing purchases.');
            return;
        }
        Item::findOrFail($id)->delete();
    }
}
