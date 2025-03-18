<?php


namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Client;
use App\Models\Terrain;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with('terrain')->where('canceled',  false)->get();


        if ($reservations->isEmpty()) {
            return response()->json(['message' => 'There is no terrain recorded']);
        }

        return response()->json($reservations);
    }


    public function store(Request $request)
    {

        $request->validate([
            'Prenom' => 'required|string',
            'Nom' => 'required|string',
            'Email' => 'required|email',
            'Tel' => 'required|string',
            'Sexe' => 'required|string',
            'terrainId' => 'required|integer',
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

        $firstDate = Carbon::parse($request->DateDebut)->setTimezone('Europe/Paris');
        $secondDate = Carbon::parse($request->DateFin)->setTimezone('Europe/Paris');
        $terrainId = $request->terrainId;
        $reservation = new Reservation();
        $reservation->client_id = $customer->id;
        $reservation->terrain_id = $terrainId;
        $reservation->DateDebut = $firstDate;
        $reservation->DateFin = $secondDate;
        $reservation->save();
        return response()->json([
            'message' => 'Terrain has been booked',
            'reservation' => $reservation

        ], 201);
    }



    public function update(Request $request, $id)
    {

        $reservation = Reservation::find($id);

        if (!$reservation) {
            return response()->json(['error' => 'Reservation not found'], 404);
        }
        if ($request->has('drafts')) {
            $reservation->drafts = $request->input('drafts');
        }
        if ($request->has('canceled')) {
            $reservation->canceled = $request->input('canceled');
        }
        $reservation->save();

        return response()->json($reservation, 200);
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


    public function getCount(Request $request)
    {
        $action = $request->input('action');
        if ($action === "drafts") {
            $Count = Reservation::where('drafts', true)->count();
        } else if ($action === "reservations") {
            $Count = Reservation::count();
        } else if ($action === "newClients") {
            $Count = Client::where('created_at', '>=', now()->subDays(7))->count();
        }


        return response()->json(['Count' => $Count]);
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
