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
                                        <option value="">-- ELIGE UN MONITOR --
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
                        @if (isset($serviciosMonitores))
                            <div class="mb-3 row d-flex align-items-center">
                                <label for="monitor_id" class="col-sm-12 col-form-label">Servicios</label>
                                <p>Pagos pendientes del monitor:</p>
                                <ul>

                                @foreach ($serviciosMonitores as $servicio)
                                    @php
                                        // Decodifica los arrays JSON
                                        $idMonitores = json_decode($servicio->id_monitores, true);
                                        $sueldoMonitores = json_decode($servicio->sueldo_monitores, true);
                                        $pagoPendiente = json_decode($servicio->pago_pendiente, true);

                                        // Encuentra todas las posiciones donde aparece el monitor_id
                                        $posiciones = array_keys($idMonitores, (string) $monitor_id);

                                        // Filtra las posiciones donde el pago pendiente es mayor a 0
                                        $pagosFiltrados = [];
                                        foreach ($posiciones as $posicion) {
                                            if (isset($pagoPendiente[$posicion]) && $pagoPendiente[$posicion] > 0) {
                                                $pagosFiltrados[] = [
                                                    'servicio_id' => $servicio->id,
                                                    'sueldo' => $sueldoMonitores[$posicion] ?? null,
                                                    'pago_pendiente' => $pagoPendiente[$posicion],
                                                ];
                                            }
                                        }
                                    @endphp

                                    <!-- Muestra los pagos pendientes con checkboxes -->
                                    @if (!empty($pagosFiltrados))
                                            @foreach ($pagosFiltrados as $index => $pago)
                                                <li class="list-group-item">
                                                    <input type="checkbox" name="pagos_seleccionados[]" wire:model="pagos_seleccionados" value="{{ $pago['servicio_id'] }}_{{ $index }}" class="pago-checkbox" data-pago="{{ $pago['pago_pendiente'] }}"/>
                                                    Sueldo: {{ $pago['sueldo'] }} € - Pago pendiente: {{ $pago['pago_pendiente'] }} €
                                                </li>
                                            @endforeach
                                    @endif
                                @endforeach
                                </ul>
                            </div>
                        @endif
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
<script>
    document.addEventListener('livewire:load', function () {
        // Función para calcular el importe total
        function calcularImporte() {
            const monitorSelect = document.getElementById('select2-monitor');
            const monitorSeleccionado = monitorSelect.value;

            // Si no hay monitor seleccionado, salir sin calcular
            if (!monitorSeleccionado) {
                return;
            }

            const importeField = document.getElementById('importe');
            const checkboxes = document.querySelectorAll('.pago-checkbox');
            let total = 0;
            let algunCheckboxSeleccionado = false;

            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    algunCheckboxSeleccionado = true;
                    total += parseFloat(checkbox.dataset.pago);
                }
            });

            if (algunCheckboxSeleccionado) {
                importeField.value = total.toFixed(2);
            } else {
                importeField.value = '0.00';
            }
        }

        // Observador de mutaciones para detectar cambios en el DOM
        const observer = new MutationObserver(() => {
            const checkboxes = document.querySelectorAll('.pago-checkbox');

            // Escuchar cambios en los checkboxes
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', calcularImporte);
            });

            // Calcular el importe inicial
            calcularImporte();
        });

        // Observar el contenedor principal donde Livewire renderiza los elementos
        const targetNode = document.querySelector('.container-fluid'); // Ajusta el selector según tu estructura
        if (targetNode) {
            observer.observe(targetNode, { childList: true, subtree: true });
        }

        // Calcular el importe inicial cuando se carga la página
        calcularImporte();
    });
</script>
@endsection
