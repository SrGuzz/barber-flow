<?php

namespace App\Livewire\Category;

use App\Models\Category;
use Illuminate\Database\QueryException;
use Livewire\Attributes\On;
use Livewire\Component;
use Mary\Traits\Toast;

class Destroy extends Component
{
    use Toast;

    public ?Category $category;

    public bool $modalDestroy = false;

    public function render()
    {
        return view('livewire.category.destroy');
    }

    #[On('category::open-destroy')]
    public function showDestroyModal(int $id): void
    {
        $this->category     = Category::findOrFail($id);
        $this->modalDestroy = true;
    }

    public function delete(): void
    {
        try {
            $this->category->delete();
            $this->success(__('toast_success_title'), __('toast_success_description'));
            $this->dispatch('category::updated', null);   
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
