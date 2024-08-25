<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Terrain extends Model
{
    use HasFactory;


    protected $fillable = [
        'Nom_Terrain',
        'type_Terrain',
        'Capacité',
        'activité',
        'prix',
        'dimension1',
        'dimension2'
    ];



}