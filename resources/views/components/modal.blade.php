@props([
    'id',
    'title',
    'description' => null,
    'size' => 'lg',
])

@php
    $sizes = [
        'sm' => 'max-w-md',
        'md' => 'max-w-lg',
        'lg' => 'max-w-2xl',
        'xl' => 'max-w-3xl',
        '2xl' => 'max-w-4xl',
        'full' => 'max-w-5xl',
    ];

    $modalSize = $sizes[$size] ?? $sizes['lg'];
@endphp

<div
    id="{{ $id }}"
    class="fixed inset-0 z-[100] hidden items-center justify-center bg-slate-950/50 p-3 backdrop-blur-sm sm:p-5"
    role="presentation"
    onclick="if (event.target === this) toggleModal('{{ $id }}')"
>
    <div
        class="relative flex w-full {{ $modalSize }} max-h-[calc(100dvh-1.5rem)] flex-col overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-2xl shadow-slate-950/20 sm:max-h-[calc(100dvh-2.5rem)] sm:rounded-3xl"
        role="dialog"
        aria-modal="true"
        aria-labelledby="{{ $id }}-title"
        aria-describedby="{{ $description ? $id . '-description' : '' }}"
    >
        {{-- HEADER --}}
        <div class="flex shrink-0 items-start justify-between gap-4 border-b border-slate-100 px-5 py-5 sm:px-7 sm:py-6">
            <div class="min-w-0">
                <h2
                    id="{{ $id }}-title"
                    class="text-lg font-semibold tracking-tight text-slate-900 sm:text-xl"
                >
                    {{ $title }}
                </h2>

                @if ($description)
                    <p
                        id="{{ $id }}-description"
                        class="mt-1.5 max-w-2xl text-sm leading-6 text-slate-500"
                    >
                        {{ $description }}
                    </p>
                @endif
            </div>

            <button
                type="button"
                onclick="toggleModal('{{ $id }}')"
                class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-xl text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-300"
                aria-label="Tutup modal"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M6 6l12 12M18 6L6 18"
                    />
                </svg>
            </button>
        </div>

        {{-- CONTENT --}}
        <div class="min-h-0 flex-1 overflow-y-auto overscroll-contain">
            {{ $slot }}
        </div>
    </div>
</div>
