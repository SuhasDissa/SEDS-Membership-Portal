<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Component;

class Register extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:255|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email:rfc,dns|unique:users,email',
            'password' => 'required|min:8|max:255|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/',
        ];
    }

    public function messages(): array
    {
        return [
            'name.regex' => 'Name must only contain letters and spaces',
            'name.min' => 'Name must be at least 3 characters',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, and one number',
        ];
    }

    public function register()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
        ]);

        auth()->login($user);

        // Send verification email
        $user->sendEmailVerificationNotification();

        session()->flash('success', 'Registration successful! Please check your email to verify your account.');

        return redirect()->route('onboarding');
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
