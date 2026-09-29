@props([
    'label' => 'Submit',
    'loading' => false,
])

<button
    {{ $attributes->merge([
        'type' => 'submit',
        'class' => 'relative inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition-colors disabled:opacity-75 disabled:cursor-not-allowed',
    ]) }}
>
    <span id="{{ $attributes->get('id') }}-btn-text" class="btn-text">{{ $label }}</span>
    <svg id="{{ $attributes->get('id') }}-btn-loader" class="animate-spin ml-2 hidden" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
    </svg>
</button>
