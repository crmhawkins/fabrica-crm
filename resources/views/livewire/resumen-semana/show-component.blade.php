<div class="container-fluid">
    <script src="//unpkg.com/alpinejs" defer></script>
    <div class="page-title-box">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h4 class="page-title">CUADRANTE SEMANAL</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-right">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Cuadrante</a></li>
                    <li class="breadcrumb-item active">Cuadrante semanal</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-9">
            <div class="card m-b-30">
                <div class="card-body">
                    @foreach ($dias as $diaIndex => $dia)
                        <div class="form-group col-md-12">
                            <h5 class="ms-3"
                                style="border-bottom: 1px gray solid !important; padding-bottom: 10px !important;">
                                {{ $dia }}
                            </h5>
                        </div>
                        <div class="form-group col-md-12">
                            @if ($eventos->where('diaEvento', $fechas[$diaIndex])->count() > 0)
                                @foreach ($eventos->where('diaEvento', $fechas[$diaIndex]) as $evento)
                                @php
                                    $presupuesto = $presupuestos->where('id_evento', $evento->id)->first();
                                @endphp
                                @if($presupuesto)
                                <table id="table-{{$evento->id}}" class="table table-striped table-bordered nowrap">
                                    <tr>

                                            <th colspan="1">
                                                #{{ optional($presupuesto->contrato)->id ?? 'Contrato no Creado' }}
                                            </th>
                                            <th colspan="6">
                                                @if ($datoEdicion['id'] == $evento->id && $datoEdicion['column'] == 'eventoNombre')
                                                    <div class="col-md-8" x-data=""
                                                        x-init="$('#select2-evento').select2();
                                                            $('#select2-evento').on('change', function(e) {
                                                                var data = $('#select2-evento').select2('val');
                                                                @this.set('datoEdicion.value', data);
                                                            });"
                                                        wire:key='rand()'>
                                                        <select class="form-control" name="eventoNombre"
                                                            id="select2-evento" wire:model="datoEdicion.value"
                                                            wire:change.lazy='terminarEdicion'>
                                                            <option value="0">-- ELIGE UN TIPO DE EVENTO --</option>
                                                            @foreach ($categorias as $tipo)
                                                                <option value="{{ $tipo->id }}">
                                                                    {{ $tipo->nombre }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @else
                                                    <span class="align-middle"
                                                        wire:click="detectarEdicion('{{ $evento->id }}', 'eventoNombre')">
                                                        {{ $this->categorias->where('id', $evento->eventoNombre)->first()->nombre.' - '.$evento->eventoProtagonista }}
                                                    </span>
                                                @endif
                                            </th>

                                            <th>
                                                @if ($presupuesto)
                                                    <a class="btn btn-sm btn-primary w-100"
                                                       href="{{ route('presupuestos.edit', $presupuesto->id) }}">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                @else
                                                    <span class="btn btn-sm btn-secondary w-100" disabled>No hay presupuesto</span>
                                                @endif
                                            </th>
                                        </tr>
                                        <tr>
                                            <td colspan="1">Contacto:</td>
                                            <td colspan="1"><spam style="font-weight: 800">{{$evento->eventoContacto}}</spam></td>
                                            <td colspan="1">Parentesco:</td>
                                            <td colspan="1"><spam style="font-weight: 800">{{$evento->eventoParentesco}}</spam></td>
                                            <td colspan="1">Telefono:</td>
                                            <td colspan="1"><spam style="font-weight: 800">{{$evento->eventoTelefono}}</spam></td>
                                            <td colspan="2"><button class="btn btn-danger btn-sm mb-2"
                                                data-export-table="table-{{$evento->id}}"
                                                data-filename="Evento_{{optional($presupuesto->contrato)->id ?? 'Contrato no Creado'}}">
                                                Exportar a PDF
                                            </button></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                {{-- @if ($datoEdicion['id'] == $evento->id && $datoEdicion['column'] == 'precioFinal')
                                                    <input type="number" wire:model.lazy="datoEdicion.value"
                                                        wire:change.lazy="terminarEdicion">
                                                @else --}}
                                                    <span class="align-middle">
                                                        {{ ($presupuesto->precioFinal - $presupuesto->adelanto) ?? 'Sin precio' }} €
                                                    </span>
                                                {{-- @endif --}}
                                            </td>
                                            <td>
                                                @if ($datoEdicion['id'] == $evento->id && $datoEdicion['column'] == 'eventoNiños')
                                                    <input type="number" wire:model.lazy="datoEdicion.value"
                                                        wire:change.lazy="terminarEdicion" name="eventoNiños"
                                                        id="eventoNiños"> niños
                                                @else
                                                    <span class="align-middle"
                                                        wire:click="detectarEdicion('{{ $evento->id }}', 'eventoNiños')">
                                                        {{ $evento->eventoNiños }} niños
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($datoEdicion['id'] == $evento->id && $datoEdicion['column'] == 'eventoAdulto')
                                                    <input type="number" wire:model.lazy="datoEdicion.value"
                                                        wire:change.lazy="terminarEdicion"> adultos
                                                @else
                                                    <span class="align-middle"
                                                        wire:click="detectarEdicion('{{ $evento->id }}', 'eventoAdulto')">
                                                        {{ $evento->eventoAdulto ?? 0 }} adultos
                                                    </span>
                                                @endif
                                            </td>
                                            <td>{{ $this->checkAuthContrato($evento->id) }}</td>
                                            <th></th>
                                            <th>{{ $evento->eventoLugar }}</th>
                                            <th></th>
                                            <th>{{ $evento->eventoLocalidad }}</th>
                                        </tr>
                                        <tr>
                                            <th>Servicio</th>
                                            <th>Hora de montaje</th>
                                            <th>Hora de inicio</th>
                                            <th>Duración</th>
                                            <th>Tiempo de desmontaje</th>
                                            <th>Monitor</th>
                                            <th>Sueldo</th>
                                            <th>Gasoil</th>
                                        </tr>
                                        @foreach ($evento->presupuesto->servicios()->get() as $servicio)
                                            @php
                                                $monitoresData = json_decode($servicio->pivot->id_monitores, true);
                                            @endphp
                                            @if (is_array($monitoresData))
                                                @foreach ($monitoresData as $monitoresIndex => $monitores)
                                                    <tr>
                                                        @if ($monitoresIndex == 0)
                                                            <td>

                                                                @php
                                                                $articulo = $articulos->where('id', $servicio->pivot->articulo_seleccionado)->first();
                                                                $serviciopresupuesto = $evento->presupuesto->serviciosPresupuesto()->where('servicio_id', $servicio->id)->where('articulo_seleccionado', $servicio->pivot->articulo_seleccionado)->first();
                                                                @endphp
                                                                    <span class="align-middle">{{ $serviciopresupuesto->concepto ?? $articulo->name ?? $servicio->nombre }}</span>

                                                            </td>
                                                            <td>
                                                                @if ($datoEdicion['id'] != null && isset($datoEdicion['id']['presupuesto']))
                                                                    @if ($datoEdicion['id']['presupuesto'] == $evento->id && $datoEdicion['id']['servicio'] == $servicio->id && $datoEdicion['column'] == 'servicioHoraMontaje')
                                                                        <input type="time"
                                                                            wire:model.lazy="datoEdicion.value"
                                                                            wire:change.lazy="terminarEdicionServicio">
                                                                    @else
                                                                        <span class="align-middle"
                                                                            wire:click="detectarEdicionServicio('{{ $evento->id }}', '{{ $servicio->id }}', 'servicioHoraMontaje')">{{ $servicio->pivot->hora_montaje }}</span>
                                                                    @endif
                                                                @else
                                                                    <span class="align-middle"
                                                                        wire:click="detectarEdicionServicio('{{ $evento->id }}', '{{ $servicio->id }}', 'servicioHoraMontaje')">{{ $servicio->pivot->hora_montaje }}</span>
                                                                @endif


                                                            </td>
                                                            <td>
                                                                @if ($datoEdicion['id'] != null && isset($datoEdicion['id']['presupuesto']))
                                                                    @if ($datoEdicion['id']['presupuesto'] == $evento->id && $datoEdicion['id']['servicio'] == $servicio->id && $datoEdicion['column'] == 'servicioHoraInicio')
                                                                        <input type="time"
                                                                            wire:model.lazy="datoEdicion.value"
                                                                            wire:change.lazy="terminarEdicionServicio">
                                                                    @else
                                                                        <span class="align-middle"
                                                                            wire:click="detectarEdicionServicio('{{ $evento->id }}', '{{ $servicio->id }}', 'servicioHoraInicio')">{{ $servicio->pivot->hora_inicio }}</span>
                                                                    @endif
                                                                @else
                                                                    <span class="align-middle"
                                                                        wire:click="detectarEdicionServicio('{{ $evento->id }}', '{{ $servicio->id }}', 'servicioHoraInicio')">{{ $servicio->pivot->hora_inicio }}</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($datoEdicion['id'] != null && isset($datoEdicion['id']['presupuesto']))
                                                                    @if ($datoEdicion['id']['presupuesto'] == $evento->id && $datoEdicion['id']['servicio'] == $servicio->id && $datoEdicion['column'] == 'servicioHoraTiempo')
                                                                        <input type="time"
                                                                            wire:model.lazy="datoEdicion.value"
                                                                            wire:change.lazy="terminarEdicionServicio">
                                                                    @else
                                                                        <span class="align-middle"
                                                                            wire:click="detectarEdicionServicio('{{ $evento->id }}', '{{ $servicio->id }}', 'servicioHoraTiempo')">{{ $servicio->pivot->tiempo }}</span>
                                                                    @endif
                                                                @else
                                                                    <span class="align-middle"
                                                                        wire:click="detectarEdicionServicio('{{ $evento->id }}', '{{ $servicio->id }}', 'servicioHoraTiempo')">{{ $servicio->pivot->tiempo }}</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($datoEdicion['id'] != null && isset($datoEdicion['id']['presupuesto']))
                                                                    @if ($datoEdicion['id']['presupuesto'] == $evento->id && $datoEdicion['id']['servicio'] == $servicio->id && $datoEdicion['column'] == 'servicioHoraTiempoMontaje')
                                                                        <input type="time"
                                                                            wire:model.lazy="datoEdicion.value"
                                                                            wire:change.lazy="terminarEdicionServicio">
                                                                    @else
                                                                        <span class="align-middle"
                                                                            wire:click="detectarEdicionServicio('{{ $evento->id }}', '{{ $servicio->id }}', 'servicioHoraTiempoMontaje')">{{ $servicio->pivot->tiempo_montaje }}</span>
                                                                    @endif
                                                                @else
                                                                    <span class="align-middle"
                                                                        wire:click="detectarEdicionServicio('{{ $evento->id }}', '{{ $servicio->id }}', 'servicioHoraTiempoMontaje')">{{ $servicio->pivot->tiempo_montaje }}</span>
                                                                @endif
                                                            </td>
                                                        @else
                                                            <td colspan="5">&nbsp;</td>
                                                        @endif
                                                        <td>
                                                            @if ($datoEdicion['id'] != null && isset($datoEdicion['id']['monitor']))
                                                                @if (
                                                                    $datoEdicion['id']['presupuesto'] == $evento->id &&
                                                                        $datoEdicion['id']['monitor'] == $monitoresIndex &&
                                                                        $datoEdicion['id']['servicio'] == $servicio->id &&
                                                                        $datoEdicion['column'] == 'monitorNombre')
                                                                    <div class="col-md-8" x-data=""
                                                                        x-init="$('#select2-monitor').select2();
                                                                        $('#select2-monitor').on('change', function(e) {
                                                                            var data = $('#select2-monitor').select2('val');
                                                                            @this.set('datoEdicion.value', data);
                                                                        });" wire:key='rand()'>
                                                                        <select class="form-control" name="servicioNombre"
                                                                            id="select2-monitor"
                                                                            wire:model.lazy="datoEdicion.value"
                                                                            wire:change.lazy='terminarEdicionMonitores'>
                                                                            <option value="0">Seleccione Monitor
                                                                            </option>
                                                                            @foreach ($monitores_datos as $monitor_select)
                                                                                <option value="{{ $monitor_select->id }}">
                                                                                    {{ $monitor_select->alias }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                @else
                                                                    <span class="align-middle"
                                                                        wire:click="detectarEdicionMonitores('{{ $evento->id }}', '{{ $servicio->id }}', '{{ $monitoresIndex }}', 'monitorNombre')">{{ $this->getMonitor($monitores)}}</span>
                                                                @endif
                                                            @else
                                                                <span class="align-middle"
                                                                    wire:click="detectarEdicionMonitores('{{ $evento->id }}', '{{ $servicio->id }}', '{{ $monitoresIndex }}', 'monitorNombre')">{{ $this->getMonitor($monitores)}}</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($datoEdicion['id'] != null && isset($datoEdicion['id']['monitor']))
                                                                @if (
                                                                    $datoEdicion['id']['presupuesto'] == $evento->id &&
                                                                        $datoEdicion['id']['monitor'] == $monitoresIndex &&
                                                                        $datoEdicion['id']['servicio'] == $servicio->id &&
                                                                        $datoEdicion['column'] == 'sueldoMonitor')
                                                                    <input type="number"
                                                                        wire:model.lazy="datoEdicion.value"
                                                                        wire:change.lazy="terminarEdicionMonitores">
                                                                @else
                                                                    <span class="align-middle"
                                                                        wire:click="detectarEdicionMonitores('{{ $evento->id }}', '{{ $servicio->id }}', '{{ $monitoresIndex }}', 'sueldoMonitor')">{{ json_decode($servicio->pivot->sueldo_monitores, true)[$monitoresIndex] }}
                                                                        €</span>
                                                                @endif
                                                            @else
                                                                <span class="align-middle"
                                                                    wire:click="detectarEdicionMonitores('{{ $evento->id }}', '{{ $servicio->id }}', '{{ $monitoresIndex }}', 'sueldoMonitor')">{{ json_decode($servicio->pivot->sueldo_monitores, true)[$monitoresIndex] }}
                                                                    €</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($datoEdicion['id'] != null && isset($datoEdicion['id']['monitor']))
                                                                @if (
                                                                    $datoEdicion['id']['presupuesto'] == $evento->id &&
                                                                        $datoEdicion['id']['monitor'] == $monitoresIndex &&
                                                                        $datoEdicion['id']['servicio'] == $servicio->id &&
                                                                        $datoEdicion['column'] == 'gasto_gasoil')
                                                                    <input type="number"
                                                                        wire:model.lazy="datoEdicion.value"
                                                                        wire:change.lazy="terminarEdicionMonitores">
                                                                @else
                                                                    <span class="align-middle"
                                                                        wire:click="detectarEdicionMonitores('{{ $evento->id }}', '{{ $servicio->id }}', '{{ $monitoresIndex }}', 'gasto_gasoil')">
                                                                        @if (!empty(json_decode($servicio->pivot->gasto_gasoil, true)))
                                                                            {{ json_decode($servicio->pivot->gasto_gasoil, true)[$monitoresIndex] }}
                                                                            €
                                                                        @endif
                                                                    </span>
                                                                @endif
                                                            @else
                                                                <span class="align-middle"
                                                                    wire:click="detectarEdicionMonitores('{{ $evento->id }}', '{{ $servicio->id }}', '{{ $monitoresIndex }}', 'gasto_gasoil')">
                                                                    @if (!empty(json_decode($servicio->pivot->gasto_gasoil, true)))
                                                                        {{ json_decode($servicio->pivot->gasto_gasoil, true)[$monitoresIndex] }}
                                                                        €
                                                                    @endif
                                                                </span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                            <tr><td colspan="5">No hay monitores asignados.</td></tr>
                                            @endif
                                        @endforeach
                                        @foreach ($evento->presupuesto->packs()->get() as $packIndex => $pack)
                                            <tr>
                                                <th>
                                                    {{ $pack->nombre }}</td>
                                                <th colspan="7">
                                                    </td>
                                            </tr>
                                            @foreach ($pack->servicios() as $servicioIndex => $servicio)
                                                @php
                                                    $monitoresData2 = json_decode($pack->pivot->id_monitores, true);
                                                @endphp
                                                @if (is_array($monitoresData))
                                                    @foreach ($monitoresData2[$servicioIndex] as $monitoresIndex => $monitores)
                                                        @if ($monitoresIndex == 0)
                                                            <tr>
                                                                <td>
                                                                    @if ($monitoresIndex == 0)
                                                                        {{ $servicio->nombre }}
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if ($datoEdicion['id'] != null && isset($datoEdicion['id']['presupuesto']))
                                                                        @if (
                                                                            $datoEdicion['id']['presupuesto'] == $evento->id &&
                                                                                $datoEdicion['id']['pack'] == $pack->id &&
                                                                                $datoEdicion['id']['servicio'] == $servicioIndex &&
                                                                                $datoEdicion['column'] == 'packHoraMontaje')
                                                                            <input type="number"
                                                                                wire:model.lazy="datoEdicion.value"
                                                                                wire:change.lazy="terminarEdicionPack">
                                                                        @else
                                                                            <span class="align-middle"
                                                                                wire:click="detectarEdicionPack('{{ $evento->id }}', '{{ $pack->id }}', '{{ $servicioIndex }}', '{{ $monitoresIndex }}', 'packHoraMontaje')">
                                                                                @if (!empty(json_decode($pack->pivot->horas_montaje, true)))
                                                                                    {{ json_decode($pack->pivot->horas_montaje, true)[$servicioIndex] }}
                                                                                @endif
                                                                            </span>
                                                                        @endif
                                                                    @else
                                                                        <span class="align-middle"
                                                                            wire:click="detectarEdicionPack('{{ $evento->id }}', '{{ $pack->id }}', '{{ $servicioIndex }}', 'packHoraMontaje')">
                                                                            @if (!empty(json_decode($pack->pivot->horas_montaje, true)))
                                                                                {{ json_decode($pack->pivot->horas_montaje, true)[$servicioIndex] }}
                                                                            @endif
                                                                        </span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if ($datoEdicion['id'] != null && isset($datoEdicion['id']['presupuesto']))
                                                                        @if (
                                                                            $datoEdicion['id']['presupuesto'] == $evento->id &&
                                                                                $datoEdicion['id']['pack'] == $pack->id &&
                                                                                $datoEdicion['id']['servicio'] == $servicioIndex &&
                                                                                $datoEdicion['column'] == 'packHoraInicio')
                                                                            <input type="time"
                                                                                wire:model.lazy="datoEdicion.value"
                                                                                wire:change.lazy="terminarEdicionServicioPack">
                                                                        @else
                                                                            <span class="align-middle"
                                                                                wire:click="detectarEdicionPack('{{ $evento->id }}', '{{ $pack->id }}', '{{ $servicioIndex }}', 'packHoraInicio')">
                                                                                @if (!empty(json_decode($pack->pivot->horas_inicio, true)))
                                                                                    {{ json_decode($pack->pivot->horas_inicio, true)[$servicioIndex] }}
                                                                                @endif
                                                                            </span>
                                                                        @endif
                                                                    @else
                                                                        <span class="align-middle"
                                                                            wire:click="detectarEdicionPack('{{ $evento->id }}', '{{ $pack->id }}', '{{ $servicioIndex }}', 'packHoraInicio')">
                                                                            @if (!empty(json_decode($pack->pivot->horas_inicio, true)))
                                                                                {{ json_decode($pack->pivot->horas_inicio, true)[$servicioIndex] }}
                                                                            @endif
                                                                        </span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if ($datoEdicion['id'] != null && isset($datoEdicion['id']['presupuesto']))
                                                                        @if (
                                                                            $datoEdicion['id']['presupuesto'] == $evento->id &&
                                                                                $datoEdicion['id']['pack'] == $pack->id &&
                                                                                $datoEdicion['id']['servicio'] == $servicioIndex &&
                                                                                $datoEdicion['column'] == 'packHoraTiempo')
                                                                            <input type="time"
                                                                                wire:model.lazy="datoEdicion.value"
                                                                                wire:change.lazy="terminarEdicionServicioPack">
                                                                        @else
                                                                            <span class="align-middle"
                                                                                wire:click="detectarEdicionPack('{{ $evento->id }}', '{{ $pack->id }}', '{{ $servicioIndex }}', 'packHoraTiempo')">
                                                                                @if (!empty(json_decode($pack->pivot->tiempos, true)))
                                                                                    {{ json_decode($pack->pivot->tiempos, true)[$servicioIndex] }}
                                                                                @endif
                                                                            </span>
                                                                        @endif
                                                                    @else
                                                                        <span class="align-middle"
                                                                            wire:click="detectarEdicionPack('{{ $evento->id }}', '{{ $pack->id }}', '{{ $servicioIndex }}', 'packHoraTiempo')">
                                                                            @if (!empty(json_decode($pack->pivot->tiempos, true)))
                                                                                {{ json_decode($pack->pivot->tiempos, true)[$servicioIndex] }}
                                                                            @endif
                                                                        </span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if ($datoEdicion['id'] != null && isset($datoEdicion['id']['presupuesto']))
                                                                        @if (
                                                                            $datoEdicion['id']['presupuesto'] == $evento->id &&
                                                                                $datoEdicion['id']['pack'] == $pack->id &&
                                                                                $datoEdicion['id']['servicio'] == $servicioIndex &&
                                                                                $datoEdicion['column'] == 'packHoraTiempoMontaje')
                                                                            <input type="time"
                                                                                wire:model.lazy="datoEdicion.value"
                                                                                wire:change.lazy="terminarEdicionServicioPack">
                                                                        @else
                                                                            <span class="align-middle"
                                                                                wire:click="detectarEdicionPack('{{ $evento->id }}', '{{ $pack->id }}', '{{ $servicioIndex }}', 'packHoraTiempoMontaje')">
                                                                                @if (!empty(json_decode($pack->pivot->tiempos_montaje, true)))
                                                                                    {{ json_decode($pack->pivot->tiempos_montaje, true)[$servicioIndex] }}
                                                                                @endif
                                                                            </span>
                                                                        @endif
                                                                    @else
                                                                        <span class="align-middle"
                                                                            wire:click="detectarEdicionPack('{{ $evento->id }}', '{{ $pack->id }}', '{{ $servicioIndex }}', 'packHoraTiempoMontaje')">
                                                                            @if (!empty(json_decode($pack->pivot->tiempos_montaje, true)))
                                                                                {{ json_decode($pack->pivot->tiempos_montaje, true)[$servicioIndex] }}
                                                                            @endif
                                                                        </span>
                                                                    @endif
                                                                </td>
                                                            @else
                                                                <td colspan="5">&nbsp;</td>
                                                        @endif
                                                        <td>
                                                            @if ($datoEdicion['id'] != null && isset($datoEdicion['id']['monitor']))
                                                                @if (
                                                                    $datoEdicion['id']['presupuesto'] == $evento->id &&
                                                                        $datoEdicion['id']['servicio'] == $servicioIndex &&
                                                                        $datoEdicion['id']['monitor'] == $monitoresIndex &&
                                                                        $datoEdicion['column'] == 'monitorNombrePack')
                                                                    <div class="col-md-8" x-data=""
                                                                        x-init="$('#select2-monitorPack').select2();
                                                                        $('#select2-monitorPack').on('change', function(e) {
                                                                            var data = $('#select2-monitorPack').select2('val');
                                                                            @this.set('datoEdicion['
                                                                                value ']', data);
                                                                        });" wire:key='rand()'>
                                                                        <select class="form-control" name="servicioNombre"
                                                                            id="select2-monitorPack"
                                                                            wire:model.lazy="datoEdicion.value"
                                                                            wire:change.lazy='terminarEdicionMonitoresPack'>
                                                                            <option value="0">-- ELIGE UN SERVICIO
                                                                                --
                                                                            </option>
                                                                            @foreach ($monitores_datos as $monitor_select)
                                                                                <option
                                                                                    value="{{ $monitor_select->id }}">
                                                                                    {{ $monitor_select->alias }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                @else
                                                                    <span class="align-middle"
                                                                        wire:click="detectarEdicionMonitoresPack('{{ $evento->id }}', '{{ $pack->id }}', '{{ $servicioIndex }}', '{{ $monitoresIndex }}', 'monitorNombrePack')">{{ $this->getMonitor($monitores) }}"
                                                                    </span>
                                                                @endif
                                                            @else
                                                                <span class="align-middle"
                                                                    wire:click="detectarEdicionMonitoresPack('{{ $evento->id }}', '{{ $pack->id }}', '{{ $servicioIndex }}', '{{ $monitoresIndex }}', 'monitorNombrePack')">{{ $this->getMonitor($monitores) }}</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($datoEdicion['id'] != null && isset($datoEdicion['id']['monitor']))
                                                                @if (
                                                                    $datoEdicion['id']['presupuesto'] == $evento->id &&
                                                                        $datoEdicion['id']['servicio'] == $servicioIndex &&
                                                                        $datoEdicion['id']['monitor'] == $monitoresIndex &&
                                                                        $datoEdicion['column'] == 'sueldoMonitorPack')
                                                                    <input type="number"
                                                                        wire:model.lazy="datoEdicion.value"
                                                                        wire:change.lazy="terminarEdicionMonitoresPack">
                                                                @else
                                                                    <span class="align-middle"
                                                                        wire:click="detectarEdicionMonitoresPack('{{ $evento->id }}', '{{ $pack->id }}', '{{ $servicioIndex }}', '{{ $monitoresIndex }}', 'sueldoMonitorPack')">{{ json_decode($pack->pivot->sueldos_monitores, true)[$servicioIndex][$monitoresIndex] }}
                                                                        €
                                                                    </span>
                                                                @endif
                                                            @else
                                                                <span class="align-middle"
                                                                    wire:click="detectarEdicionMonitoresPack('{{ $evento->id }}', '{{ $pack->id }}', '{{ $servicioIndex }}', '{{ $monitoresIndex }}', 'sueldoMonitorPack')">{{ json_decode($pack->pivot->sueldos_monitores, true)[$servicioIndex][$monitoresIndex] }}
                                                                    €</span>
                                                            @endif

                                                        </td>
                                                        <td>0 €</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                <tr><td colspan="5">No hay monitores asignados.</td></tr>
                                                @endif
                                            @endforeach
                                        @endforeach
                                        <tr>
                                            <th colspan="8">Observaciones</th>
                                        </tr>
                                        <tr>
                                            @if ($presupuesto)
                                            <th colspan="8">
                                                {{ $presupuesto->observaciones }}
                                            </th>
                                            @endif
                                        </tr>
                                        <tr>
                                            <th colspan="8">Posibiliadad de Montaje</th>
                                        </tr>
                                        <tr>
                                            @if ($evento)
                                            <th colspan="8">
                                                {{ $evento->eventoMontaje }}
                                            </th>
                                            @endif
                                        </tr>
                                    </table>
                                @endif
                                @endforeach
                            @else
                                <h6 class="text-center">No hay eventos para este día.</h6>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card m-b-30 position-fixed">
                <div class="card-body">
                    <h5>Elige una semana para resumir</h5>
                    <div class="row">
                        <div class="col-12">
                            <input type="week" class="form-control" wire:model="semana"
                                wire:change="cambioSemana">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @section('scripts')
    <!-- Carga jsPDF y el plugin autoTable -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <script>
        document.addEventListener('click', function(event) {
            if (event.target.tagName !== 'INPUT' && event.target.tagName !== 'SPAN') {
                Livewire.emit('terminarInputs');
            }
        });
    </script>


<script>
   document.addEventListener('livewire:load', function () {
    attachExportButtons();

    Livewire.hook('message.processed', (message, component) => {
        attachExportButtons(); // Reasigna eventos después de cambios en Livewire
    });

    function attachExportButtons() {
        document.querySelectorAll("[data-export-table]").forEach(button => {
            button.removeEventListener("click", exportTableToPDF);
            button.addEventListener("click", exportTableToPDF);
        });
    }

    function exportTableToPDF(event) {
        let tableId = event.target.getAttribute("data-export-table");
        let filename = event.target.getAttribute("data-filename");

        let element = document.getElementById(tableId);
        if (!element) {
            alert('No se encontró la tabla para exportar.');
            return;
        }

        let opt = {
            margin: [5, 5, 5, 5],  // Márgenes ajustados
            filename: filename + '.pdf',
            image: { type: 'jpeg', quality: 1 }, // Máxima calidad
            html2canvas: {
                scale: 3,  // Aumenta la escala para más nitidez
                useCORS: true,  // Para asegurar que se capturen imágenes externas
                allowTaint: true,  // Evita problemas de seguridad con imágenes
                logging: false,  // Reduce el log en consola
                scrollX: 0,  // Evita que recorte la tabla si hay desplazamiento horizontal
                scrollY: 0
            },
            jsPDF: {
                orientation: 'landscape', // Cambiar a landscape
                unit: 'mm',
                format: 'a4',
                compressPDF: true
            }
        };

        // Generar el PDF
        html2pdf().set(opt).from(element).save();
    }
});
</script>
@endsection

</div>
