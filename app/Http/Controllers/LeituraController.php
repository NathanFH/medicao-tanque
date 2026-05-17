<?php

namespace App\Http\Controllers;

use App\Models\Leitura;
use Illuminate\Http\Request;

class LeituraController extends Controller
{
    public function store(Request $request)
    {
        // Garante que o dispositivo mandou a duração do pulso (em microssegundos - dado mais bruto possível)
        $request->validate([
            'duracao' => 'required|numeric'
        ]);

        // 1. Calcula a distância em cm com base na duração do pulso sonoro
        $duracao = $request->duracao;
        $distancia_cm = $duracao * 0.0343 / 2;

        // 2. Altura do sensor até o chão/fundo do tanque
        $altura_sensor_chao = 40.0;

        // 3. Cálculo do nível percentual (Regra de negócio movida para o servidor)
        $nivel_percentual = (($altura_sensor_chao - $distancia_cm) / $altura_sensor_chao) * 100;
        
        // Limita o percentual entre 0% e 100%
        if ($nivel_percentual < 0) $nivel_percentual = 0;
        if ($nivel_percentual > 100) $nivel_percentual = 100;

        // Salva no banco de dados (arredondando para 2 casas decimais)
        $leitura = Leitura::create([
            'nivel' => round($nivel_percentual, 2)
        ]);

        // Retorna um JSON confirmando
        return response()->json([
            'message' => 'Duração bruta recebida, cálculos realizados e nível salvo com sucesso!',
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

    