<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{
    public function search(Request $request)
{
    $search = $request->search;

    $clients = Client::where('name', 'like', "%{$search}%")
        ->orWhere('identificacion', 'like', "%{$search}%")
        ->orderBy('name')
        ->limit(10)
        ->get();

    return response()->json($clients);
}
}
