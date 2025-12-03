<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserService
{
    /**
     * Paginar usuários com filtro de busca.
     */
    public function paginateUsers(string $search, int $perPage, int $page): LengthAwarePaginator
    {
        $query = User::query();

        if (strlen($search) > 0) {
            $s = '%' . $search . '%';

            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', $s)
                    ->orWhere('email', 'like', $s);
            });
        }

        return $query
            ->orderBy('updated_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    /**
     * Buscar usuário ou lançar 404.
     */
    public function findUserOrFail(int $id): User
    {
        return User::findOrFail($id);
    }

    /**
     * Regras de validação, respeitando criação x edição
     * e se a senha veio preenchida ou não.
     */
    public function rulesFor(?int $id, string $password): array
    {
        $emailRule = [
            'required',
            'email',
            'max:255',
            Rule::unique('users', 'email')->ignore($id),
        ];

        $rules = [
            'name'  => ['required', 'string', 'max:255'],
            'email' => $emailRule,
        ];

        if ($id) {
            // Edição: senha opcional
            if ($password !== '') {
                $rules['password'] = ['nullable', 'min:6', 'confirmed'];
            }
        } else {
            // Criação: senha obrigatória
            $rules['password'] = ['required', 'min:6', 'confirmed'];
        }

        return $rules;
    }

    /**
     * Criar ou atualizar usuário a partir dos dados validados.
     */
    public function saveUser(?int $id, array $validated, string $password): User
    {
        // Se veio senha, sempre substitui
        if ($password !== '') {
            $validated['password'] = Hash::make($password);
        } else {
            // Se for criação e por algum motivo a senha ainda está em $validated,
            // garante que vai hashear (defensive).
            if (!$id && isset($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            }
        }

        if ($id) {
            $user = User::findOrFail($id);
            $user->update($validated);

            return $user;
        }

        return User::create($validated);
    }

    /**
     * Excluir usuário pelo ID.
     */
    public function deleteUser(int $id): void
    {
        User::whereKey($id)->delete();
    }
}
