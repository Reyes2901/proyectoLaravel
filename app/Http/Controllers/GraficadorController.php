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
