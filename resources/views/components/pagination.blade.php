@if ($paginator->hasPages())
    <style>
        :root {
            --color-primary: #1a7a99;
            --color-primary-light: #2a9db8;
            --color-text-primary: #333333;
            --color-text-secondary: #666666;
            --color-bg-primary: #f5f5f5;
            --color-border: #e0e0e0;
            --spacing-xs: 4px;
            --spacing-sm: 8px;
            --spacing-md: 12px;
            --radius-md: 6px;
        }

        .pagination-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: var(--spacing-md) 0;
            flex-wrap: wrap;
            gap: var(--spacing-xs);
        }

        .pagination-list {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            gap: var(--spacing-xs);
            align-items: center;
            flex-wrap: wrap;
            justify-content: center;
        }

        .pagination-item {
            display: flex;
            align-items: center;
        }

        .pagination-link,
        .pagination-active,
        .pagination-disabled {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pagination-btn,
        .pagination-number {
            min-width: 32px;
            height: 32px;
            padding: 0 var(--spacing-xs);
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--color-border);
            border-radius: var(--radius-md);
            color: var(--color-text-primary);
            text-decoration: none;
            transition: all 0.2s ease;
            font-weight: 500;
            font-size: 13px;
            cursor: pointer;
            background-color: white;
        }

        .pagination-btn:hover {
            background-color: var(--color-bg-primary);
            color: var(--color-primary);
            border-color: var(--color-primary-light);
        }

        .pagination-active .pagination-number {
            background-color: var(--color-primary);
            color: white;
            border-color: var(--color-primary);
        }

        .pagination-disabled .pagination-btn {
            color: var(--color-text-secondary);
            cursor: not-allowed;
            opacity: 0.5;
            background-color: #f9f9f9;
        }

        .pagination-disabled .pagination-btn:hover {
            background-color: #f9f9f9;
            border-color: var(--color-border);
        }

        @media (max-width: 768px) {
            .pagination-btn,
            .pagination-number {
                min-width: 28px;
                height: 28px;
                padding: 0 4px;
                font-size: 12px;
            }
        }
    </style>

    <nav aria-label="Pagination Navigation" class="pagination-wrapper">
        <ul class="pagination-list">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="pagination-item pagination-disabled">
                    <span class="pagination-btn">← Previous</span>
                </li>
            @else
                <li class="pagination-item">
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="pagination-btn">← Previous</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="pagination-item pagination-disabled">
                        <span class="pagination-btn">...</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="pagination-item pagination-active">
                                <span class="pagination-number" aria-current="page">{{ $page }}</span>
                            </li>
                        @else
                            <li class="pagination-item">
                                <a href="{{ $url }}" class="pagination-number">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="pagination-item">
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="pagination-btn">Next →</a>
                </li>
            @else
                <li class="pagination-item pagination-disabled">
                    <span class="pagination-btn">Next →</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
