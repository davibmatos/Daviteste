<?php

namespace App\Repositories;

use App\Models\Registration;

class RegistrationRepository implements RegistrationRepositoryInterface
{
    public function search($filters)
    {
        $query = Registration::with(['addresses' => function ($query) {
            $query->whereIn('type', ['residential', 'commercial']);
        }]);

        if (!empty($filters['cpf'])) {
            $query->where('cpf', 'like', '%' . $filters['cpf'] . '%');
        }

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (!empty($filters['phone'])) {
            $query->where('phone', 'like', '%' . $filters['phone'] . '%');
        }

        return $query->get();
    }
}
