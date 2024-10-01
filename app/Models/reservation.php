<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class reservation extends Model
{
    use HasFactory;
    protected $fillable = [
        
        'DateDebut',
        'DateFin',
    ];

    public function Client()
{
    return $this->belongsTo(Client::class, 'client_id');
}
public function Terrain()
{
    return $this->belongsTo(Terrain::class, 'terrains_id');
}
   
}