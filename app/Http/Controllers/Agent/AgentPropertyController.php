<?php

namespace App\Http\Controllers\Agent;

use App\Models\Property;
use App\Models\PropertyType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AgentPropertyController extends Controller
{
    public function AgentAllProperty()
    {
        $agentId = Auth::id(); // Use Auth::id() for cleaner code
        $property = Property::where('agent_id', $agentId)->latest()->get();
        //return view('agent.property.all_property', compact('property'));
        return view('agent.property.all_property', ['property' => $property]);
    } // End Method
}
