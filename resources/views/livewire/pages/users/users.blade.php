<div
    class="p-4 lg:p-6 space-y-6"
    wire:poll.120s="refreshUsers">
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

        <flux:button
            variant="primary"
            icon="plus"
            wire:click="create">
            Novo usuário
        </flux:button>
    </div>

    {{-- Filtros / Busca + Itens por página --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <flux:input
            class="w-full sm:max-w-xs"
            icon="magnifying-glass"
            placeholder="Buscar por nome ou e-mail..."
            wire:model.debounce.400ms.live="search" />

        <div class="flex items-center gap-3 text-xs text-zinc-500 dark:text-zinc-400">
            <div class="flex items-center gap-2">
                <span>Exibir</span>
                <select
                    wire:model.live="perPage"
                    class="rounded-lg border border-zinc-300 bg-white px-2 py-1 text-xs text-zinc-700
                       dark:bg-zinc-800 dark:border-zinc-700 dark:text-zinc-200">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span>por página</span>
            </div>

            {{-- Botão de Refresh da Tabela --}}
            <flux:button
                variant="ghost"
                size="xs"
                icon="arrow-path"
                class="!px-2"
                wire:click="refreshUsers"
                wire:loading.attr="disabled"
                wire:target="refreshUsers">
                <span wire:loading.remove wire:target="refreshUsers">
                    Atualizar
                </span>
                <span wire:loading wire:target="refreshUsers">
                    Atualizando...
                </span>
            </flux:button>
        </div>
    </div>


    {{-- Tabela de Usuários --}}
    <div class="overflow-hidden rounded-2xl border border-zinc-200 dark:border-zinc-700">
        {{-- Altura fixa ~10 linhas, scroll interno quando tiver mais --}}
        <div class="max-h-150 overflow-y-auto">

            {{-- SKELETON ENQUANTO CARREGA --}}
            <flux:skeleton.group
                animate="shimmer"
                wire:loading
                wire:target="search, perPage, page, save, delete, refreshUsers"
                class="block w-full">
                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                    <thead class="bg-zinc-50/80 dark:bg-zinc-900/60">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">
                                Id
                            </th>
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

                    @php
                    $skeletonRows = max($perPage ?? 10, 10);
                    @endphp

                    <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700">
                        @foreach (range(1, $skeletonRows) as $i)
                        <tr>
                            {{-- Id --}}
                            <td class="px-4 py-3 text-sm">
                                <flux:skeleton.line class="h-4 w-full" />
                            </td>

                            {{-- Nome --}}
                            <td class="px-4 py-3 text-sm">
                                <flux:skeleton.line class="h-4 w-full" />
                            </td>

                            {{-- E-mail --}}
                            <td class="px-4 py-3 text-sm">
                                <flux:skeleton.line class="h-4 w-full" />
                            </td>

                            {{-- Criado em --}}
                            <td class="px-4 py-3 text-sm">
                                <flux:skeleton.line class="h-4 w-full" />
                            </td>

                            {{-- Ações --}}
                            <td class="px-4 py-3 text-sm text-right">
                                <div class="inline-flex items-center gap-2 justify-end">
                                    <flux:skeleton class="h-6 w-16 rounded-md" />
                                    <flux:skeleton class="h-6 w-16 rounded-md" />
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </flux:skeleton.group>

            {{-- TABELA REAL (SOME QUANDO ESTIVER CARREGANDO) --}}
            <table
                class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700"
                wire:loading.remove
                wire:target="search, perPage, page, save, delete, refreshUsers">
                <!-- cabeçalho da tabela -->
                <thead class="bg-zinc-50/80 dark:bg-zinc-900/60">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">
                            Id
                        </th>
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

                <!-- corpo da tabela -->
                <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse ($users as $user)
                    <tr>
                        <td class="px-4 py-3 text-sm text-zinc-900 dark:text-zinc-50">
                            {{ $user->id }}
                        </td>
                        <td class="px-4 py-3 text-sm text-zinc-900 dark:text-zinc-50">
                            {{ $user->name }}
                        </td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-300">
                            {{ $user->email }}
                        </td>
                        <td class="px-4 py-3 text-sm text-zinc-500 dark:text-zinc-400">
                            {{ $user->created_at?->format('d/m/Y H:i') }}
                        </td>

                        <!-- ações -->
                        <td class="px-4 py-3 text-sm text-right">
                            <div class="inline-flex items-center gap-2">
                                <flux:button
                                    size="xs"
                                    variant="primary"
                                    color="amber"
                                    icon="pencil-square"
                                    wire:click="edit({{ $user->id }})">
                                    Editar
                                </flux:button>

                                <flux:button
                                    size="xs"
                                    variant="primary"
                                    color="red"
                                    icon="trash"
                                    wire:click="confirmDelete({{ $user->id }})">
                                    Excluir
                                </flux:button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-sm text-zinc-500 dark:text-zinc-400">
                            Nenhum usuário encontrado.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Paginação --}}
    @if ($users->hasPages())
    <div class="flex items-center justify-between mt-3 text-xs text-zinc-500 dark:text-zinc-400">
        <div>
            Mostrando
            <span class="font-semibold">
                {{ $users->firstItem() }}–{{ $users->lastItem() }}
            </span>
            de
            <span class="font-semibold">
                {{ $users->total() }}
            </span>
            registros
        </div>

        <div class="text-right">
            {{ $users->onEachSide(1)->links('components.pagination.numbers') }}
        </div>
    </div>
    @endif


    {{-- MODAL DE FORMULÁRIO (CRIAR / EDITAR) --}}
    @if ($showFormModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
        <div class="w-full max-w-md rounded-2xl bg-white dark:bg-zinc-800 shadow-lg p-6 space-y-4">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">
                        {{ $editingId ? 'Editar usuário' : 'Novo usuário' }}
                    </h2>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">
                        Preencha os dados do usuário.
                    </p>
                </div>

                <flux:button
                    size="xs"
                    variant="ghost"
                    icon="x-mark"
                    wire:click="closeModals" />
            </div>

            <div class="space-y-3">
                <flux:input
                    label="Nome"
                    placeholder="Nome completo"
                    wire:model.defer="name" />
                @error('name')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror

                <flux:input
                    label="E-mail"
                    type="email"
                    placeholder="email@exemplo.com"
                    wire:model.defer="email" />
                @error('email')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror

                <flux:input
                    label="Senha"
                    type="password"
                    placeholder="{{ $editingId ? 'Deixe em branco para manter' : '' }}"
                    wire:model.defer="password" />
                @error('password')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror

                <flux:input
                    label="Confirmar senha"
                    type="password"
                    wire:model.defer="password_confirmation" />
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <flux:button
                    variant="ghost"
                    wire:click="closeModals">
                    Cancelar
                </flux:button>

                <flux:button
                    variant="primary"
                    icon="check"
                    wire:click="save">
                    Salvar
                </flux:button>
            </div>
        </div>
    </div>
    @endif

    {{-- MODAL DE EXCLUSÃO --}}
    @if ($showDeleteModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
        <div class="w-full max-w-md rounded-2xl bg-white dark:bg-zinc-800 shadow-lg p-6 space-y-4">
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">
                Excluir usuário
            </h2>

            <p class="text-sm text-zinc-500 dark:text-zinc-400">
                Tem certeza que deseja excluir
                <span class="font-semibold">{{ $deletingName }}</span>?
            </p>

            <div class="flex items-center justify-end gap-2 pt-4">
                <flux:button
                    variant="ghost"
                    wire:click="closeModals">
                    Cancelar
                </flux:button>

                <flux:button
                    variant="primary"
                    color="red"
                    icon="trash"
                    wire:click="delete">
                    Excluir
                </flux:button>
            </div>
        </div>
    </div>
    @endif
</div>