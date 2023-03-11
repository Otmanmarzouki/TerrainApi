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

    public function Clients()
{
    return $this->belongsTo(Clients::class);
}
public function Terrain()
{
    return $this->belongsTo(Terrain::class);
}
   
}
