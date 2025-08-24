<?php

use App\Models\Member;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.registration')] class extends Component {
    public int $currentStep = 1;
    public int $totalSteps = 4;

    #[Rule('required|string|max:255')]
    public string $first_name = '';

    #[Rule('required|string|max:255')]
    public string $last_name = '';

    #[Rule('required|email|unique:members,email')]
    public string $email = '';

    #[Rule('required|string|max:20')]
    public string $phone = '';

    #[Rule('required|string|unique:members,student_id')]
    public string $student_id = '';

    #[Rule('required|in:1,2,3,4,graduate')]
    public string $year = '';

    #[Rule('required|string|max:255')]
    public string $faculty = '';

    #[Rule('required|string|max:255')]
    public string $department = '';

    #[Rule('required|string|max:1000')]
    public string $motivation = '';

    #[Rule('array')]
    public array $interests = [];

    #[Rule('boolean')]
    public bool $has_programming_experience = false;

    #[Rule('nullable|string|max:500')]
    public string $programming_languages = '';

    #[Rule('boolean')]
    public bool $has_space_projects_experience = false;

    #[Rule('nullable|string|max:500')]
    public string $space_projects_description = '';

    public bool $submitted = false;

    public function nextStep()
    {
        $this->validateCurrentStep();
        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function goToStep($step)
    {
        if ($step <= $this->currentStep) {
            $this->currentStep = $step;
        } elseif ($step == $this->currentStep + 1) {
            try {
                $this->validateCurrentStep();
                $this->currentStep = $step;
            } catch (\Exception $e) {
                return;
            }
        }
    }

    private function validateCurrentStep()
    {
        switch ($this->currentStep) {
            case 1:
                $this->validate([
                    'first_name' => 'required|string|max:255',
                    'last_name' => 'required|string|max:255',
                    'email' => 'required|email|unique:members,email',
                    'phone' => 'required|string|max:20',
                ]);
                break;
            case 2:
                $this->validate([
                    'student_id' => 'required|string|unique:members,student_id',
                    'year' => 'required|in:1,2,3,4,graduate',
                    'faculty' => 'required|string|max:255',
                    'department' => 'required|string|max:255',
                ]);
                break;
            case 3:
                $this->validate([
                    'motivation' => 'required|string|max:1000',
                ]);
                break;
            case 4:
                // Optional validation for step 4 since these are optional fields
                if ($this->has_programming_experience) {
                    $this->validate([
                        'programming_languages' => 'nullable|string|max:500',
                    ]);
                }
                if ($this->has_space_projects_experience) {
                    $this->validate([
                        'space_projects_description' => 'nullable|string|max:500',
                    ]);
                }
                break;
        }
    }

    private function hasValidatedStep($step)
    {
        try {
            $currentStep = $this->currentStep;
            $this->currentStep = $step;
            $this->validateCurrentStep();
            $this->currentStep = $currentStep;
            return true;
        } catch (\Exception $e) {
            $this->currentStep = $currentStep;
            return false;
        }
    }

    public function submit()
    {
        // Validate all steps before submission
        for ($i = 1; $i <= $this->totalSteps; $i++) {
            $currentStep = $this->currentStep;
            $this->currentStep = $i;
            $this->validateCurrentStep();
            $this->currentStep = $currentStep;
        }

        Member::create([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'student_id' => $this->student_id,
            'year' => $this->year,
            'faculty' => $this->faculty,
            'department' => $this->department,
            'motivation' => $this->motivation,
            'interests' => json_encode($this->interests), // Store as JSON
            'has_programming_experience' => $this->has_programming_experience,
            'programming_languages' => $this->programming_languages,
            'has_space_projects_experience' => $this->has_space_projects_experience,
            'space_projects_description' => $this->space_projects_description,
        ]);

        $this->submitted = true;
    }

    public function toggleInterest($interest)
    {
        if (in_array($interest, $this->interests)) {
            $this->interests = array_values(array_diff($this->interests, [$interest]));
        } else {
            $this->interests[] = $interest;
        }
    }

    public function getProgressPercentage()
    {
        return ($this->currentStep / $this->totalSteps) * 100;
    }

    public function updatedHasProgrammingExperience($value)
    {
        if (!$value) {
            $this->programming_languages = '';
        }
    }

    public function updatedHasSpaceProjectsExperience($value)
    {
        if (!$value) {
            $this->space_projects_description = '';
        }
    }
};
?>

<div class="min-h-screen py-8 lg:py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        @if(!$submitted)
            <!-- Header -->
            <div class="text-center mb-12 flex flex-col items-center">
                    <x-mary-header 
                        title="Member Registration" 
                        subtitle="Join the Space Exploration and Development Society at University of Moratuwa" 
                        size="text-4xl lg:text-5xl" 
                        class="text-gray-900 mx-auto" />
                <!-- Progress -->
                <div class="max-w-md mx-auto mt-8 w-full">
                    <div class="flex items-center justify-between mb-2">
                        <x-mary-badge value="Step {{ $currentStep }} of {{ $totalSteps }}" class="badge-ghost" />
                        <x-mary-badge value="{{ number_format($this->getProgressPercentage()) }}%" class="badge-primary" />
                    </div>
                    <x-mary-progress 
                        value="{{ $this->getProgressPercentage() }}" 
                        max="100" 
                        class="progress-primary" />
                </div>
            </div>

            <!-- Step Navigation using Mary UI Steps -->
            <div class="flex items-center">
            <div class="max-w-5xl mx-auto mb-8 px-4">
                <div class="hidden md:block">
                    <x-mary-steps wire:model="currentStep" class="w-full">
                        <x-mary-step step="1" text="Personal Info" icon="s-user">
                        </x-mary-step>
                        <x-mary-step step="2" text="Academic Info" icon="s-academic-cap">
                        </x-mary-step>
                        <x-mary-step step="3" text="Motivation" icon="s-heart">
                        </x-mary-step>
                        <x-mary-step step="4" text="Skills" icon="s-wrench-screwdriver">
                        </x-mary-step>
                    </x-mary-steps>
                </div>
            </div>
                
                <!-- Mobile Step Indicator -->
                <div class="md:hidden">
                    <div class="flex justify-center space-x-2">
                        @for($i = 1; $i <= $totalSteps; $i++)
                            <div class="flex items-center">
                                <x-mary-avatar class="w-8 h-8 text-sm font-medium {{ $i == $currentStep ? 'bg-blue-600 text-white' : ($i < $currentStep ? 'bg-green-600 text-white' : 'bg-gray-300 text-gray-600') }}">
                                    {{ $i < $currentStep ? '✓' : $i }}
                                </x-mary-avatar>
                                @if($i < $totalSteps)
                                    <div class="w-8 h-0.5 {{ $i < $currentStep ? 'bg-green-500' : 'bg-gray-300' }}"></div>
                                @endif
                            </div>
                        @endfor
                    </div>
                    <div class="text-center mt-2">
                        <x-mary-badge value="@if($currentStep == 1) Personal Info @elseif($currentStep == 2) Academic Info @elseif($currentStep == 3) Motivation @else Skills @endif" class="badge-ghost text-gray-600" />
                    </div>
                </div>
            </div>

            <!-- Form Container -->
            <div class="max-w-5xl mx-auto px-4">
                <x-mary-card class="shadow-xl bg-white/95 border border-gray-200 backdrop-blur-sm">
                    <x-mary-form wire:submit="submit">
                        
                        @if($currentStep == 1)
                            <!-- Step 1: Personal Information -->
                            <div class="space-y-8">
                                <x-mary-header title="Personal Information" subtitle="Let's start with your basic information" />
                                
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
                                    <x-mary-input 
                                        wire:model="first_name" 
                                        label="First Name" 
                                        placeholder="Enter your first name" 
                                        icon="o-user"
                                        required />

                                    <x-mary-input 
                                        wire:model="last_name" 
                                        label="Last Name" 
                                        placeholder="Enter your last name" 
                                        icon="o-user"
                                        required />

                                    <x-mary-input 
                                        wire:model="email" 
                                        type="email" 
                                        label="University Email" 
                                        placeholder="your.email@uom.lk" 
                                        icon="o-envelope"
                                        required />

                                    <x-mary-input 
                                        wire:model="phone" 
                                        type="tel" 
                                        label="Phone Number" 
                                        placeholder="+94 71 234 5678" 
                                        icon="o-phone"
                                        required />
                                </div>
                            </div>
                        @endif

                        @if($currentStep == 2)
                            <!-- Step 2: Academic Information -->
                            <div class="space-y-8">
                                <x-mary-header title="Academic Information" subtitle="Tell us about your academic background" />
                                
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
                                    <x-mary-input 
                                        wire:model="student_id" 
                                        label="Student ID" 
                                        placeholder="e.g., 200123456" 
                                        icon="o-identification"
                                        required />

                                    <x-mary-select 
                                        wire:model="year" 
                                        label="Academic Year" 
                                        placeholder="Select Academic Year" 
                                        icon="o-academic-cap"
                                        :options="[
                                            ['id' => '1', 'name' => '1st Year Undergraduate'],
                                            ['id' => '2', 'name' => '2nd Year Undergraduate'],
                                            ['id' => '3', 'name' => '3rd Year Undergraduate'],
                                            ['id' => '4', 'name' => '4th Year Undergraduate'],
                                            ['id' => 'graduate', 'name' => 'Graduate Student']
                                        ]"
                                        option-value="id"
                                        option-label="name"
                                        required />

                                    <x-mary-input 
                                        wire:model="faculty" 
                                        label="Faculty" 
                                        placeholder="e.g., Faculty of Engineering" 
                                        icon="o-building-library"
                                        required />

                                    <x-mary-input 
                                        wire:model="department" 
                                        label="Department" 
                                        placeholder="e.g., Computer Science and Engineering" 
                                        icon="o-building-office"
                                        required />
                                </div>
                            </div>
                        @endif

                        @if($currentStep == 3)
                            <!-- Step 3: Interests & Motivation -->
                            <div class="space-y-8">
                                <x-mary-header title="Interests & Motivation" subtitle="Tell us about your interests and motivation to join" />
                                
                                <div class="space-y-8">
                                    <div>
                                        <x-mary-header title="Areas of Interest" subtitle="Select all that apply" size="text-lg" />
                                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-6">
                                            @php
                                                $interestOptions = [
                                                    ['value' => 'Rocket Engineering', 'icon' => 'o-rocket-launch'],
                                                    ['value' => 'Satellite Technology', 'icon' => 'o-wifi'],
                                                    ['value' => 'Space Exploration', 'icon' => 'o-globe-alt'],
                                                    ['value' => 'Astrophysics', 'icon' => 'o-star'],
                                                    ['value' => 'Mission Planning', 'icon' => 'o-map'],
                                                    ['value' => 'Space Systems', 'icon' => 'o-cpu-chip'],
                                                    ['value' => 'Robotics', 'icon' => 'o-wrench-screwdriver'],
                                                    ['value' => 'Data Analysis', 'icon' => 'o-chart-bar'],
                                                    ['value' => 'Research & Development', 'icon' => 'o-beaker']
                                                ];
                                            @endphp

                                            @foreach($interestOptions as $option)
                                                <x-mary-checkbox
                                                    wire:model="interests"
                                                    value="{{ $option['value'] }}"
                                                    label="{{ $option['value'] }}"
                                                    class="checkbox-primary" />
                                            @endforeach
                                        </div>
                                    </div>

                                    <x-mary-textarea
                                        wire:model="motivation"
                                        label="Why do you want to join SEDS?"
                                        rows="6"
                                        placeholder="Share your passion for space exploration, your goals, and how you hope to contribute to SEDS..."
                                        required />
                                </div>
                            </div>
                        @endif

                        @if($currentStep == 4)
                            <!-- Step 4: Experience & Skills -->
                            <div class="space-y-8">
                                <x-mary-header title="Experience & Skills" subtitle="Tell us about your technical background and experience" />
                                
                                <div class="space-y-8">
                                    <!-- Programming Experience Card -->
                                    <x-mary-card class="border border-blue-200 bg-blue-50/80 backdrop-blur-sm">
                                        <x-mary-header>
                                            <x-slot:title>
                                                <div class="flex items-center gap-3">
                                                    <x-mary-avatar class="w-10 h-10 bg-blue-600">
                                                        <x-mary-icon name="o-code-bracket" class="w-5 h-5 text-white" />
                                                    </x-mary-avatar>
                                                    Programming Experience
                                                </div>
                                            </x-slot:title>
                                        </x-mary-header>

                                        <x-mary-checkbox
                                            wire:model.live="has_programming_experience"
                                            label="I have programming experience"
                                            class="checkbox-primary" />
                                        
                                        @if($has_programming_experience)
                                            <div class="mt-6">
                                                <x-mary-textarea
                                                    wire:model="programming_languages"
                                                    label="Programming Languages & Technologies"
                                                    rows="4"
                                                    placeholder="e.g., Python, C++, JavaScript, MATLAB, Arduino, ROS, TensorFlow, etc." />
                                            </div>
                                        @endif
                                    </x-mary-card>

                                    <!-- Space Projects Experience Card -->
                                    <x-mary-card class="border border-indigo-200 bg-indigo-50/80 backdrop-blur-sm">
                                        <x-mary-header>
                                            <x-slot:title>
                                                <div class="flex items-center gap-3">
                                                    <x-mary-avatar class="w-10 h-10 bg-indigo-600">
                                                        <x-mary-icon name="o-rocket-launch" class="w-5 h-5 text-white" />
                                                    </x-mary-avatar>
                                                    Space Projects Experience
                                                </div>
                                            </x-slot:title>
                                        </x-mary-header>

                                        <x-mary-checkbox
                                            wire:model.live="has_space_projects_experience"
                                            label="I have worked on space-related projects"
                                            class="checkbox-primary" />
                                        
                                        @if($has_space_projects_experience)
                                            <div class="mt-6">
                                                <x-mary-textarea
                                                    wire:model="space_projects_description"
                                                    label="Describe Your Space Projects"
                                                    rows="4"
                                                    placeholder="Describe any space-related projects, research, competitions (CanSat, Model rocketry), internships, or coursework..." />
                                            </div>
                                        @endif
                                    </x-mary-card>
                                </div>
                            </div>
                        @endif

                        <!-- Navigation Buttons -->
                        <x-slot:actions>
                            <div class="flex items-center justify-between w-full">
                                <x-mary-button 
                                    type="button" 
                                    wire:click="previousStep" 
                                    class="btn-ghost"
                                    :disabled="$currentStep == 1">
                                    <x-mary-icon name="o-chevron-left" class="w-4 h-4" />
                                    Previous
                                </x-mary-button>

                                @if($currentStep < $totalSteps)
                                    <x-mary-button 
                                        type="button" 
                                        wire:click="nextStep" 
                                        class="btn-primary">
                                        Continue
                                        <x-mary-icon name="o-chevron-right" class="w-4 h-4" />
                                    </x-mary-button>
                                @else
                                    <x-mary-button 
                                        type="submit" 
                                        class="btn-primary">
                                        <x-mary-icon name="o-paper-airplane" class="w-4 h-4" />
                                        Submit Application
                                    </x-mary-button>
                                @endif
                            </div>
                        </x-slot:actions>
                    </x-mary-form>
                </x-mary-card>
            </div>
        @else
            <!-- Success Message -->
            <div class="max-w-2xl mx-auto text-center px-4">
                <x-mary-card class="shadow-xl bg-white/95 border border-gray-200 backdrop-blur-sm">
                    <div class="text-center">
                        <x-mary-avatar class="w-24 h-24 mx-auto mb-8 bg-success">
                            <x-mary-icon name="o-check-circle" class="w-12 h-12 text-success-content" />
                        </x-mary-avatar>
                        
                        <x-mary-header title="Registration Successful!" subtitle="Your application has been submitted to SEDS University of Moratuwa." size="text-4xl" class="text-gray-900" />
                        
                        <x-mary-alert icon="o-information-circle" class="alert-info mb-8">
                            <x-slot:title>What's Next?</x-slot:title>
                            We'll review your application and contact you at 
                            <strong class="text-info">{{ $email }}</strong> 
                            within 5-7 business days.
                            <br><br>
                            Welcome to the SEDS community!
                        </x-mary-alert>
                        
                        <x-mary-button
                            link="{{ route('home') }}" 
                            class="btn-primary btn-lg">
                            <x-mary-icon name="o-home" class="w-5 h-5" />
                            Return Home
                        </x-mary-button>
                    </div>
                </x-mary-card>
            </div>
        @endif
    </div>
</div>