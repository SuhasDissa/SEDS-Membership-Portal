<?php

namespace App\Livewire\Onboarding;

use Livewire\Component;
use Livewire\WithFileUploads;

class CompleteProfileForm extends Component
{
    use WithFileUploads;

    public string $university_id = '';
    public string $faculty = '';
    public string $department = '';
    public string $phone = '';
    public $avatar = null;
    public string $avatar_url = '';

    public function rules(): array
    {
        return [
            'university_id' => 'required|string|unique:users,university_id|regex:/^[0-9]{6}[A-Z]$/',
            'faculty' => 'required|in:Engineering,IT,Architecture,Business,Science,Other',
            'department' => 'required|string|min:3|max:255',
            'phone' => 'required|string|regex:/^[+]?[0-9\s\-()]{7,20}$/',
            'avatar' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'university_id.regex' => 'University ID must be 6 digits followed by a letter (e.g., 230152X)',
            'department.min' => 'Department name must be at least 3 characters',
            'phone.regex' => 'Please enter a valid phone number (e.g., +94 77 123 4567)',
            'avatar.mimes' => 'Avatar must be a JPEG, JPG, PNG, or WebP image',
        ];
    }

    public function mount()
    {
        $user = auth()->user();

        // Pre-fill if user has Google avatar
        if ($user->avatar_url) {
            $this->avatar_url = $user->avatar_url;
        }
    }

    public function faculties(): array
    {
        return [
            ['id' => 'Engineering', 'name' => 'Engineering'],
            ['id' => 'IT', 'name' => 'Information Technology'],
            ['id' => 'Architecture', 'name' => 'Architecture'],
            ['id' => 'Business', 'name' => 'Business'],
            ['id' => 'Science', 'name' => 'Science'],
            ['id' => 'Other', 'name' => 'Other'],
        ];
    }

    public function completeProfile()
    {
        $this->validate();

        $user = auth()->user();

        $avatarPath = $user->avatar_url;

        // Handle avatar upload
        if ($this->avatar) {
            $avatarPath = $this->avatar->store('avatars', 'public');
        }

        $user->update([
            'university_id' => $this->university_id,
            'faculty' => $this->faculty,
            'department' => $this->department,
            'phone' => $this->phone,
            'avatar_url' => $avatarPath,
        ]);

        session()->flash('success', 'Profile completed successfully!');

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.onboarding.complete-profile-form');
    }
}
