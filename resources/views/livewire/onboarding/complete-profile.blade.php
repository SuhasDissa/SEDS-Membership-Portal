<div class="card bg-base-100 shadow-sm hover:shadow-md transition-shadow">
    <div class="card-body">
        <form wire:submit="completeProfile">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- University --}}
                <div class="md:col-span-2">
                    <x-input label="University" value="{{ \App\Models\User::UNIVERSITY_DATA['university'] }}" icon="o-academic-cap" inline readonly />
                </div>

                {{-- University ID --}}
                <div class="md:col-span-2">
                    <x-input label="University ID" wire:model.blur="university_id" icon="o-identification"
                        placeholder="230152X" hint="Format: 6 digits followed by a letter (e.g., 230152X)" inline />
                </div>

                {{-- Faculty --}}
                <div class="md:col-span-2">
                    <x-select label="Faculty" wire:model.live="faculty" :options="$this->faculties()"
                        placeholder="Select your faculty" icon="o-academic-cap" inline />
                </div>

                {{-- Department --}}
                <div class="md:col-span-2">
                    <x-select label="Department" wire:model.live="department" :options="$this->departments()"
                        placeholder="Select your department" icon="o-building-library" 
                        :disabled="!$faculty" inline />
                </div>

                {{-- Phone --}}
                <div class="md:col-span-2">
                    <x-input label="Phone Number" wire:model.blur="phone" icon="o-phone" placeholder="+94 77 123 4567"
                        hint="Include country code if applicable" inline />
                </div>

                {{-- Profile Photo Upload (Optional) --}}
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

            {{-- Submit Button --}}
            <div class="mt-6">
                <button type="submit" class="btn btn-primary w-full">
                    <x-icon name="o-check-circle" class="w-5 h-5" />
                    Complete Profile
                </button>
            </div>
        </form>
    </div>
</div>