<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;

class ProfileSettings extends Component
{
    use WithFileUploads;

    public $name = '';
    public $email = '';
    public $university_id = '';
    public $faculty = '';
    public $department = '';
    public $phone = '';
    public $bio = '';
    public $skills = '';
    public $interests = '';
    public $avatar = null;
    public $avatar_url = '';
    public $current_password = '';
    public $new_password = '';
    public $new_password_confirmation = '';

    public function rules()
    {
        return [
            'name' => 'required|string|min:3|max:255|regex:/^[a-zA-Z\s]+$/',
            'university_id' => 'required|string|regex:/^[0-9]{6}[A-Z]$/',
            'faculty' => 'required|in:Engineering,IT,Architecture,Business,Science,Other',
            'department' => 'required|string|min:3|max:255',
            'phone' => 'required|string|regex:/^[+]?[0-9\s\-()]{7,20}$/',
            'bio' => 'nullable|string|max:500',
            'skills' => 'nullable|string|max:500',
            'interests' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'current_password' => 'nullable|required_with:new_password|string',
            'new_password' => 'nullable|min:8|max:255|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/',
        ];
    }

    public function messages()
    {
        return [
            'name.regex' => 'Name must only contain letters and spaces',
            'name.min' => 'Name must be at least 3 characters',
            'university_id.regex' => 'University ID must be 6 digits followed by a letter (e.g., 230152X)',
            'department.min' => 'Department name must be at least 3 characters',
            'phone.regex' => 'Please enter a valid phone number (e.g., +94 77 123 4567)',
            'avatar.mimes' => 'Avatar must be a JPEG, JPG, PNG, or WebP image',
            'current_password.required_with' => 'Current password is required to set a new password',
            'new_password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, and one number',
            'new_password.confirmed' => 'Password confirmation does not match',
        ];
    }

    public function faculties()
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

    public function mount()
    {
        $user = auth()->user();

        $this->name = $user->name;
        $this->email = $user->email;
        $this->university_id = $user->university_id;
        $this->faculty = $user->faculty;
        $this->department = $user->department;
        $this->phone = $user->phone;
        $this->bio = $user->bio ?? '';
        $this->skills = $user->skills ? implode(', ', $user->skills) : '';
        $this->interests = $user->interests ? implode(', ', $user->interests) : '';
        $this->avatar_url = $user->avatar_url ?? '';
    }

    public function updateProfile()
    {
        $this->validate();

        $user = auth()->user();

        // Verify current password if attempting to change password
        if ($this->new_password) {
            // For Google OAuth users, allow setting password without current password
            // Only if they have a google_id and haven't manually set their password yet
            $isGoogleUser = !empty($user->google_id);

            if (!$isGoogleUser || $this->current_password) {
                // Regular users or Google users who entered a current password
                if (!$this->current_password) {
                    $this->addError('current_password', 'Current password is required to change password.');
                    return;
                }

                if (!Hash::check($this->current_password, $user->password)) {
                    $this->addError('current_password', 'The current password is incorrect.');
                    return;
                }
            }
        }

        $avatarPath = $user->avatar_url;

        // Handle avatar upload
        if ($this->avatar) {
            $avatarPath = $this->avatar->store('avatars', 'public');
        }

        // Process skills and interests from comma-separated to arrays
        $skillsArray = $this->skills ? array_map('trim', explode(',', $this->skills)) : null;
        $interestsArray = $this->interests ? array_map('trim', explode(',', $this->interests)) : null;

        // Update profile data
        $updateData = [
            'name' => $this->name,
            'university_id' => $this->university_id,
            'faculty' => $this->faculty,
            'department' => $this->department,
            'phone' => $this->phone,
            'bio' => $this->bio,
            'skills' => $skillsArray,
            'interests' => $interestsArray,
            'avatar_url' => $avatarPath,
        ];

        // Add password to update if changing
        if ($this->new_password) {
            $updateData['password'] = bcrypt($this->new_password);
        }

        $passwordChanged = !empty($this->new_password);

        $user->update($updateData);

        // Clear password fields after update
        $this->current_password = '';
        $this->new_password = '';
        $this->new_password_confirmation = '';

        $message = 'Profile updated successfully!';
        if ($passwordChanged) {
            $message = 'Profile and password updated successfully!';
        }

        session()->flash('success', $message);
    }

    public function render()
    {
        return view('livewire.settings.profile-settings');
    }
}
