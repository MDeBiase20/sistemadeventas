<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\Empresa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\File;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::where('empresa_id', Auth::user()->empresa_id)->get();
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $datos = request()->all();
        // return response()->json($datos);

        //Creo una nueva instanciación del modelo Empresas
        $roles = new Role();

        $roles->name = $request->name;
        $roles->guard_name = "web";
        $roles->empresa_id = Auth::user()->empresa_id;

        $roles->save();

        return redirect()->route('admin.roles.index')
            ->with('mensaje', 'Se registró el rol de forma correcta')
            ->with('icono', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $rol = Role::find($id);
        return view('admin.roles.show', compact('rol'));
    }

    
    public function edit($id)
    {
        $rol = Role::find($id);
        return view('admin.roles.edit', compact('rol'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // $datos = request()->all();
        // return response()->json($datos);

        $request->validate([
            'name'=>'required|unique:roles,name,'.$id,
        ]);

        $roles = Role::find($id);

        $roles->name = $request->name;
        $roles->guard_name = "web";
        

        $roles->save();

        return redirect()->route('admin.roles.index')
            ->with('mensaje', 'Se actualizó el rol de forma correcta')
            ->with('icono', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $rol = Role::destroy($id);
        return redirect()->route('admin.roles.index')
            ->with('mensaje', 'Se eliminó el rol de forma correcta')
            ->with('icono', 'success');
    }

    public function reporte(){

        $empresa = Empresa::where('id', Auth::user()->empresa_id)->first();
        $roles = Role::where('empresa_id', Auth::user()->empresa_id)->get();

         // Obtener la ruta física completa del logo
    	$logoPath = $_SERVER['DOCUMENT_ROOT'] . '/storage/' . $empresa->logo;
    
    	// Verificar si el archivo existe
    	if (File::exists($logoPath)) {
        $logoBase64 = 'data:image/' . 
                    pathinfo($logoPath, PATHINFO_EXTENSION) . 
                    ';base64,' . 
                    base64_encode(file_get_contents($logoPath));
    	} else {
        // Logo alternativo o vacío si no existe
        $logoBase64 = '';
    	}

        $pdf = PDF::loadView('admin.roles.reporte', compact('roles', 'empresa'));
        return $pdf->stream();
    }

    public function asignar($id){

        $rol = Role::find($id);

        /*La función "stripos" agarra algunos caracteres del campo en este caso "name" que sean similiares al prefijo en este caso "usu" */

        $permisos = Permission::all()->groupBy(function($permiso){
            if (stripos($permiso->name, 'config') !==false) {
                return 'Configuración';
            }elseif (stripos($permiso->name, 'rol') !==false ){
                return 'Roles';
            }elseif (stripos($permiso->name, 'permi') !==false ){
                return 'Permisos';
            }elseif (stripos($permiso->name, 'usu') !==false ){
                return 'Usuarios';
            }elseif (stripos($permiso->name, 'cat') !==false ){
                return 'Categorías';
            }elseif (stripos($permiso->name, 'prod') !==false ){
                return 'Productos';
            }elseif (stripos($permiso->name, 'prov') !==false ){
                return 'Proveedores';
            }elseif (stripos($permiso->name, 'comp') !==false ){
                return 'Compras';
            }elseif (stripos($permiso->name, 'cli') !==false ){
                return 'Clientes';
            }elseif (stripos($permiso->name, 'ven') !==false ){
                return 'Ventas';
            }elseif (stripos($permiso->name, 'arq') !==false ){
                return 'Arqueo de Caja';
            }
        });
            

        return view('admin.roles.asignar', compact('permisos', 'rol'));
    }

    public function update_asignar(Request $request, $id){

        // $datos = request()->all();
        // return response()->json($datos);

        $request->validate([
            'permisos'=>'required|array'
        ]);

        $rol = Role::find($id);

        
        /*permissions ya viene cuando se instala el paquete de spatie, lo que nos va a permitir es
        sincronizar los permisos con el rol que está jalando del array de los IDs de los permisos seleccionados del array permisos.
        Sync viene del paquete Spatie para poder sincronizar el rol con los permisos*/
        
        //Sincronizamos el rol con el modelo permissions que vienen el input con el name permisos
        $rol->permissions()->sync($request->input('permisos'));

        return redirect()->back()->with('mensaje', 'Se agregaron los permisos al rol de manera correcta')
            ->with('icono', 'success');

    }
}
