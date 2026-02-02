<?php

namespace App\Services;

use App\Models\Agent;

class AgentService
{
    public function getAll()
    {
        return Agent::all();
    }

    public function getById($id)
    {
        return Agent::find($id);
    }

    public function create($data)
    {
        return Agent::create($data);
    }

    public function update($id, $data)
    {
        $agent = Agent::find($id);
        if ($agent) {
            $agent->update($data);
            return $agent;
        }
        return null;
    }

    public function delete($id)
    {
        $agent = Agent::find($id);
        if ($agent) {
            $agent->delete();
            return true;
        }
        return false;
    }
}
