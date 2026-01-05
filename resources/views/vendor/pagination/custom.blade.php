@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="w-full mt-4">
        <ul class="flex justify-center items-center space-x-1">

            {{-- Previous Page Link --}}
            @if (!$paginator->onFirstPage())
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}"
                       class="inline-flex items-center justify-center w-8 h-8 rounded text-gray-600 hover:bg-gray-100">
                        &laquo;
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li>
                        <span class="inline-flex items-center justify-center w-8 h-8 text-gray-600">...</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li>
                            @if ($page == $paginator->currentPage())
                                <span class="inline-flex items-center justify-center w-8 h-8 bg-[#5B6541] text-white rounded">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}"
                                   class="inline-flex items-center justify-center w-8 h-8 text-gray-600 hover:bg-gray-100 rounded">
                                    {{ $page }}
                                </a>
                            @endif
                        </li>
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}"
                       class="inline-flex items-center justify-center w-8 h-8 rounded text-gray-600 hover:bg-gray-100">
                        &raquo;
                    </a>
                </li>
            @endif

        </ul>
    </nav>
@endif
