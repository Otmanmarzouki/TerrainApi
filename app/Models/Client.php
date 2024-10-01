<?php

namespace App\Models;

use App\Policies\ReservationPolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $fillable = [
        'Prenom',
        'Nom',
        'Email',
        'Tel',
        
    ];
    public function reservation()
{
    return $this->hasMany(Reservation::class);

}

}