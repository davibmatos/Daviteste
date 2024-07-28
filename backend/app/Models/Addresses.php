<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addresses extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id', 'type', 'cep', 'street', 'number', 'complement', 'neighborhood', 'state', 'city'
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}
