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
        'Nom_Terrain' => 'required|string|max:255',
        'type_Terrain' => 'required|string|max:255',
        'Capacité' => 'required|integer',
        'activité' => 'required|string|max:255',
        'prix' => 'required|numeric',
        'dimension1' => 'required|string|max:255', 
        'dimension2' => 'required|string|max:255'
       
        ]);
       $Terrain->Nom_Terrain = $request->Nom_Terrain;
       $Terrain->type_Terrain = $request->type_Terrain;
       $Terrain->Capacité = $request->Capacité;
       $Terrain->activité = $request->activité;
       $Terrain->prix = $request->prix;
       $Terrain->dimension1 = $request->dimension1; 
       $Terrain->dimension2 = $request->dimension2;
        
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

    public function destroy(Request $request)
    {
        $terrainId = $request->query('id');
        $terrain = Terrain::findOrFail($terrainId);
        $terrain->delete();
    
        return response()->json(['message' => 'Terrain deleted successfully.']);
    }
    public function getTerainById($id) {
        // Use find instead of findOrFail
        $terrain = Terrain::find($id);

        if ($terrain) {
            return response()->json($terrain);
        }

        return response()->json([
            'message' => 'Terrain not found',
            'id' => $id,
        ], 404);
    
    
}
}