<div class="p-6">
    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-base-content">Settings</h1>
        <p class="text-base-content/70">Manage your account settings</p>
    </div>

    <div class="max-w-3xl">
        <div class="card bg-base-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="card-body">
                <h2 class="card-title text-xl mb-4">Profile Information</h2>

                <form wire:submit="updateProfile">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Name --}}
                        <div class="md:col-span-2">
                            <x-input label="Full Name" wire:model.blur="name" icon="o-user"
                                hint="At least 3 characters, letters and spaces only" inline />
                        </div>

                        {{-- Email (Read-only) --}}
                        <div class="md:col-span-2">
                            <x-input label="Email" value="{{ $email }}" type="email" icon="o-envelope"
                                hint="Email cannot be changed" readonly disabled inline />
                        </div>

                        {{-- University ID --}}
                        <x-input label="University ID" wire:model.blur="university_id" icon="o-identification"
                            hint="Format: 6 digits followed by a letter (e.g., 230152X)" inline />

                        {{-- Faculty --}}
                        <x-select label="Faculty" wire:model="faculty" :options="$this->faculties()"
                            icon="o-academic-cap" inline />

                        {{-- Department --}}
                        <div class="md:col-span-2">
                            <x-input label="Department" wire:model.blur="department" icon="o-building-library"
                                hint="At least 3 characters" inline />
                        </div>

                        {{-- Phone --}}
                        <div class="md:col-span-2">
                            <x-input label="Phone Number" wire:model.blur="phone" icon="o-phone"
                                hint="Include country code if applicable" inline />
                        </div>
                    </div>

                    {{-- Profile Customization Section --}}
                    <div class="divider my-6">Profile Customization (Optional)</div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Bio --}}
                        <div class="md:col-span-2">
                            <x-textarea label="Bio" wire:model.blur="bio" placeholder="Tell us about yourself..."
                                hint="Max 500 characters" rows="4" />
                        </div>

                        {{-- Skills --}}
                        <div class="md:col-span-2">
                            <x-input label="Skills" wire:model.blur="skills" icon="o-sparkles"
                                placeholder="e.g., Python, JavaScript, Machine Learning"
                                hint="Separate skills with commas" inline />
                        </div>

                        {{-- Interests --}}
                        <div class="md:col-span-2">
                            <x-input label="Interests" wire:model.blur="interests" icon="o-heart"
                                placeholder="e.g., Space Exploration, Robotics, Astrophysics"
                                hint="Separate interests with commas" inline />
                        </div>
                    </div>

                    {{-- Password Change Section --}}
                    <div class="divider my-6">Change Password (Optional)</div>

                    @if(auth()->user()->google_id)
                        <div class="alert alert-info mb-4">
                            <x-icon name="o-information-circle" class="w-5 h-5" />
                            <span>You signed up with Google. You can set a password here to enable email/password login.
                                Leave current password blank if you haven't set one yet.</span>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Current Password --}}
                        <div class="md:col-span-2">
                            <x-input label="Current Password" wire:model.blur="current_password" type="password"
                                icon="o-key" placeholder="Enter current password"
                                hint="{{ auth()->user()->google_id ? 'Optional for Google users' : 'Required to change password' }}"
                                inline />
                        </div>

                        {{-- New Password --}}
                        <div class="md:col-span-2">
                            <x-input label="New Password" wire:model.blur="new_password" type="password" icon="o-key"
                                placeholder="Enter new password"
                                hint="Min 8 characters with uppercase, lowercase, and number" inline />
                        </div>

                        {{-- Confirm New Password --}}
                        <div class="md:col-span-2">
                            <x-input label="Confirm New Password" wire:model.blur="new_password_confirmation"
                                type="password" icon="o-key" placeholder="Confirm new password"
                                hint="Must match new password" inline />
                        </div>
                    </div>

                    {{-- Avatar Upload Section --}}
                    <div class="divider my-6">Profile Photo</div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Profile Photo Upload --}}
                        <div class="md:col-span-2">
                            <label class="label">
                                <span class="label-text flex items-center gap-2">
                                    <x-icon name="o-photo" class="w-5 h-5" />
                                    Profile Photo (Optional)
                                </span>
                            </label>
                            <x-file wire:model="avatar" accept="image/*" crop-after-change
                                hint="Max size: 2MB. Click to upload and crop your profile photo">
                                <img src="{{ auth()->user()->avatar }}" class="h-40 rounded-lg" alt="Profile Avatar" />
                            </x-file>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="btn btn-primary">
                            <x-icon name="o-check-circle" class="w-5 h-5" />
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>