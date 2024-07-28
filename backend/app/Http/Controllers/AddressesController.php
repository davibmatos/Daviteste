<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Addresses;
use Illuminate\Http\Request;

class AddressesController extends Controller
{
    public function index()
    {
        return Addresses::all();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'registration_id' => 'required|exists:registrations,id',
            'type' => 'required|in:residential,commercial',
            'cep' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'number' => 'required|string|max:255',
            'complement' => 'nullable|string|max:255',
            'neighborhood' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
        ]);

        $address = Addresses::create($validatedData);
        return response()->json($address, 201);
    }

    public function show($id)
    {
        return Addresses::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'registration_id' => 'required|exists:registrations,id',
            'type' => 'required|in:residential,commercial',
            'cep' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'number' => 'required|string|max:255',
            'complement' => 'nullable|string|max:255',
            'neighborhood' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
        ]);

        $address = Addresses::findOrFail($id);
        $address->update($validatedData);
        return response()->json($address, 200);
    }

    public function destroy($id)
    {
        $address = Addresses::findOrFail($id);
        $address->delete();
        return response()->json(null, 204);
    }
}
