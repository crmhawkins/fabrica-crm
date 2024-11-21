<div class="container-fluid">
    <div class="page-title-box">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h4 class="page-title">IVA</h4>
            </div>
            <div class="col-sm-6 text-sm-right">
                <a href="{{ route('iva.create') }}" class="btn btn-info text-white">
                    <i class="fa-solid fa-plus"></i> Crear Tipo de IVA
                </a>
            </div>
        </div> <!-- end row -->
    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <h5 class="card-title">Tipos de IVA</h5>
                    <br>

                    @if (count($iva) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="tableIva">
                                <thead>
                                    <tr>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">IVA (%)</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($iva as $item)
                                        <tr>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->iva }}</td>
                                            <td>
                                                <a href="{{ route('iva.edit', $item->id) }}" class="btn btn-primary btn-sm">
                                                    Editar
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center">No hay tipos de IVA registrados.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
    <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.4/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.print.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tableIva').DataTable({
                responsive: true,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'collection',
                        text: 'Exportar',
                        buttons: [
                            { extend: 'pdf', className: 'btn-export' },
                            { extend: 'excel', className: 'btn-export' }
                        ],
                        className: 'btn btn-info text-white'
                    }
                ],
                language: {
                    lengthMenu: "Mostrando _MENU_ registros por página",
                    zeroRecords: "No se encontraron registros",
                    info: "Mostrando página _PAGE_ de _PAGES_",
                    infoEmpty: "No hay registros disponibles",
                    infoFiltered: "(filtrado de _MAX_ registros en total)",
                    search: "Buscar:",
                    paginate: {
                        first: "Primero",
                        last: "Último",
                        next: "Siguiente",
                        previous: "Anterior"
                    }
                }
            });

            addEventListener("resize", () => {
                location.reload();
            });
        });
    </script>
@endsection
