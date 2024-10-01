<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Client;
use App\Models\Terrain;
use App\Http\Requests\StorereservationRequest;
use App\Http\Requests\UpdatereservationRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function index()
    {
        // Get all reservations and eager load the related terrain (activité)
        $reservations = Reservation::with('terrain')->get();
    
        // Check if any reservations exist
        if ($reservations->isEmpty()) {
            return response()->json(['message' => 'There is no terrain recorded']);
        } 
    
        // Return the reservations, including terrain information
        return response()->json($reservations);
    }
    


    public function create(Request $request) {
       $customer = Client::firstOrCreate([
            'Prenom' => $request->Prenom,
            'Nom' => $request->Nom,
            'Email' => $request->Email,
            'Tel' => $request->Tel
        ]);
        $customer_id = $customer->id;
        $terrain_id = Terrain::where('activité', $request->activité)->first()->id; 
        
        $firstDate = Carbon::parse($request->DateDebut)->setTimezone('Europe/Paris'); // Use the appropriate timezone
        $secondDate = Carbon::parse($request->DateFin)->setTimezone('Europe/Paris');
       
       
       if(Reservation::where('DateDebut', '<=', $firstDate)->where('DateFin', '>=',$secondDate)->exists()){
        return response()->json(['message' => 'Terrain Already booked']);
       }
       else{
        $reservation = new Reservation();
        $reservation->terrains_id = $terrain_id;
        $reservation->client_id =  $customer_id;
        $reservation->DateDebut = $firstDate; 
        $reservation->DateFin = $secondDate;
        $result = $reservation->save();
        if ($result) {

            return response()->json(['message' => 'Terrain has been booked']);
        } else {
            return response()->json(['errors' => $request->validate->errors()]);
        }
       }
        

        
    

       
    }

    public function update(Request $request, $id)
    {
       
        $reservation = Reservation::find($id);

        $reservation->DateDebut = $request->DateDebut;
        $reservation->DateFin = $request->DateFin;

        $result = $reservation->save();
        if ($result) {

            return response()->json(['message' => 'Reservation updated correctly']);
        } else {
            return response()->json(['errors' => $request->validate->errors()]);
        }
    }

    public function destroy($id)
    {

        $reservation = Reservation::find($id);
        $reservation->delete();
        return response()->json(['message' => 'Reservation has been removed']);
    }


public function getClientsCountBySport(Request $request)
{
    $terrain = Terrain::where('activité', $request->activité)->first();
    
    if (!$terrain) {
        return response()->json(['message' => 'No terrains found for this sport'], 404);
    }

    $clientCount = Reservation::join('terrains', 'reservations.terrains_id', '=', 'terrains.id')
        ->where('terrains.activité', $request->activité)  
        ->distinct('reservations.client_id')
        ->count('reservations.client_id');  

    return response()->json(['client_count' => $clientCount]);
}
}