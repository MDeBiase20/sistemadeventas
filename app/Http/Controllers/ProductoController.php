<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Que me traiga todos los productos que estén relacionados con la tabla categorías
        $productos = Producto::where('empresa_id', Auth::user()->empresa_id)->get(); 
        return view('admin.productos.index', compact('productos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = Categoria::where('empresa_id', Auth::user()->empresa_id)->get();
        return view('admin.productos.create', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $datos = request()->all();
        // return response()->json($datos);

        $request->validate([
            'codigo'=>'required|unique:productos,codigo',
            'nombre'=>'required',
            'stock'=>'required',
            'stock_minimo'=>'required',
            'stock_maximo'=>'required',
            'precio_compra'=>'required',
            'precio_venta'=>'required',
            'fecha_ingreso'=>'required',
        ]);

        $producto = new Producto();

        $producto->codigo = $request->codigo;
        $producto->nombre = $request->nombre;
        $producto->descripcion = $request->descripcion;
        $producto->stock = $request->stock;
        $producto->stock_minimo = $request->stock_minimo;
        $producto->stock_maximo = $request->stock_maximo;
        $producto->precio_compra = $request->precio_compra;
        $producto->precio_venta = $request->precio_venta;
        $producto->fecha_ingreso = $request->fecha_ingreso;
        $producto->categoria_id = $request->categoria_id;
        $producto->empresa_id = Auth::user()->empresa_id;

        if($request->hasFile('imagen')){
            $producto->imagen = $request->file('imagen')->store('productos', 'public');
        }

        $producto->save();

        return redirect()->route('admin.productos.index')
        ->with('mensaje', 'Se registró el producto en la base de datos de manera correcta')
        ->with('icono', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $producto = Producto::find($id);
        
        return view('admin.productos.show', compact('producto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $producto = Producto::find($id);
        $categorias = Categoria::where('empresa_id', Auth::user()->empresa_id)->get();
        return view('admin.productos.edit', compact('producto', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // $datos = request()->all();
        // return response()->json($datos);

        $request->validate([
            'codigo'=>'required|unique:productos,codigo,'.$id,
            'nombre'=>'required',
            'stock'=>'required',
            'stock_minimo'=>'required',
            'stock_maximo'=>'required',
            'precio_compra'=>'required',
            'precio_venta'=>'required',
            'fecha_ingreso'=>'required',
        ]);

        $producto = Producto::find($id);

        $producto->codigo = $request->codigo;
        $producto->nombre = $request->nombre;
        $producto->descripcion = $request->descripcion;
        $producto->stock = $request->stock;
        $producto->stock_minimo = $request->stock_minimo;
        $producto->stock_maximo = $request->stock_maximo;
        $producto->precio_compra = $request->precio_compra;
        $producto->precio_venta = $request->precio_venta;
        $producto->fecha_ingreso = $request->fecha_ingreso;
        $producto->categoria_id = $request->categoria_id;
        $producto->empresa_id = Auth::user()->empresa_id;

        if($request->hasFile('imagen')){
            Storage::delete('public/'.$producto->imagen);
            $producto->imagen = $request->file('imagen')->store('productos', 'public');
        }

        $producto->save();

        return redirect()->route('admin.productos.index')
        ->with('mensaje', 'Se actualizó el producto en la base de datos de manera correcta')
        ->with('icono', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $producto = Producto::find($id);
        Producto::destroy($id);

        Storage::delete('public/'.$producto->imagen);
        
        return redirect()->route('admin.productos.index')
        ->with('mensaje', 'Registro eliminado correctamente de la base de datos')
        ->with('icono', 'success');
    }

    public function reporte(){

        $empresa = Empresa::where('id', Auth::user()->empresa_id)->first();

        $productos = Producto::where('empresa_id', Auth::user()->empresa_id)->get();

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

        $pdf = PDF::loadView('admin.productos.reporte', compact('empresa', 'productos'))
                    ->setPaper('letter', 'LandScape');
        return $pdf->stream();
    }
}
