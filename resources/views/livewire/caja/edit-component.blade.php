@section('head')
    @vite(['resources/sass/productos.scss'])
@endsection
<div class="container-fluid">
    <div class="page-title-box">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h4 class="page-title">EDITAR MOVIMIENTO DE {{strtoupper($tipo_movimiento)}}</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-right">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Diario de Caja</a></li>
                    <li class="breadcrumb-item active">Editar {{$tipo_movimiento}}</li>
                </ol>
            </div>
        </div> <!-- end row -->
    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-md-9">
            <div class="card m-b-30">
                <div class="card-body">
                    <form wire:submit.prevent="update">
                        <input type="hidden" name="csrf-token" value="{{ csrf_token() }}">

                        <div class="mb-3 row d-flex align-items-center">
                            <label for="nombre" class="col-sm-12 col-form-label">Presupuesto</label>
                            <div class="col-sm-10">
                                <div class="col-md-12" x-data="" x-init="$('#select2-monitor').select2();
                                $('#select2-monitor').on('change', function(e) {
                                    var data = $('#select2-monitor').select2('val');
                                    @this.set('presupuesto_id', data);
                                });" wire:key='rand()'>
                                    <select class="form-control" name="presupuesto_id" id="select2-monitor"
                                        wire:model.lazy="presupuesto_id">
                                        <option value="0">-- ELIGE UN PRESUPUESTO
                                            --
                                        </option>
                                        @foreach ($presupuestos as $presupuesto)
                                            <option {{$presupuesto_id == $presupuesto->id ? 'selected' : '' }} value="{{ $presupuesto->id }}">
                                                (#{{ $presupuesto->nPresupuesto }})
                                                {{ $categorias->firstWhere('id', ($eventos->firstWhere('id', $presupuesto->id_evento)->eventoNombre))->nombre }} -
                                                {{ $this->getCliente($presupuesto->id) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div> @error('presupuesto_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row d-flex align-items-center">
                            <label for="descripcion" class="col-sm-12 col-form-label">Concepto</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" wire:model="descripcion" nombre="descripcion"
                                id="descripcion" placeholder="Concepto...">
                                @error('descripcion')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row d-flex align-items-center">
                            <label for="nombre" class="col-sm-12 col-form-label">Importe</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" wire:model="importe" nombre="importe"
                                    id="importe" placeholder="Nombre de la categoría...">
                                @error('importe')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row d-flex align-items-center">
                            <label for="fecha" class="col-sm-12 col-form-label">Fecha</label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control" wire:model="fecha" nombre="fecha"
                                    id="fecha" placeholder="Fecha...">
                                @error('fecha')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row d-flex align-items-center">
                            <label for="metodo_pago" class="col-sm-12 col-form-label">Método de pago</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" wire:model="metodo_pago" nombre="metodo_pago"
                                    id="metodo_pago" placeholder="Nombre de la categoría...">
                                @error('metodo_pago')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card m-b-30">
                <div class="card-body">
                    <h5>Acciones</h5>
                    <div class="row">
                        <div class="col-12">
                            <button class="w-100 btn btn-success mb-2" id="alertaGuardar">Editar movimiento</button>
                        </div>
                        <div class="col-12">
                            <button class="w-100 btn btn-danger mb-2" wire:click="destroy">Eliminar movimiento</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    $("#alertaGuardar").on("click", () => {
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Pulsa el botón de confirmar para guardar el tipo de evento.',
            icon: 'warning',
            showConfirmButton: true,
            showCancelButton: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.livewire.emit('update');
            }
        });
    });
</script>
@endsection
