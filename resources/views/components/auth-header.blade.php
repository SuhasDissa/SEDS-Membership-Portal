@props([
    'title',
    'description',
])

<div class="flex w-full flex-col text-center">
    <x-mary-header :title="$title" :subtitle="$description" size="text-3xl" class="text-center" />
</div>
