@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="w-full">

        {{-- MOBILE: anterior / próximo --}}
        <div class="flex gap-3 items-center justify-between sm:hidden px-4 py-3">

            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-gray-400 bg-gray-50 border border-gray-200 cursor-not-allowed rounded-lg dark:text-gray-500 dark:bg-gray-800 dark:border-gray-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Anterior
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                   class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-all duration-200 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700 dark:hover:border-gray-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Anterior
                </a>
            @endif

            {{-- Page Info --}}
            <span class="text-sm text-gray-600 dark:text-gray-400 font-medium">
                {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
            </span>

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                   class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-all duration-200 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700 dark:hover:border-gray-500">
                    Próximo
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @else
                <span class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-gray-400 bg-gray-50 border border-gray-200 cursor-not-allowed rounded-lg dark:text-gray-500 dark:bg-gray-800 dark:border-gray-700">
                    Próximo
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </span>
            @endif

        </div>

        {{-- DESKTOP: números com lógica de 3 iniciais ... 3 finais --}}
        <div class="hidden sm:flex sm:items-center sm:justify-center sm:gap-2 py-4">

            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}"
                      class="inline-flex items-center justify-center w-10 h-10 text-gray-400 bg-gray-50 border border-gray-200 cursor-not-allowed rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-gray-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                   class="inline-flex items-center justify-center w-10 h-10 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700"
                   aria-label="{{ __('pagination.previous') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
            @endif

            {{-- Lógica de páginas: 3 iniciais ... 3 finais --}}
            @php
                $currentPage = $paginator->currentPage();
                $lastPage = $paginator->lastPage();
                $showPages = [];
                
                if ($lastPage <= 9) {
                    // Se tiver 9 páginas ou menos, mostra todas
                    $showPages = range(1, $lastPage);
                } else {
                    // Mostra 3 iniciais
                    $showPages = array_merge($showPages, [1, 2, 3]);
                    
                    // Se a página atual estiver no meio, mostra ela e adjacentes
                    if ($currentPage > 4 && $currentPage < $lastPage - 3) {
                        $showPages[] = '...';
                        $showPages = array_merge($showPages, [$currentPage - 1, $currentPage, $currentPage + 1]);
                        $showPages[] = '...';
                    } else if ($currentPage <= 4) {
                        // Se estiver perto do início, não mostra reticências no início
                        $showPages[] = '...';
                    } else {
                        // Se estiver perto do fim
                        $showPages[] = '...';
                    }
                    
                    // Mostra 3 finais
                    $showPages = array_merge($showPages, [$lastPage - 2, $lastPage - 1, $lastPage]);
                    $showPages = array_unique($showPages);
                }
            @endphp

            @foreach ($showPages as $page)
                @if ($page === '...')
                    <span class="inline-flex items-center justify-center w-10 h-10 text-gray-500 dark:text-gray-400">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"/>
                        </svg>
                    </span>
                @elseif ($page == $currentPage)
                    <span aria-current="page"
                          class="inline-flex items-center justify-center w-10 h-10 text-sm font-semibold text-white bg-blue-600 border border-blue-600 rounded-lg shadow-sm dark:bg-blue-500">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $paginator->url($page) }}"
                       class="inline-flex items-center justify-center w-10 h-10 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700"
                       aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                   class="inline-flex items-center justify-center w-10 h-10 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700"
                   aria-label="{{ __('pagination.next') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @else
                <span aria-disabled="true" aria-label="{{ __('pagination.next') }}"
                      class="inline-flex items-center justify-center w-10 h-10 text-gray-400 bg-gray-50 border border-gray-200 cursor-not-allowed rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-gray-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </span>
            @endif

        </div>

    </nav>
@endif