<?php

namespace App\Livewire\Services;

use App\Models\Category;
use App\Models\Service;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class Create extends Component
{
    use Toast;
    use WithFileUploads;

    public $categories;

    public $name;
    public $price;
    public $description;
    public $acess;
    public $duration;
    public $modalCreate = false;
    public $category_id;

    #[Rule('required')]
    #[Rule('image')]
    #[Rule('mimes:jpeg,png,jpg,webp')]
    public $avatar;

    public function render()
    {
        return view('livewire.services.create');
    }

    #[On('service::open-create')]
    public function showModalCreate()
    {
        $this->modalCreate = true;
        $this->getCategories(null);
    }

    public function save()
    {
        if($this->duration && !$this->isHorarioValido($this->duration))
        {
            $this->addError('duration', 'O horário deve ser inferior a 12 horas e 50 minutos.');
            return;
        } 
        else{
            $this->resetErrorBag('duration');
        }

        $service = $this->validate();

        try {
            $filename = $this->avatar->getClientOriginalName();
            $path = $this->avatar->storeAs('fotos',$filename, 'public');
            $service['avatar'] = '/storage/' . $path;

            $service = Service::create($service);

            $this->reset();
            $this->modalCreate = false;
            $this->dispatch('service::refresh');

            $this->success(
                'Sucesso!',
                'Serviço cadastrado com sucesso.',
                position: 'toast-start toast-bottom',
            );
        } catch (\Exception $e){
            $this->error(
                'Opss!',
                'Erro ao salvar, por gentileza entre em contato com o time de TI',
                position: 'toast-start toast-bottom',
                icon: 'o-face-frown'
            );
        }
    }

    public function rules()
    {
        return [
            'name'  => ['required', 'min:3', 'unique:services,name'], // Nome com pelo menos 3 caracteres e apenas letras
            'price' => ['required', 'numeric', 'min:0.01'], // Preço deve ser um número maior que 0
            'description' => ['required', 'string', 'min:5'], // Descrição com pelo menos 10 caracteres
            'duration' => ['required'],
            'category_id' => ['required'],
        ];
    }

    #[On('category::updated')]
    public function getCategories($category)
    {
        $this->categories = Category::query()->orderBy('name')->get();
        $this->category_id = ($category) ? $category['id'] : $this->categories->first()->id ?? null;
    }

    private function isHorarioValido($hora)
    {
        list($h, $m) = explode(':', $hora);
        $minutosTotais = ((int)$h * 60) + (int)$m;

        return $minutosTotais < (12 * 60 + 50); // 770 minutos
    }
}