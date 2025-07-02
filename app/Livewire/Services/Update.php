<?php

namespace App\Livewire\Services;

use App\Models\Category;
use App\Models\Service;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class Update extends Component
{
    use Toast;

    use WithFileUploads;

    public $categories;

    public $service;

    public $name;

    public $price;

    public $category_id;

    public $description;

    public $duration;

    public $avatar;

    public $modalUpdate = false;


    public function render()
    {
        return view('livewire.services.update');
    }

    #[On('service::open-update')]
    public function showModalUpdate($id)
    {
        $this->service = Service::findOrFail($id);

        $this->name = $this->service->name;
        $this->price = $this->service->price;
        $this->description = $this->service->description;
        $this->duration = $this->service->duration;
        $this->category_id = $this->service->category_id;
        $this->avatar = $this->service->avatar;

        $this->getCategories(null);

        $this->modalUpdate = true;
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
        
        $new_service = $this->validate();

        try {
            if ($this->avatar instanceof TemporaryUploadedFile) {
                $this->validate(['avatar' => 'image|mimes:jpeg,png,jpg,webp']);

                $filename = $this->avatar->getClientOriginalName();
                $path = $this->avatar->storeAs('fotos',$filename, 'public');
                $new_service['avatar'] = '/storage/' . $path;

                Storage::disk('public')->delete($this->service->avatar);
            }
            // Atualizando os dados do serviço
            $this->service->update($new_service);

            // Resetando as variáveis e fechando o modal
            $this->reset();

            $this->modalUpdate = false;

            // Atualizando a lista de serviços
            $this->dispatch('service::refresh');

            // Mensagem de sucesso
            $this->success(
                __('toast_success_title'),
                __('toast_success_description'),
                position: 'toast-start toast-bottom',
            );
        } catch (\Exception $e) {
            // Caso haja erro
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
            'avatar' => 'required',
            'name'  => ['required', 'min:3', 'unique:services,name,' . $this->service->id], // Nome com pelo menos 3 caracteres e apenas letras
            'price' => ['required', 'numeric', 'min:0.01'], // Preço deve ser um número maior que 0
            'description' => ['required', 'string', 'min:5'], // Descrição com pelo menos 10 caracteres
            'duration' => ['required'], // Duração de 15, 30 ou 45 minutos
            'category_id' => ['required']
        ];
    }

    #[On('category::updated')]
    public function getCategories($category)
    {
        $this->categories = Category::query()->orderBy('name')->get();
        $this->category_id = ($category) ? $category['id'] : $this->service->category->id ?? null;
    }

    private function isHorarioValido($hora)
    {
        list($h, $m) = explode(':', $hora);
        $minutosTotais = ((int)$h * 60) + (int)$m;

        return $minutosTotais < (12 * 60 + 50); // 770 minutos
    }
}