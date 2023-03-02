<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class reservation extends Model
{
    use HasFactory;
    protected $fillable = [
        'Nom',
        'Prenom',
        'numTel',
        'email',
        'sport',
        'DateDebut',
        'DateFin',
    ];
}
