<?php

namespace App\Livewire\User;

use App\Models\User;
use Illuminate\Database\QueryException;
use Livewire\Attributes\On;
use Livewire\Component;
use Mary\Traits\Toast;

class Destroy extends Component
{
    use Toast;

    public ?User $user;

    public bool $modalDestroy = false;

    #[On('user::refresh')]
    public function render()
    {
        return view('livewire.user.destroy');
    }

    #[On('user::open-destroy')]
    public function showDestroyModal(int $id): void
    {
        $this->user         = User::findOrFail($id);
        $this->modalDestroy = true;
    }

    public function delete(): void
    {
        try {
            $this->user->delete();
            $this->success('Sucesso!', 'Usuario excluido com exito.', position: 'toast-bottom toast-start');
            $this->dispatch('user::refresh');
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
