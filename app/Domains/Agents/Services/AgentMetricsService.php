<?php 

namespace App\Domains\Agents\Services;

use App\Domains\Agents\Models\Agent;

class AgentMetricsService
{
    public function totalTimeInStatus(Agent $agent, string $status): int
    {
        $histories = $agent->statusHistories()->where('status', $status)->get();

        $totalSeconds = 0;

        foreach ($histories as $history) {
            $start = $history->started_at;
            $end = $history->ended_at ?? now();
            $totalSeconds += $end->diffInSeconds($start);
        }

        return $totalSeconds; // devuelve tiempo total en segundos
    }
}