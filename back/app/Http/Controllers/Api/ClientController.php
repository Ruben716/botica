<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        return response()->json(Client::all(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'nullable|email|unique:clients,email',
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $client = Client::create($validated);

        return response()->json($client, 201);
    }

    public function show($id)
    {
        $client = Client::findOrFail($id);
        return response()->json($client, 200);
    }

    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);

        $validated = $request->validate([
            'name'    => 'string|max:255',
            'email'   => 'nullable|email|unique:clients,email,' . $client->id,
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $client->update($validated);

        return response()->json($client, 200);
    }

    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return response()->json(['message' => 'Cliente eliminado'], 200);
    }
}
