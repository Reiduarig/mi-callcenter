<?php

namespace App\Livewire\Agents;

use Livewire\Component;
use Livewire\WithPagination;
use App\Domains\Agents\Models\Agent;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class AgentIndex extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $agents = Agent::with('user')
            ->whereHas('user', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

            
        return view('livewire.agents.agent-index', [
            'agents' => $agents,
        ])
        ->layout('layouts.app');
    }
}
