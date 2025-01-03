<div class="container-fluid">
    <div class="page-title-box">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h4 class="page-title">SERVICIOS</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-right">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Servicios</a></li>
                    <li class="breadcrumb-item active">Todos los servicios</li>
                </ol>
            </div>
        </div> <!-- end row -->
    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Listado de todos los servicios</h4>
                    <p class="sub-title../plugins">Listado completo de todos nuestros eventos, para editar o ver la informacion completa pulse el boton de Editar en la columna acciones.
                    </p>
                    @if (count($servicios) > 0)
                    <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th scope="col">Servicio</th>
                                {{-- <th scope="col">Categoria</th> --}}
                                <th scope="col">Pack</th>
                                {{-- <th scope="col">Stock</th> --}}
                                <th scope="col">Precio Base</th>
                                <th scope="col">Nº mínimo de monitores</th>
                                <th scope="col">Precio por monitor</th>
                                <th scope="col">Precio total Estimado</th>


                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($servicios as $servicio)
                            <tr>
                                <td>{{ $servicio->nombre }}</td>
                                {{-- <td>{{ $this->nombreCategoria($servicio->id_categoria) }}</td> --}}
                                @if($servicio->id_pack == null)
                                <td> No pertenece a ningún pack </td>
                                @else
                                <td>{{ $this->nombrePacks($servicio->id_pack) }}</td>
                                @endif
                                {{-- <td>{{ $servicio->stock }}</td> --}}
                                <td>{{ $servicio->precioBase }}</td>
                                <td>{{ $servicio->minMonitor }}</td>
                                <td>{{ $servicio->precioMonitor }} €</td>
                                <td>{{ $this->precioTotal($servicio->id) }} €</td>


                                <td> <a href="servicios-edit/{{ $servicio->id }}" class="btn btn-primary" target="blank">Editar</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>
    </div>


    @section('scripts')
    <script src="../assets/js/jquery.slimscroll.js"></script>
    <script src="../plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../plugins/datatables/dataTables.bootstrap4.min.js"></script>
    <!-- Buttons examples -->
    <script src="../plugins/datatables/dataTables.buttons.min.js"></script>
    <script src="../plugins/datatables/buttons.bootstrap4.min.js"></script>
    <script src="../plugins/datatables/jszip.min.js"></script>
    <script src="../plugins/datatables/pdfmake.min.js"></script>
    <script src="../plugins/datatables/vfs_fonts.js"></script>
    <script src="../plugins/datatables/buttons.html5.min.js"></script>
    <script src="../plugins/datatables/buttons.print.min.js"></script>
    <script src="../plugins/datatables/buttons.colVis.min.js"></script>
    <!-- Responsive examples -->
    <script src="../plugins/datatables/dataTables.responsive.min.js"></script>
    <script src="../plugins/datatables/responsive.bootstrap4.min.js"></script>
    <script src="../assets/pages/datatables.init.js"></script>
    @endsection
