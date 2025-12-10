<?php

namespace App\Livewire\Contributions;

use Livewire\Component;

class Create extends Component
{
    public string $title = '';
    public string $description = '';
    public string $date = '';

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
        ];
    }

    public function submit()
    {
        $this->validate();

        auth()->user()->contributions()->create([
            'title' => $this->title,
            'description' => $this->description,
            'date' => $this->date,
            'status' => 'pending',
        ]);

        session()->flash('success', 'Contribution logged successfully!');

        return redirect()->route('contributions.index');
    }

    public function render()
    {
        return view('livewire.contributions.create');
    }
}
