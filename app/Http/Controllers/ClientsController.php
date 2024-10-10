<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Http\Requests\StoreClientsRequest;
use App\Http\Requests\UpdateClientsRequest;
use Illuminate\Http\Request;

class ClientsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        $client = Client::all();
        if ($client->isEmpty()) { 
            return response()->json(['message' =>'There is no terrain recorded']);
          
           
           }else
           return response()->json($client);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreClientsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreClientsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $clients
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Clients  $clients
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateClientsRequest  $request
     * @param  \App\Models\Clients  $clients
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateClientsRequest $request, Client $clients)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $clients
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $clientId = $request->query('id');
        $client = Client::findOrFail($clientId);
        $client->delete();
    
        return response()->json(['message' => 'Client deleted successfully.']);
    }

    public function findUniqueClient($id)
    {
        $client = Client::findOrFail($id);
            return response()->json([
            'success' => true,
            'data' => $client,
            'message' => 'Client retrieved successfully.',
        ], 200);
    }

    public function uploadLogo(Request $request, $id)
{
    
    $request->validate([
        'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);
    $client = Client::findOrFail($id);

    if ($request->hasFile('logo')) {
        $fileName = time() . '_' . $request->file('logo')->getClientOriginalName();
        $filePath = $request->file('logo')->storeAs('logos', $fileName, 'public');

        $client->logo = $filePath;
        $client->save();
        return response()->json([
            'logo' => $filePath,
            'message' => 'Logo uploaded successfully',
        ]);
    }

    return response()->json(['message' => 'No file uploaded'], 400);
}

   
 
}