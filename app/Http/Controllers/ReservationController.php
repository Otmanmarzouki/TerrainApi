<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Clients;
use App\Models\Terrain;
use App\Http\Requests\StorereservationRequest;
use App\Http\Requests\UpdatereservationRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function index()
    {
        $reservation = Reservation::all();
        if ($reservation->isEmpty()) {
            return response()->json(['message' => 'There is no terrain recorded']);
        } else
            return response()->json($reservation);
    }


    public function create(Request $request)
    {
       $customer = Clients::firstOrCreate([
            'Prenom' => $request->Prenom,
            'Nom' => $request->Nom,
            'Email' => $request->Email,
            'Tel' => $request->Tel
        ]);
        $customer_id = Clients::where('Email', $request->Email)->first()->id; 
        $terrain_id = Terrain::where('activité', $request->activité)->first()->id; 
        
        $firstDate = Carbon::createFromFormat('Y-m-d H:i:s',$request['DateDebut']);
        $secondDate = Carbon::createFromFormat('Y-m-d H:i:s',$request['DateFin']);
       
       
       if(Reservation::where('DateDebut', '<=', $firstDate)->where('DateFin', '>=',$secondDate)->exists()){
        return response()->json(['message' => 'Terrain Already booked']);
       }
       else{
        $reservation = new Reservation();
        $reservation->terrains_id = $terrain_id;
        $reservation->client_id =  $customer_id;
        $reservation->DateDebut = $request['DateDebut'];
        $reservation->DateFin = $request['DateFin'];
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
}
