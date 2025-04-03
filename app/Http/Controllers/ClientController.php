<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function search(Request $request)
    {
        $search = $request->search;

        $clients = Client::where('name', 'like', "%{$search}%")
            ->orWhere('identificacion', 'like', "%{$search}%")
            ->orderBy(DB::raw('GREATEST(updated_at, created_at)'), 'desc') // Ordena por la fecha más reciente entre ambas
            ->limit(5)
            ->get();
    
        return response()->json($clients);

    }
}
