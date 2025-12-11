<?php

namespace App\Livewire\Contributions;

use App\Models\Contribution;
use Livewire\Component;

class Edit extends Component
{
    public Contribution $contribution;
    public string $title = '';
    public string $description = '';
    public string $date = '';

    public function mount(Contribution $contribution)
    {
        // Check if the user owns this contribution
        if ($contribution->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if the contribution is still editable (pending status)
        if ($contribution->status !== 'pending') {
            session()->flash('error', 'You cannot edit an approved contribution.');
            return redirect()->route('contributions.index');
        }

        $this->contribution = $contribution;
        $this->title = $contribution->title;
        $this->description = $contribution->description;
        $this->date = $contribution->date->format('Y-m-d');
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
        ];
    }

    public function update()
    {
        // Double-check the contribution is still editable
        if ($this->contribution->status !== 'pending') {
            session()->flash('error', 'You cannot edit an approved contribution.');
            return redirect()->route('contributions.index');
        }

        $this->validate();

        $this->contribution->update([
            'title' => $this->title,
            'description' => $this->description,
            'date' => $this->date,
        ]);

        session()->flash('success', 'Contribution updated successfully!');

        return redirect()->route('contributions.index');
    }

    public function delete()
    {
        // Only allow deletion of pending contributions
        if ($this->contribution->status !== 'pending') {
            session()->flash('error', 'You cannot delete an approved contribution.');
            return redirect()->route('contributions.index');
        }

        $this->contribution->delete();

        session()->flash('success', 'Contribution deleted successfully!');

        return redirect()->route('contributions.index');
    }

    public function render()
    {
        return view('livewire.contributions.edit');
    }
}
