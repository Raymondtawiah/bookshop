@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand name="Visa Resources" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground">
            <img src="{{ asset('favicon.jpg') }}" alt="Logo" class="w-8 h-8 object-contain rounded-md">
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="Visa Resources" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground">
            <img src="{{ asset('favicon.jpg') }}" alt="Logo" class="w-8 h-8 object-contain rounded-md">
        </x-slot>
    </flux:brand>
@endif
