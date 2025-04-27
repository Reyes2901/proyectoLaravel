<?php

namespace App\Http\Controllers;

use App\Models\Graficador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // <---- Agrega esto arriba en el controlador


class GraficadorController extends Controller
{
    // Mostrar el listado de registros
    public function index()
    {
        //$graficadores = Graficador::all();  // Obtiene todos los registros
        $graficadores = DB::table('graficador')->get(); // Consulta directa a la tabla "graficador"
 
        //print_r($graficadores);
        return view('graficadores.index', compact('graficadores'));
    }

    // Mostrar el formulario para crear un nuevo registro
    public function create()
    {
        return view('graficadores.create');
    }

    // Guardar el nuevo registro en la base de datos
    public function store(Request $request)
    {
        // Validar los datos si quieres (opcional pero recomendado)
        $request->validate([
            'Titulo' => 'required|string|max:255',
            'Descripcion' => 'required|string',
            'FechaCreacion' => 'required|date',
            'HoraCreacion' => 'required',
            'Estado' => 'required|integer',
            'Contenido' => 'required|string',
        ]);
    
        // Insertar en la base de datos usando DB::table()
        DB::table('graficador')->insert([
            'Titulo' => $request->Titulo,
            'Descripcion' => $request->Descripcion,
            'FechaCreacion' => $request->FechaCreacion,
            'HoraCreacion' => $request->HoraCreacion,
            'Estado' => $request->Estado,
            'Contenido' => $request->Contenido,
            'created_at' => now(), // Agrega fecha de creación automática
            'updated_at' => now(), // Agrega fecha de actualización automática
        ]);
    
        // Redireccionar de nuevo al listado con mensaje (opcional)
        return redirect()->route('graficadores.index')->with('success', 'Graficador creado exitosamente.');
    
    }

    // Mostrar el formulario para editar un registro
    public function edit($idGraficador)
    {
        //$graficador = Graficador::findOrFail($idGraficador);
        $graficador = DB::table('graficador')->where('IdGraficador', $idGraficador)->first();
        return view('graficadores.edit', compact('graficador'));
    }

    // Actualizar el registro en la base de datos
    public function update(Request $request, $id)
    {
      

        $graficador = Graficador::findOrFail($id);
        $graficador->update($request->all());  // Actualiza el registro

        return redirect()->route('graficadores.index');  // Redirige a la lista de registros
    }

    // Eliminar un registro
    public function destroy($id)
    {
        $graficador = Graficador::findOrFail($id);
        $graficador->delete();  // Elimina el registro

        return redirect()->route('graficadores.index');  // Redirige a la lista de registros
    }
}
