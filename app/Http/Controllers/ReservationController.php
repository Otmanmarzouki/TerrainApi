<?php


namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Client;
use App\Models\Terrain;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function index()
    {
       
        $reservations = Reservation::with('terrain')->where('canceled', '!=', true)->get();
    
        if ($reservations->isEmpty()) {
            return response()->json(['message' => 'There is no terrain recorded']);
        }
    
        return response()->json($reservations);
    }
    

    public function create(Request $request)
    {
        $request->validate([
            'Prenom' => 'required|string',
            'Nom' => 'required|string',
            'Email' => 'required|email',
            'Tel' => 'required|string',
            'Sexe' => 'required|string',
            'activité' => 'required|string',
            'DateDebut' => 'required|date',
            'DateFin' => 'required|date|after_or_equal:DateDebut',
        ]);

        $customer = Client::firstOrCreate([
            'Prenom' => $request->Prenom,
            'Nom' => $request->Nom,
            'Email' => $request->Email,
            'Tel' => $request->Tel,
            'Sexe' => $request->Sexe
        ]);

        $customer_id = $customer->id;
        $terrain_id = Terrain::where('activité', $request->activité)->first()->id; 
        
        $firstDate = Carbon::parse($request->DateDebut)->setTimezone('Europe/Paris');
        $secondDate = Carbon::parse($request->DateFin)->setTimezone('Europe/Paris');

        if (Reservation::where('DateDebut', '<=', $firstDate)
            ->where('DateFin', '>=', $secondDate)
            ->exists()) {
            return response()->json(['message' => 'Terrain Already booked']);
        } else {
            $reservation = new Reservation();
            $reservation->terrain_id = $terrain_id;
            $reservation->client_id = $customer_id;
            $reservation->DateDebut = $firstDate;
            $reservation->DateFin = $secondDate;
            $reservation->drafts = true;

            $result = $reservation->save();
            if ($result) {
                return response()->json([
                    'message' => 'Terrain has been booked',
                    'reservation' => $reservation
                ], 201); 
            } else {
                return response()->json(['errors' => $request->validate->errors()], 400);
            }
        }
    }

    public function update(Request $request, $id)
    {
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }
        $request->validate([
            'DateDebut' => 'required|date',
            'DateFin' => 'required|date|after_or_equal:DateDebut',
            'drafts' => 'boolean',
        ]);

        $reservation->DateDebut = $request->DateDebut;
        $reservation->DateFin = $request->DateFin;

        if (isset($request->drafts)) {
            $reservation->drafts = $request->drafts;
        }

        $result = $reservation->save();
        if ($result) {
            return response()->json(['message' => 'Reservation updated correctly']);
        } else {
            return response()->json(['errors' => $request->validate->errors()]);
        }
    }
    
    public function updateStatus($id)
    {
        $reservation = Reservation::find($id);
    
        if (!$reservation) {
            return response()->json(['error' => 'Reservation not found'], 404);
        }
    
        $reservation->drafts = false; 
        $reservation->save();
    
        return response()->json($reservation, 200);
    }

    public function cancelReservation($id)
    {
        $reservation = Reservation::find($id);
    
        if (!$reservation) {
            return response()->json(['error' => 'Reservation not found'], 404);
        }
    
        $reservation->canceled = false; 
        $reservation->save();
    
        return response()->json($reservation, 200);
    }
    

    public function destroy($id)
    {
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }

        $reservation->delete();
        return response()->json(['message' => 'Reservation has been removed']);
    }

    public function getClientsCountBySport(Request $request)
    {
        $terrain = Terrain::where('activité', $request->activité)->first();

        if (!$terrain) {
            return response()->json(['message' => 'No terrains found for this sport'], 404);
        }

        $clientCount = Reservation::join('terrains', 'reservations.terrain_id', '=', 'terrains.id')
            ->where('terrains.activité', $request->activité)
            ->distinct('reservations.client_id')
            ->count('reservations.client_id');

        return response()->json(['client_count' => $clientCount]);
    }


    public function getDraftCount()
{
    $draftCount = Reservation::where('drafts', true)->count();

    return response()->json(['draftCount' => $draftCount]);
}



public function getTerrainsWithReservations(Request $request)
{
    $terrainId = $request->input('terrain_id');
    $terrains = $terrainId
        ? Terrain::with('reservations')->where('id', $terrainId)->get()
        : Terrain::with('reservations')->get();

    if ($terrains->isEmpty()) {
        return response()->json(['message' => 'Terrain not found.'], 404);
    }
    $data = $terrains->map(function ($terrain) {
        return [
            'terrain_id' => $terrain->id,
            'terrain_name' => $terrain->Nom_Terrain,
            'reservations' => $terrain->reservations,
        ];
    });
    return response()->json($terrainId ? $data->first() : $data, 200);
}






}