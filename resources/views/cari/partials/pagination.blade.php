@if ($paginator->hasPages())
    <div class="pagination" style="display: flex; justify-content: center; align-items: center; gap: 5px; padding: 20px; flex-wrap: wrap;">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span style="padding: 8px 12px; background: #e0e0e0; color: #999; border-radius: 5px; cursor: not-allowed;">‹ Önceki</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="pagination-link" style="padding: 8px 12px; background: #667eea; color: white; border-radius: 5px; text-decoration: none;">‹ Önceki</a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span style="padding: 8px 12px;">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span style="padding: 8px 12px; background: #667eea; color: white; border-radius: 5px; font-weight: bold;">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="pagination-link" style="padding: 8px 12px; background: #f0f0f0; color: #333; border-radius: 5px; text-decoration: none;">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="pagination-link" style="padding: 8px 12px; background: #667eea; color: white; border-radius: 5px; text-decoration: none;">Sonraki ›</a>
        @else
            <span style="padding: 8px 12px; background: #e0e0e0; color: #999; border-radius: 5px; cursor: not-allowed;">Sonraki ›</span>
        @endif

        <span style="margin-left: 15px; color: #666; font-size: 14px;">
            {{ $paginator->firstItem() }}-{{ $paginator->lastItem() }} / {{ $paginator->total() }} kayıt
        </span>
    </div>
@endif

