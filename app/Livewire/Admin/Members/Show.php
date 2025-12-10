<?php

namespace App\Livewire\Admin\Members;

use Livewire\Component;
use App\Models\User;

class Show extends Component
{
    public User $user;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function render()
    {
        return view('livewire.admin.members.show');
    }
}
