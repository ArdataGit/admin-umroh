@props(['pageTitle' => 'Page', 'breadcrumbs' => []])

<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div class="flex items-center gap-4">
        <a href="javascript:history.back()" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">
            {{ $pageTitle }}
        </h2>
    </div>
    <nav>
        <ol class="flex items-center gap-1.5">
            <li>
                <a
                    class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400"
                    href="{{ url('/') }}"
                >
                    Home
                    <svg
                        class="stroke-current"
                        width="17"
                        height="16"
                        viewBox="0 0 17 16"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M6.0765 12.667L10.2432 8.50033L6.0765 4.33366"
                            stroke=""
                            stroke-width="1.2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>
                </a>
            </li>
            @if(count($breadcrumbs) > 0)
                @foreach($breadcrumbs as $breadcrumb)
                    <li>
                        <a
                            class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400"
                            href="{{ $breadcrumb['url'] ?? '#' }}"
                        >
                            {{ $breadcrumb['label'] }}
                            <svg
                                class="stroke-current"
                                width="17"
                                height="16"
                                viewBox="0 0 17 16"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M6.0765 12.667L10.2432 8.50033L6.0765 4.33366"
                                    stroke=""
                                    stroke-width="1.2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </a>
                    </li>
                @endforeach
            @endif
            <li class="text-sm text-gray-800 dark:text-white/90">
                {{ $pageTitle }}
            </li>
        </ol>
    </nav>
</div>
