<div class="p-4 lg:p-6 space-y-6">
    {{-- Título + Ações --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-semibold text-zinc-900 dark:text-zinc-50">
                Gerenciamento de Usuários
            </h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400">
                Cadastre, edite e gerencie os usuários do sistema.
            </p>
        </div>

        <flux:button icon="plus" wire:click="$dispatch('openCreateUserModal')">
            Novo usuário
        </flux:button>
    </div>

    {{-- Filtros / Busca --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <flux:input
            class="w-full sm:max-w-xs"
            icon="magnifying-glass"
            placeholder="Buscar por nome ou e-mail..."
            wire:model.debounce.400ms="search" />
    </div>

    {{-- Tabela de Usuários --}}
    <div class="overflow-hidden rounded-2xl border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50/80 dark:bg-zinc-900/60">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">
                        Nome
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">
                        E-mail
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">
                        Criado em
                    </th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-zinc-500 uppercase tracking-wider">
                        Ações
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700">
                @forelse ($users as $user)
                    <tr>
                        <td class="px-4 py-3 text-sm text-zinc-900 dark:text-zinc-50">
                            {{ $user->name }}
                        </td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-300">
                            {{ $user->email }}
                        </td>
                        <td class="px-4 py-3 text-sm text-zinc-500 dark:text-zinc-400">
                            {{ $user->created_at?->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-4 py-3 text-sm text-right">
                            <div class="inline-flex items-center gap-2">
                                <flux:button
                                    size="xs"
                                    variant="ghost"
                                    icon="pencil-square"
                                    wire:click="$dispatch('openEditUserModal', { id: @js($user->id) })">
                                    Editar
                                </flux:button>

                                <flux:button
                                    size="xs"
                                    variant="ghost"
                                    color="red"
                                    icon="trash"
                                    wire:click="$dispatch('confirmDeleteUser', { id: @js($user->id) })">
                                    Excluir
                                </flux:button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-sm text-zinc-500 dark:text-zinc-400">
                            Nenhum usuário encontrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Aqui depois você pode colocar modais de criar/editar --}}
</div>
