<?php

namespace App\Http\Controllers;

use App\Models\reservation;
use App\Http\Requests\StorereservationRequest;
use App\Http\Requests\UpdatereservationRequest;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    { 
        $reservation = reservation::all();
        if ($reservation->isEmpty()) { 
            return response()->json(['message' =>'There is no terrain recorded']);
          
           
           }else
           return response()->json($reservation);
    }


    public function create(Request $request)
    {
        $reservation = new reservation();
         $request->validate([
            'Nom'=>'required',
            'Prenom'=>'required',
            'numTel'=>'required',
            'email'=>'required',
            'DateDebut'=>'required',
            'DateFin'=>'required',
       
        ]);
        $reservation->Nom=$request->Nom;
        $reservation->Prenom=$request->Prenom;
        $reservation->numTel=$request->numTel;
        $reservation->email = $request->email;
        $reservation->sport = $request->sport;
        $reservation->DateDebut = $request->DateDebut;
        $reservation->DateFin = $request->DateFin;
        
        
        
        $result= $reservation->save();
        if($result) {
          
            return response()->json(['message' =>'Terrain has been booked']);
        }else {
            return response()->json(['errors'=>$request->validate->errors()]);
        }
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'Nom'=>'required',
            'Prenom'=>'required',
            'numTel'=>'required',
            'email'=>'required',
            'Sport'=>'required',
            'DateDebut'=>'required',
            'DateFin'=>'required',
       
        ]);
        $reservation = reservation::find($id);   
        $reservation->Nom = $request->Nom;        
        $reservation->Prenom = $request->Prenom; 
        $reservation->numTel = $request->numTel;
        $reservation->email = $request->email;
        $reservation->sport = $request->sport;
        $reservation->DateDebut = $request->DateDebut;
        $reservation->DateFin = $request->DateFin;

        $result= $reservation->save();
       if($result) {
          
            return response()->json(['message' =>'Terrain updated correctly']);
        }else {
            return response()->json(['errors'=>$request->validate->errors()]);
        }
   
    }

    public function destroy($id)
    {
      
        $reservation = reservation::find($id);
        $reservation->delete();
        return response()->json(['message' =>'Terrain has been removed']);
    }

    public function nombreClient(Request $request, $sport)
    {
      
        $Nombreclient =reservation::where('sport',$sport)->get();
        $Count = $Nombreclient->count();

        return response()->json($Count);

    }
    public function filterReservationbySport(Request $request, $sport)
    {
      
        $ReservationbySport =reservation::where('sport',$sport)->get();
        

        return response()->json($ReservationbySport);

    }
  




}
