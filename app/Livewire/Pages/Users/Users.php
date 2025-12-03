<?php

namespace App\Livewire\Pages\Users;

use App\Services\UserService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.panel')]
class Users extends Component
{
    use WithPagination;

    /** @var \App\Services\UserService */
    protected UserService $userService;

    // Livewire injeta aqui, em vez de no __construct
    public function boot(UserService $userService): void
    {
        $this->userService = $userService;
    }

    // Livewire vai usar esse cara para ?page=2
    #[Url(as: 'page')]
    public int $page = 1;

    // Quantidade por página (select 10, 25, 50, 100)
    public int $perPage = 10;

    public string $search = '';

    // Modal de formulário (criar/editar)
    public bool $showFormModal = false;
    public ?int $editingId = null;
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    // Modal de exclusão
    public bool $showDeleteModal = false;
    public ?int $deletingId = null;
    public string $deletingName = '';

    // === REAÇÕES A MUDANÇAS DE FILTRO / PER PAGE ===

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    // Lista de usuários paginada (via service)
    public function getUsersProperty()
    {
        return $this->userService->paginateUsers(
            search: $this->search,
            perPage: $this->perPage,
            page: $this->page,
        );
    }

    // ==== AÇÕES DA TELA ====

    public function create()
    {
        $this->resetForm();
        $this->showFormModal = true;
    }

    public function edit(int $id)
    {
        $user = $this->userService->findUserOrFail($id);

        $this->editingId = $user->id;
        $this->name      = $user->name;
        $this->email     = $user->email;
        $this->password  = '';
        $this->password_confirmation = '';

        $this->resetErrorBag();
        $this->showFormModal = true;
    }

    public function save()
    {
        $id = $this->editingId;

        // regras vêm da service
        $rules = $this->userService->rulesFor($id, $this->password);

        $data = $this->validate($rules);

        // Service faz hash + create/update
        $this->userService->saveUser($id, $data, $this->password);

        $this->showFormModal = false;
        $this->resetForm();
    }

    public function confirmDelete(int $id)
    {
        $user = $this->userService->findUserOrFail($id);

        $this->deletingId   = $user->id;
        $this->deletingName = $user->name;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if ($this->deletingId) {
            $this->userService->deleteUser($this->deletingId);
        }

        $this->showDeleteModal = false;
        $this->deletingId   = null;
        $this->deletingName = '';
    }

    public function closeModals()
    {
        $this->showFormModal   = false;
        $this->showDeleteModal = false;
    }

    protected function resetForm(): void
    {
        $this->reset([
            'editingId',
            'name',
            'email',
            'password',
            'password_confirmation',
        ]);

        $this->resetErrorBag();
    }

    public function refreshUsers(): void
    {
        // só pra manter o skeleton visível
        sleep(1);
    }

    public function render()
    {
        return view('livewire.pages.users.users', [
            'users' => $this->users,
        ]);
    }
}
