<?php

namespace App\Livewire\Agents;

use Livewire\Component;

class AgentForm extends Component
{
    public $agentId;
    public $userId;
    public $employee_code;
    public $status = 'available';
    public $skill_group;

    protected $listeners = [
        'editAgent' => 'loadAgent'
    ];

    protected $rules = [
        'userId' => 'required|exists:users,id',
        'employee_code' => 'required|unique:agents,employee_code',
        'status' => 'required',
    ];

    public function loadAgent($id)
    {
        $agent = Agent::findOrFail($id);
        $this->agentId = $agent->id;
        $this->userId = $agent->user_id;
        $this->employee_code = $agent->employee_code;
        $this->status = $agent->status;
        $this->skill_group = $agent->skill_group;
    }

    public function save()
    {
        $this->validate();

        if ($this->agentId) {
            // Update
            $action = app(UpdateAgent::class);
            $action->execute(Agent::findOrFail($this->agentId), [
                'employee_code' => $this->employee_code,
                'status' => $this->status,
                'skill_group' => $this->skill_group,
            ]);
        } else {
            // Create
            $user = User::findOrFail($this->userId);
            $action = app(CreateAgent::class);
            $action->execute($user, [
                'employee_code' => $this->employee_code,
                'status' => $this->status,
                'skill_group' => $this->skill_group,
            ]);
        }

        $this->resetForm();
        $this->emit('agentSaved');
    }

    public function resetForm()
    {
        $this->agentId = null;
        $this->userId = null;
        $this->employee_code = null;
        $this->status = 'available';
        $this->skill_group = null;
    }


    public function render()
    {
        $users = User::whereDoesntHave('agent')->get(); // usuarios que no son agentes aún
        return view('livewire.agents.agent-form', [
            'users' => $users,
        ]);
    }
}
