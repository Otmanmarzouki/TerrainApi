<?php

namespace App\Http\Controllers;

use App\Models\Terrain;
use App\Http\Requests\StoreTerrainRequest;
use App\Http\Requests\UpdateTerrainRequest;
use Illuminate\Http\Request;

use function Termwind\terminal;

class TerrainController extends Controller
{
   
    public function index()
    { 
        $Terrains = Terrain::all();
        if ($Terrains->isEmpty()) { 
            return response()->json(['message' =>'There is no terrain recorded']);
          
           
           }else
           return response()->json($Terrains);
    }


    public function create(Request $request)
    {
        $Terrain = new Terrain();
         $request->validate([
            'NomDeTerrain'=>'required',
            'IdentifiantDeTerrain'=>'required',
            'Capacité'=>'required',
            'activité'=>'required',
       
        ]);
        $Terrain->NomDeTerrain=$request->NomDeTerrain;
        $Terrain->IdentifiantDeTerrain=$request->IdentifiantDeTerrain;
        $Terrain->Capacité=$request->Capacité;
        $Terrain->activité = $request->activité;
        
        $result= $Terrain->save();
        if($result) {
          
            return response()->json(['message' =>'Terrain added correctly']);
        }else {
            return response()->json(['errors'=>$request->validate->errors()]);
        }
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'NomDeTerrain'=>'required',
            'IdentifiantDeTerrain'=>'required',
            'Capacité'=>'required',
            'activité'=>'required',
       
        ]);
       

        $Terrain = Terrain::find($id);   
        $Terrain->NomDeTerrain = $request->NomDeTerrain;        
        $Terrain->IdentifiantDeTerrain = $request->IdentifiantDeTerrain; 
        $Terrain->Capacité = $request->Capacité;
        $Terrain->activité = $request->activité;
        $result= $Terrain->save();
       if($result) {
          
            return response()->json(['message' =>'Terrain updated correctly']);
        }else {
            return response()->json(['errors'=>$request->validate->errors()]);
        }
    }

    public function destroy($id)
    {
        $terrain = Terrain::find($id);
        $terrain->delete();
        return response()->json(['message' =>'Terrain has been removed']);

       
    }
    
}
