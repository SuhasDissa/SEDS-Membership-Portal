@props([
    'status',
])

@if ($status)
    <x-mary-alert icon="o-check-circle" class="alert-success font-medium text-sm" {{ $attributes }}>
        {{ $status }}
    </x-mary-alert>
@endif
