<?php

namespace App\Livewire\Category;

use App\Models\Category;
use Livewire\Attributes\On;
use Livewire\Component;
use Mary\Traits\Toast;

class Create extends Component
{
    use Toast;

    public $modal_create = false;
    public $name;

    public function render()
    {
        return view('livewire.category.create');
    }

    #[On('category::open-create')]
    public function show_modal_create()
    {
        $this->modal_create = true;
    }

    public function save()
    {
        $this->validate();

        $category = Category::create(['name' => $this->name]);

        $this->dispatch('category::updated', $category);
        $this->success(__('toast_success_title'), __('toast_success_description'));
        $this->reset();
        $this->modal_create = false;
    }

    public function rules()
    {
        return [
            'name' => 'required|min:2|unique:categories,name',
        ];
    }
}
