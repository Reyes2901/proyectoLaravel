<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Graficador;
use Illuminate\Support\Facades\DB;


class GraficadorController extends Controller
{
    // Método que lista todas las gráficas
    public function listatodaslasgraficas()
    {
        // Obtener todos los registros de la tabla graficador
        $graficadores = Graficador::all();

        // Pasar los datos a la vista
        return view('graficadores.listatodaslasgraficas', compact('graficadores'));
    }

    // Método para añadir colaborador
    public function addCollaborator(Request $request)
    {


        // Obtener los datos del usuario autenticado
        $user = auth()->user(); // Asegúrate de que el usuario esté autenticado

        // Insertar el nuevo colaborador en la tabla graficadorusuario
        $result = DB::table('graficadorusuario')->insert([
            'IdGraficador' => $request->graficadorId,
            'Contador' => 1,  // Ajusta esto si necesitas contar los colaboradores
            'IdUser' => $user->id,
            'TipoUsuario' => 2,  // O el tipo que corresponda
            'Fecha' => now()->toDateString(),
            'Hora' => now()->toTimeString(),
            'Estado' => 1,  // O el estado que sea necesario
        ]);

        // Responder con un JSON
        return response()->json(['success' => $result]);
    }
}
