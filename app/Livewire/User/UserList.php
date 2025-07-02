<?php

namespace App\Livewire\User;

use App\Models\User;
use Livewire\Attributes\{On, Title};
use Livewire\{Component, WithPagination};
use Mary\Traits\Toast;

class UserList extends Component
{
    use WithPagination;

    use Toast;

    public string $search = '';

    public bool $drawer = false;

    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];

    #[ON('user::refresh')]
    #[Title('Usuários')]
    public function render()
    {
        return view('livewire.user.user-list', [
            'users'   => $this->users(),
            'headers' => $this->headers(),
        ]);
    }

    // Clear filters
    public function clear(): void
    {
        $this->reset();
        $this->success('Filters cleared.', position: 'toast-bottom');
    }

    // Table headers
    public function headers(): array
    {
        return [
            ['key' => 'name', 'label' => 'Nome'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'phone', 'label' => 'Telefone'],
            ['key' => 'access_level', 'label' => 'Nivel de Acesso'],
            ['key' => 'status', 'label' => 'Status'],
        ];
    }

    /**
     * For demo purpose, this is a static collection.
     *
     * On real projects you do it with Eloquent collections.
     * Please, refer to maryUI docs to see the eloquent examples.
     */
    public function users()
    {
        $query = User::orderBy(...array_values($this->sortBy));

        // Aplica filtro de busca se um termo for fornecido
        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        // Retorna os resultados paginados
        return $query->paginate(15); // Você pode ajustar o número de itens por página
    }

}
