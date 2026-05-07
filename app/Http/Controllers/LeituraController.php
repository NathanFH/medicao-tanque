<?php

namespace App\Http\Controllers;

use App\Models\Leitura;
use Illuminate\Http\Request;

class LeituraController extends Controller
{
    public function store(Request $request)
    {
        // Garante que o ESP32 mandou um número
        $request->validate([
            'nivel' => 'required|numeric'
        ]);

        // Salva no banco de dados
        $leitura = Leitura::create([
            'nivel' => $request->nivel
        ]);

        // Retorna um JSON confirmando (bom para debugar no Serial Monitor do Arduino)
        return response()->json([
            'message' => 'Leitura recebida do tanque!',
            'data' => $leitura
        ], 201);
    }
    public function latest()
    {
        // Pega a última leitura salva no banco de dados
        $leitura = Leitura::latest()->first();
        
        return response()->json($leitura);
    }
}

    