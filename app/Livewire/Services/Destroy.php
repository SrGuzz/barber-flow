<?php

namespace App\Livewire\Services;

use App\Models\Service;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Mary\Traits\Toast;

class Destroy extends Component
{
    use Toast;

    public ?Service $service;

    public bool $modalDestroy = false;

    #[On('service::refresh')]
    public function render()
    {
        return view('livewire.services.destroy');
    }

    #[On('service::open-destroy')]
    public function showDestroyModal(int $id): void
    {
        $this->service        = Service::findOrFail($id);
        $this->modalDestroy = true;
    }

    public function delete(): void
    {
        try {
            $this->service->delete();
            Storage::disk('public')->delete($this->contact->avatar);
            $this->success(__('toast_success_title'), __('toast_success_description'));
            $this->dispatch('service::refresh');
            $this->modalDestroy = false;
        } 
        catch (QueryException $e) {
            $this->modalDestroy = false;

            // Verifica se é um erro de restrição de chave estrangeira
            if ($e->getCode() == 23000) { // Código SQLSTATE para "Integrity constraint violation"
                $this->error(
                    'Opss!',
                    'Não é possível excluir este registro porque ele está relacionado a outros registros no sistema.',
                    position: 'toast-start toast-bottom',
                    icon: 'o-face-frown'
                );
            }
        } 
        catch (\Exception $e) {
            $this->modalDestroy = false;
            $this->error(
                'Opss!',
                'Erro ao salvar, por gentileza entre em contato com o time de TI',
                position: 'toast-start toast-bottom',
                icon: 'o-face-frown'
            );
        }
    }

}

