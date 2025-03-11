<div class="container-fluid">
    <div class="page-title-box">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h4 class="page-title">AÑADIR MOVIMIENTO DE CAJA (GASTO)</span></h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-right">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Caja</a></li>
                    <li class="breadcrumb-item active">Añadir movimiento de gasto</li>
                </ol>
            </div>
        </div> <!-- end row -->
    </div>
    <!-- end page-title -->
    <div class="row">
        <div class="col-md-9">
            <div class="card m-b-30">
                <div class="card-body">
                    <form wire:submit.prevent="submit">
                        <input type="hidden" name="csrf-token" value="{{ csrf_token() }}">

                        <div class="mb-3 row d-flex align-items-center">
                            <label for="nombre" class="col-sm-12 col-form-label">Presupuesto</label>
                            <div class="col-sm-10">
                                <div >
                                    <select class="form-control select2" name="presupuesto_id" id="select2-presupuesto"
                                        wire:model.lazy="presupuesto_id">
                                        <option value="0">-- ELIGE UN PRESUPUESTO
                                            --
                                        </option>
                                        @foreach ($presupuestos as $presupuesto)
                                            <option value="{{ $presupuesto->id }}">
                                                (#{{ optional($presupuesto->contrato)->id ?? 'Sin Contrato' }})
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
                        <div class="mb-3 row d-flex align-items-center">
                            <label for="monitor_id" class="col-sm-12 col-form-label">Monitor</label>
                            <div class="col-sm-10">
                                <div>
                                    <select class="form-control select2" name="monitor_id" id="select2-monitor" wire:model.lazy="monitor_id">
                                        <option value="0">-- ELIGE UN MONITOR --
                                        </option>
                                        @foreach ($monitores as $monitor)
                                            <option value="{{ $monitor->id }}">
                                                {{ $monitor->nombre .' '.$monitor->apellidos }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div> @error('monitor_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        {{-- @if (isset($serviciosMonitores))
                            <div class="mb-3 row d-flex align-items-center">
                                <label for="monitor_id" class="col-sm-12 col-form-label">Servicios</label>
                                <div class="col-sm-10">
                                    <div class="col-md-12" x-data="" x-init="$('#select2-monitor').select2();
                                    $('#select2-monitor').on('change', function(e) {
                                        var data = $('#select2-monitor').select2('val');
                                        @this.set('monitor_id', data);
                                    });" wire:key='rand()'>
                                        <select class="form-control" name="monitor_id" id="select2-monitor"
                                            wire:model.lazy="monitor_id">
                                            <option value="0">-- Elige los servicios --
                                            </option>
                                            @foreach ($serviciosMonitores as $servicio)
                                                <option value="{{ $monitor->id }}">
                                                    {{ $servicio->nombre .' '.$monitor->apellidos }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div> @error('monitor_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        @endif --}}
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
                            <button class="w-100 btn btn-success mb-2" id="alertaGuardar">Guardar Gasto </button>
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
                text: 'Pulsa el botón de confirmar para guardar la nueva categoría.',
                icon: 'warning',
                showConfirmButton: true,
                showCancelButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.livewire.emit('submit');
                }
            });
        });
        document.addEventListener('livewire:load', function () {
        $('#select2-presupuesto').select2();
        $('#select2-presupuesto').on('change', function (e) {
            var data = $('#select2-presupuesto').select2("val");
            @this.set('presupuesto_id', data);
        });
        $('#select2-monitor').select2();
        $('#select2-monitor').on('change', function (e) {
            var data = $('#select2-monitor').select2("val");
            @this.set('monitor_id', data);
        });

        Livewire.hook('message.processed', (message, component) => {
            $('#select2-presupuesto').select2();
            $('#select2-monitor').select2();
        });
    });
    </script>
@endsection
