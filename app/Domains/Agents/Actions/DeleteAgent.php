<?php 
namespace App\Domains\Agents\Actions;

use App\Domains\Agents\Models\Agent;

class DeleteAgent
{
    public function execute(Agent $agent): void
    {
        $agent->delete();
    }
}