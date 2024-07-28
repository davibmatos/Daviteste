<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRegistrationRequest;
use App\Models\Registration;
use App\Repositories\RegistrationRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RegistrationController extends Controller
{
    protected $registrationRepo;

    public function __construct(RegistrationRepositoryInterface $registrationRepo)
    {
        $this->registrationRepo = $registrationRepo;
    }

    public function index(Request $request)
    {
        try {
            $registrations = $this->registrationRepo->search($request->only(['cpf', 'name', 'phone']));
            return response()->json(['data' => $registrations]);
        } catch (\Exception $e) {
            Log::error('Error fetching registrations: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao buscar registros: ' . $e->getMessage()], 500);
        }
    }

    public function store(StoreRegistrationRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $registration = Registration::create($validatedData);
            return response()->json(['message' => 'Registration created successfully', 'data' => $registration], 201);
        } catch (QueryException $e) {
            Log::error('Error creating registration: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao criar registro: ' . $e->getMessage()], 400);
        }
    }

    public function show($id)
    {
        try {
            $registration = Registration::with(['addresses' => function ($query) {
                $query->whereIn('type', ['residential', 'commercial']);
            }])->findOrFail($id);

            return response()->json($registration);
        } catch (\Exception $e) {
            Log::error('Error fetching registration details: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao buscar detalhes do registro: ' . $e->getMessage()], 404);
        }
    }       
}
