@if ($products->hasPages())
    <ul class="pagination">
        {{-- Previous Page Link --}}
        @if ($products->onFirstPage())
            <li>
                <span class="pagination__link disabled">
                    <i class="ri-arrow-left-s-line"></i>
                </span>
            </li>
        @else
            <li>
                <a href="{{ $products->previousPageUrl() }}" class="pagination__link icon" rel="prev">
                    <i class="ri-arrow-left-s-line"></i>
                </a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($products->links()->elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li>
                    <span class="pagination__link">...</span>
                </li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $products->currentPage())
                        <li>
                            <span class="pagination__link active">{{ sprintf('%02d', $page) }}</span>
                        </li>
                    @else
                        <li>
                            <a href="{{ $url }}" class="pagination__link">{{ sprintf('%02d', $page) }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($products->hasMorePages())
            <li>
                <a href="{{ $products->nextPageUrl() }}" class="pagination__link icon" rel="next">
                    <i class="ri-arrow-right-s-line"></i>
                </a>
            </li>
        @else
            <li>
                <span class="pagination__link disabled">
                    <i class="ri-arrow-right-s-line"></i>
                </span>
            </li>
        @endif
    </ul>
@endif
