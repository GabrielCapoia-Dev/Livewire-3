<?php

namespace App\Livewire\Pages\Users;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.panel')]
class Users extends Component
{
    public function render()
    {
        $users = User::orderBy('name')->get();

        return view('livewire.pages.users.users', compact('users'));
    }
}
