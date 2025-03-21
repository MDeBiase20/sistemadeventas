@extends('adminlte::page')

@section('content_header')
    <h1><b>Producto: {{ $producto->nombre }}</b></h1>
    <hr>
@stop

@section('content')
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title">Datos del producto</h3>
                        <!-- /.card-tools -->

                    </div>
                    <!-- /.card-header -->
                    

                    <div class="card-body">
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="name">Categoría</label>
                                                <p>{{ $producto->categoria->nombre }}</p>
                                            </div>
                                        </div>
        
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="codigo">Código</label>
                                                <p>{{ $producto->codigo }}</p>
                                            </div>
                                        </div>
        
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="nombre">Nombre del producto</label>
                                                <p>{{ $producto->nombre }}</p>
                                            </div>
                                        </div>
        
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="descripcion">Descripción</label>
                                                <p>{{ $producto->descripcion }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="stock">Stock</label>
                                                <p>{{ $producto->stock }}</p>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="stock_minimo">Stock mínimo</label>
                                                <p>{{ $producto->stock_minimo }}</p>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="stock_maximo">Stock máximo</label>
                                                <p>{{ $producto->stock_maximo }}</p>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="precio_compra">Precio compra</label>
                                                <p>{{ $producto->precio_compra }}</p>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="precio_venta">Precio venta</label>
                                                <p>{{ $producto->precio_venta }}</p>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="fecha_ingreso">Fecha ingreso</label>
                                                <p>{{ $producto->fecha_ingreso }}</p>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="logo">Imagen</label><br>
                                        <img src="{{ asset('storage/'.$producto->imagen) }}" width="80px" alt="logo">
                                    </div>
                                </div>

                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <a href="{{ url('/admin/productos') }}" class="btn btn-secondary">Volver</a>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
@stop

@section('css')
    
@stop

@section('js')
    
@stop