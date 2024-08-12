
<div class="container-fluid">
    <div class="page-title-box">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h4 class="page-title">SERVICIOS DISPONIBLES</span></h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-right">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Servicios Disponibles</a></li>
                </ol>
            </div>
        </div> <!-- end row -->
    </div>
    <div class="row">
        <div class="col-9">
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Listado de todos los servicios</h4>
                    <p class="sub-title">Listado completo de todos nuestros eventos</p>
                    <table wire:key="{{ rand() }}" id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th scope="col">Servicio</th>
                                <th scope="col">Stock total</th>
                                <th scope="col">Disponibles</th>
                                <th scope="col">Articulos</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($servicios as $servicio)
                                <tr>
                                    <td>{{ $servicio->nombre }}</td>
                                    <td>{{ $this->stockTotal($servicio->id) }}</td>
                                    <td>{{ $this->stock($servicio->id) }}</td>
                                    <td class="details-control" data-id="{{ $servicio->id }}" style="cursor: pointer;">
                                        <span>Ver artículos</span>
                                        <div class="articulos" style="display: none;">
                                            <ul>
                                                @foreach ($articulos as $articulo)
                                                    @if ($articulo->id_categoria == $servicio->id)
                                                        <li>{{ $articulo->name }}</li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card m-b-30 position-fixed">
                <div class="card-body">
                    <h5>Elige un día para mostrar</h5>
                    <div class="row">
                        <div class="col-12">
                            <input type="date" class="form-control" wire:model="dia">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@section('scripts')
<script>
    document.querySelectorAll('.details-control').forEach(function(element) {
        element.addEventListener('click', function() {
            var articulos = this.querySelector('.articulos');
            articulos.style.display = articulos.style.display === 'none' ? 'block' : 'none';
        });
    });
</script>
<script>
    document.addEventListener('livewire:load', function () {
        window.livewire.hook('message.processed', function () {
            if ($.fn.DataTable.isDataTable('#datatable-buttons')) {
                    $('#datatable-buttons').DataTable().destroy();
                }
                $('#datatable-buttons').DataTable({
                    lengthChange: false,
                    dom: 'Bfrtip',
                    buttons: ['copy', 'excel', 'pdf', 'colvis'],
                    language: {
                        lengthMenu: "Mostrando _MENU_ registros por página",
                        zeroRecords: "Nothing found - sorry",
                        info: "Mostrando página _PAGE_ of _PAGES_",
                        infoEmpty: "No hay registros disponibles",
                        infoFiltered: "(filtrado de _MAX_ total registros)",
                        search: "Buscar:",
                        paginate: {
                            first: "Primero",
                            last: "Ultimo",
                            next: "Siguiente",
                            previous: "Anterior"
                        },
                        zeroRecords: "No se encontraron registros coincidentes",
                    },
                    order: [[0, 'asc']],
                });
        });
    });
</script>
<!-- Required datatable js -->
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
