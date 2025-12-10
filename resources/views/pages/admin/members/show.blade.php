<x-layouts.app>
    <x-slot:title>{{ $user->name }} - Profile</x-slot:title>
    
    <livewire:admin.members.show :user="$user" />
</x-layouts.app>

