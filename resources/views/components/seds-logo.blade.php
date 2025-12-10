@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'flex items-center gap-3 ' . $class]) }}>
    <x-app-logo-icon class="w-24 h-24 text-white" />
    <div class="flex flex-col">
        <span class="text-xl font-bold text-base-content">SEDS Mora</span>
        <span class="text-xs text-base-content/70">Space Exploration & Development</span>
    </div>
</div>
