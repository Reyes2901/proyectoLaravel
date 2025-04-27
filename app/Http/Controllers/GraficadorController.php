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
        //$graficadores = DB::table('graficador')->get(); // Consulta directa a la tabla "graficador"

        $user = auth()->user(); // Obtener el usuario autenticado

$graficadores = DB::table('graficador')
    ->join('graficadorusuario', 'graficador.IdGraficador', '=', 'graficadorusuario.IdGraficador')
    ->where('graficadorusuario.IdUser', $user->id)
    ->select('graficador.*') // Trae todos los campos del graficador
    ->get();

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
    // Validar los datos recibidos, incluyendo los nuevos campos
    $request->validate([
        'Titulo' => 'required|string|max:255',
        'Descripcion' => 'required|string',
        'FechaCreacion' => 'required|date',
        'HoraCreacion' => 'required',
        'Estado' => 'required|integer',
        'Contenido' => 'required|string',
    ]);

    // Insertar en la base de datos usando DB::table()
    
    $idGraficador=DB::table('graficador')->insertGetId([
    
        'Titulo' => $request->Titulo,
        'Descripcion' => $request->Descripcion,
        'FechaCreacion' => $request->FechaCreacion,
        'HoraCreacion' => $request->HoraCreacion,
        'Estado' => $request->Estado,
        'Contenido' => $request->Contenido,
        'created_at' => now(), // Fecha de creación automática
        'updated_at' => now(), // Fecha de actualización automática
    ]);

    $user = auth()->user(); // Asegúrate de que el usuario esté autenticado

        // Insertar el nuevo colaborador en la tabla graficadorusuario
        $result = DB::table('graficadorusuario')->insert([
            'IdGraficador' => $idGraficador,
            'Contador' => 1,  // Ajusta esto si necesitas contar los colaboradores
            'IdUser' => $user->id,
            'TipoUsuario' => 1,  // O el tipo que corresponda
            'Fecha' => now()->toDateString(),
            'Hora' => now()->toTimeString(),
            'Estado' => 2,  // O el estado que sea necesario
        ]);


    // Redirigir al listado con un mensaje de éxito
    return redirect()->route('graficadores.index')->with('success', 'Graficador creado y asociado exitosamente.');


    // Redireccionar de nuevo al listado con mensaje (opcional)
    //return redirect()->route('graficadores.index')->with('success', 'Graficador creado exitosamente.');
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

    public function listarUsuariosGraficadores()
{
    $usuarios = DB::table('users')
        ->join('graficadorusuario', 'users.id', '=', 'graficadorusuario.IdUser')
        ->select('users.*')
        ->distinct()
        ->get();

    return view('graficadores.ListaColaboradores', compact('usuarios'));
}
public function listarUsuariosPorGraficador($idGraficador)
{
    $usuarios = DB::table('users')
        ->join('graficadorusuario', 'users.id', '=', 'graficadorusuario.IdUser')
        ->where('graficadorusuario.IdGraficador', $idGraficador)
        ->select('users.*','graficadorusuario.IdGraficador', 'graficadorusuario.Estado as estadoGraficador')
        ->distinct()
        ->get();

    return view('graficadores.ListaColaboradores', compact('usuarios', 'idGraficador'));
}

public function cambiarEstado($idGraficador, $id)
{
    // Opcional: Validar que el graficador corresponda al usuario
    $registro = DB::table('graficadorusuario')
                ->where('IdGraficador', $idGraficador)
                ->where('IdUser', $id)
                ->first();

    if (!$registro) {
        return redirect()->back()->with('error', 'Registro no encontrado.');
    }

    // Actualizar el estado
    DB::table('graficadorusuario')
    ->where('IdGraficador', $idGraficador)
    ->where('IdUser', $id)
        ->update([
            'Estado' => 2,
        ]);

    return redirect()->back()->with('success', 'Estado actualizado correctamente.');
}


}
