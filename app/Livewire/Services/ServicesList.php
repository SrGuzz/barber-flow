<?php

namespace App\Livewire\Services;

use App\Models\Service;
use Livewire\Attributes\{On, Title};
use Livewire\{Component, WithPagination};
use Mary\Traits\Toast;

class ServicesList extends Component
{

    use WithPagination;

    use Toast;

    public string $search = '';

    public bool $drawer = false;

    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];

    #[ON('service::refresh')]
    #[Title('Serviços')]
    public function render()
    {
        return view('livewire.services.services-list', [
            'services'   => $this->services(),

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
            ['key' => 'price', 'label' => 'Preço'],
            ['key' => 'category_name', 'label' => 'Categoria'],
            ['key' => 'description', 'label' => 'Descrição'],
            ['key' => 'duration', 'label' => 'Duração'],
        ];
    }

    public function services()
    {
        $query = Service::orderBy(...array_values($this->sortBy))->withAggregate('category', 'name');

        // Aplica filtro de busca se um termo for fornecido
        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        // Retorna os resultados paginados
        return $query->paginate(15); // Você pode ajustar o número de itens por página
    }
}
