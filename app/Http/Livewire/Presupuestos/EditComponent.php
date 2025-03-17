<?php

namespace App\Http\Livewire\Presupuestos;

use App\Models\Cliente;
use App\Models\Evento;
use App\Models\Articulos;
use App\Models\TipoEvento;
use App\Models\CategoriaEvento;
use App\Models\ServicioEvento;
use App\Models\ServicioPresupuesto;
use App\Models\PackPresupuesto;
use App\Models\Monitor;
use App\Models\Presupuesto;
use App\Models\Contrato;
use App\Models\Facturas;
use App\Models\Iva;
use App\Models\Programa;
use App\Models\Servicio;
use App\Models\ServicioPack;
use App\Models\Settings;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Arr;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EditComponent extends Component
{

    use LivewireAlert;

    public $num_arti;
    public $identificador;
    public $diaMostrar;
    public $contrato_id;
    public $currentStep = 1; // Pasos para el formulario, 1 es el comienzo y 3 el final
    public $nPresupuesto; // Numero de presupusto
    public $fechaEmision; // Fecha del presupuesto
    public $fechaVencimiento; // Fecha del presupuesto
    public $clienteSeleccionado; // Cliente seleccionado
    public $categoria_evento_id = 0;

    public $indicador_montaje = 1;
    public $preciosBasePack = [];
    public $tipos_evento;
    public $categorias_evento;
    public $gestor_id;
    public $id_evento = 0; // 0 por defecto por si no se selecciona ninguna
    public $id_cliente = 0; // 0 por defecto por si no se selecciona ninguna
    public $observaciones = "";
    public $estado;
    //cliente
    public $editandoCliente = 0;

    public $trato = null;
    public $nombre;
    public $apellido;
    public $tipoCalle;
    public $calle;
    public $numero;
    public $direccionAdicional1 = "";
    public $direccionAdicional2 = "";
    public $direccionAdicional3 = "";
    public $codigoPostal;
    public $ciudad;
    public $nif;
    public $tlf1;
    public $tlf2 = 0;
    public $tlf3 = 0;
    public $email1;
    public $email2 = "No";
    public $email3 = "No";
    public $confPostal = false;
    public $confEmail = false;
    public $confSms = false;

    public $clienteIsSave;

    public $clientes;

    public $cliente;

    public $clienteExistente = false;

    //Evento
    public $evento;
    public $eventoNombre;
    public $eventoProtagonista;
    public $eventoNiños;
    public $eventoAdulto;
    public $eventoContacto;
    public $eventoParentesco;
    public $eventoTelefono;
    public $eventoLugar;
    public $eventoLocalidad;
    public $eventoMontaje = false;
    public $diaFinal;
    public $diaEvento;
    public $dias = [];

    public $eventoIsSave = false;
    public $editarEvento = 0;

    //monitores
    public $monitores;
    public $id_monitor;
    public $desplazamiento;
    public $timeDif;
    public $empty;
    //Selector

    public $tipo_seleccionado;
    public $id_pack;

    public $preciosMonitores = [];
    public $tiemposPack = [];
    public $horasInicioPack = [];
    public $horasFinalizacionPack = [];

    public $numero_monitores = 0;

    public $precioFinalPack = 0;
    public $precioFinalServicio = 0;


    // Servicios
    public $id_servicio;
    public $comienzoMontaje;
    public $tiempo = 0;
    public $hora_inicio = 0;
    public $hora_finalizacion = 0;

    public $horaMontaje;
    public $tiempoMontaje;
    public $tiempoDesmontaje;
    public $precioMonitor;
    public $costoDesplazamiento;
    public $dia;
    public $minMonitores;
    public $numMonitores;
    public $importeBase;
    public $descuentoServicio;
    public $importeServicioDesc;

    public $serviciosListDia = [];
    public $servicioEventoList = [];

    public $servicioIsSave;


    public $servicios;

    public $precioServicio;
    public $servicio;

    public $packs;
    public $pack_seleccionado = 0;

    public $servicio_seleccionado = 0;

    public $listaPacks = [];
    public $listaPacksEliminar = [];

    public $eventoServicios;
    public $programas;
    public $listaServicios = [];
    public $listaServiciosEliminar = [];

    //pricing
    public $precioBase;
    public $descuento = 0;
    public $precioFinal = 0;
    public $entrega;
    public $adelanto = 0;


    //Descuentos
    public $descTotal = 0;
    public $entregaDiscount;
    public $totalDiscount;


    //triggers
    public $addDiscount = false;
    public $addPrograma = false;
    public $addCliente = false;
    public $addServicio = false;
    public $addObservaciones = false;


    //Debug
    public $baseMonitor;
    public $servicioEvento;
    public $key = 0;
    public $currentProgram;
    public $validatePrograms;
    public $type;
    public $lowerN;
    public $metodoPago = 'Efectivo';
    public $cuentaTransferencia;
    public $authImagen = 1;
    public $authMenores = 1;


    public $clienteNuevo = false;
    public $mensajeCliente = false;
    public $presupuesto;
    public $tiemposMontajePack = [];
    public $tiemposDesmontajePack = [];


    public $horasMontajePack = [];
    public $idMonitoresPack = [];
    public $sueldoMonitoresPack = [];
    public $gastosGasoilPack = [];
    public $pagosPendientesPack = [];

    public $idMonitores = [];
    public $sueldoMonitores = [];
    public $gastosGasoil = [];
    public $pagosPendientes = [];
    public $nombreGestor;
    public $articulos;
    public $articulos1;
    public $articulo_seleccionado;
    public $articulos_seleccionados = [];
    public $concepto;
    public $visible = 1;
    public $ivaLista;
    public $ivaSeleccionado;
    public $iva_id;
    public $iva_valor;

    public $factura_propia;
    public $cliente_vip;

    public function updatedIvaSeleccionado($value)
    {
        $iva = Iva::find($value);
       $this->iva_id = $value ?? null;
       $this->iva_valor = $iva->iva ?? null;
    }

    public function mount()
    {

        $this->presupuesto = Presupuesto::find($this->identificador);
        $this->categoria_evento_id = $this->presupuesto->categoria_evento_id;
        $this->nPresupuesto = $this->presupuesto->id;
        $this->observaciones = $this->presupuesto->observaciones;
        $this->addObservaciones = $this->observaciones > " ";
        $this->precioBase = $this->presupuesto->precioBase;
        $this->iva_id = $this->presupuesto->iva_id;
        $this->ivaSeleccionado = $this->presupuesto->iva_id;
        $this->iva_valor = $this->presupuesto->iva_valor;
        $this->ivaLista = Iva::all();
        $this->precioFinal = $this->presupuesto->precioBase;
        $this->descuento = $this->presupuesto->descuento;
        $this->addDiscount = $this->descuento > 0;
        $this->adelanto = $this->presupuesto->adelanto;
        $this->estado = $this->presupuesto->estado;
        $this->fechaVencimiento = $this->presupuesto->fechaVencimiento;
        $this->gestor_id = $this->presupuesto->gestor_id ? $this->presupuesto->gestor_id : Auth::id();
        $gestor = User::firstWhere('id', $this->gestor_id);
        $this->nombreGestor = optional($gestor)->name . " " . optional($gestor)->surname;
        $this->articulos = Articulos::all();
        $this->articulos1 = Articulos::all();
        $this->factura_propia = $this->presupuesto->factura_propia;
        $this->cliente_vip = $this->presupuesto->cliente_vip;


        //Cliente
        $this->id_cliente = $this->presupuesto->id_cliente;
        $this->clientes = Cliente::all(); // datos que se envian al select2
        $this->cliente = Cliente::where('id', $this->presupuesto->id_cliente)->first();

        if (Contrato::where('id_presupuesto', $this->identificador)->exists()) {
            $this->contrato_id = Contrato::firstWhere('id_presupuesto', $this->identificador)->id;
        }
        //Evento
        $this->id_evento = $this->presupuesto->id_evento;
        $this->evento = Evento::where('id', $this->id_evento)->first();
        $this->eventoNombre = $this->evento->eventoNombre;
        $this->eventoProtagonista = $this->evento->eventoProtagonista;
        $this->eventoNiños = $this->evento->eventoNiños;
        $this->eventoAdulto = $this->evento->eventoAdulto;
        $this->eventoContacto = $this->evento->eventoContacto;
        $this->eventoParentesco = $this->evento->eventoParentesco;
        $this->eventoTelefono = $this->evento->eventoTelefono;
        $this->eventoLugar = $this->evento->eventoLugar;
        $this->eventoLocalidad = $this->evento->eventoLocalidad;
        $this->eventoMontaje = $this->evento->eventoMontaje;
        $this->diaFinal = $this->evento->diaFinal;
        $this->diaEvento = $this->evento->diaEvento;

        foreach ($this->presupuesto->servicios()->get() as $servicio) {
            $numMonitores = $servicio->pivot->numero_monitores;

            // Preparar un array basado en numero_monitores
            $defaultArray = array_fill(0, $numMonitores, '0');

            $this->listaServicios[] = [
                'id_pivot' => ServicioPresupuesto::where('servicio_id', $servicio->id)->where('presupuesto_id', $this->identificador)->first(),
                'id' => $servicio->id,
                'numero_monitores' => $numMonitores,
                'precioFinal' => $servicio->pivot->precio_final ?? '0',
                'tiempo' => $servicio->pivot->tiempo ?? '0',
                'hora_inicio' => $servicio->pivot->hora_inicio ?? '0',
                'hora_finalizacion' => $servicio->pivot->hora_finalizacion ?? '0',
                'id_monitores' => json_decode($servicio->pivot->id_monitores, true) ?? $defaultArray,
                'sueldo_monitores' => json_decode($servicio->pivot->sueldo_monitores, true) ?? $defaultArray,
                'gasto_gasoil' => json_decode($servicio->pivot->gasto_gasoil, true) ?? $defaultArray,
                'pago_pendiente' => json_decode($servicio->pivot->pago_pendiente, true) ?? $defaultArray,
                'hora_montaje' => $servicio->pivot->hora_montaje ?? '0',
                'tiempo_montaje' => $servicio->pivot->tiempo_montaje ?? '0',
                'tiempo_desmontaje' => $servicio->pivot->tiempo_desmontaje ?? '0',
                'articulo_seleccionado' => $servicio->pivot->articulo_seleccionado ?? '0',
                'num_art_indef' => $servicio->pivot->num_art_indef,
                'concepto'=> $servicio->pivot->concepto,
                'visible'=> $servicio->pivot->visible,
                'existente' => 1
            ];
        }

        foreach ($this->presupuesto->packs()->get() as $pack) {
            $numMonitores = json_decode($pack->pivot->numero_monitores, true);

            // Preparar arrays basados en numero_monitores
            $defaultArray = array_fill(0, count($numMonitores), '0');
            $defaultDoubleArray = array_map(function () use ($defaultArray) {
                return $defaultArray;
            }, $numMonitores);

            $this->listaPacks[] = [
                'id_pivot' => PackPresupuesto::where('pack_id', $pack->id)->where('presupuesto_id', $this->identificador)->first(),
                'id' => $pack->id,
                'numero_monitores' => $numMonitores,
                'precioFinal' => $pack->pivot->precio_final ?? '0',
                'tiempos' => $pack->pivot->tiempos ? json_decode($pack->pivot->tiempos, true) : $defaultDoubleArray,
                'horas_inicio' => $pack->pivot->horas_inicio ? json_decode($pack->pivot->horas_inicio, true) : $defaultDoubleArray,
                'horas_finalizacion' => $pack->pivot->horas_finalizacion ? json_decode($pack->pivot->horas_finalizacion, true) : $defaultDoubleArray,
                'id_monitores' => $pack->pivot->id_monitores ? json_decode($pack->pivot->id_monitores, true) : $defaultArray,
                'sueldos_monitores' => $pack->pivot->sueldos_monitores ? json_decode($pack->pivot->sueldos_monitores, true) : $defaultArray,
                'gastos_gasoil' => $pack->pivot->gastos_gasoil ? json_decode($pack->pivot->gastos_gasoil, true) : $defaultArray,
                'horas_montaje' => $pack->pivot->horas_montaje ? json_decode($pack->pivot->horas_montaje, true) : $defaultDoubleArray,
                'tiempos_montaje' => $pack->pivot->tiempos_montaje ? json_decode($pack->pivot->tiempos_montaje, true) : $defaultDoubleArray,
                'tiempos_desmontaje' => $pack->pivot->tiempos_desmontaje ? json_decode($pack->pivot->tiempos_desmontaje, true) : $defaultDoubleArray,
                'pagos_pendientes' => $pack->pivot->pagos_pendientes ? json_decode($pack->pivot->pagos_pendientes, true) : $defaultArray,
                'articulos_seleccionados' => $pack->pivot->articulos_seleccionados ? json_decode($pack->pivot->articulos_seleccionados, true) : $defaultArray,
                'existente' => 1
            ];
        }

        // foreach ($this->presupuesto->packs()->get() as $pack) {

        //     $this->listaPacks[] = [
        //         'id' => $pack->id,
        //         'numero_monitores' => json_decode($pack->pivot->numero_monitores, true),
        //         'precioFinal' => $pack->pivot->precio_final,
        //         'tiempos' => json_decode($pack->pivot->tiempos, true),
        //         'horas_inicio' => json_decode($pack->pivot->horas_inicio, true),
        //         'horas_finalizacion' => json_decode($pack->pivot->horas_finalizacion, true),
        //         'id_monitores' => json_decode($pack->pivot->id_monitores, true),
        //         'sueldos_monitores' => json_decode($pack->pivot->sueldos_monitores, true),
        //         'gastos_gasoil' => json_decode($pack->pivot->gastos_gasoil, true),
        //         'horas_montaje' => json_decode($pack->pivot->horas_montaje, true),
        //         'tiempos_montaje' => json_decode($pack->pivot->tiempos_montaje, true),
        //         'tiempos_desmontaje' => json_decode($pack->pivot->tiempos_desmontaje, true),
        //         'pagos_pendientes' => json_decode($pack->pivot->pagos_pendientes, true),
        //         'existente' => 1
        //     ];
        // }
        // $this->servicioEventoList = ServicioEvento::where("id_evento", $this->evento->id)->get()->toArray();

        $this->programas = Programa::all();
        $this->tipos_evento = TipoEvento::all();
        $this->categorias_evento = CategoriaEvento::all();
        $this->monitores = Monitor::all();
        $this->servicios = Servicio::all();

        $this->packs = ServicioPack::all();


        $this->fechaEmision = $this->presupuesto->fechaEmision;
        $this->setupServicioEventoList($this->serviciosListDia);
        $this->getTotalPrice();
        $this->realizarAccion();
    }

    public function render()
    {
        $this->clienteSeleccionado = Cliente::find($this->id_cliente);
        return view('livewire.presupuestos.edit-component');
    }

    public function setupEvento($id)
    {
        $this->evento = Evento::find($id);

        $this->eventoNombre = $this->evento->eventoNombre;
        $this->eventoProtagonista = $this->evento->eventoProtagonista;
        $this->eventoNiños = $this->evento->eventoNiños;
        $this->eventoContacto = $this->evento->eventoContacto;
        $this->eventoParentesco = $this->evento->eventoParentesco;
        $this->eventoTelefono = $this->evento->eventoTelefono;
        $this->eventoLugar = $this->evento->eventoLugar;
        $this->eventoLocalidad = $this->evento->eventoLocalidad;
        $this->eventoMontaje = $this->evento->eventoMontaje;
        $this->diaFinal = $this->evento->diaFinal;
        $this->diaEvento = $this->evento->diaEvento;
        $this->dia = $this->diaEvento;
        $this->getDias();
    }


    public function updatePresupuestoValidacion()
    {
        if ($this->diaEvento == null) {
            return $this->alert('error', '¡No se ha selccionado ningun dia de comienzo del evento!', [
                'position' => 'center',
                'toast' => false,
            ]);
        }
        if ($this->diaFinal == null) {
            return $this->alert('error', '¡No se ha selccionado ningun dia de finalizacion del evento!', [
                'position' => 'center',
                'toast' => false,
            ]);
        }

        if ($this->eventoNombre == null) {
            return $this->alert('error', '¡El campo de Evento no puede estar vacio!', [
                'position' => 'center',
                'toast' => false,
            ]);
        }

        if ($this->eventoProtagonista == null) {
            return $this->alert('error', '¡El campo de Protagonistas no puede estar vacio!', [
                'position' => 'center',
                'toast' => false,
            ]);
        }
        /*if ($this->eventoNiños == null) {
            return $this->alert('error', '¡El campo de Numero de niños no puede estar vacio!', [
                'position' => 'center',
                'toast' => false,
            ]);
        }*/

        if ($this->eventoContacto == null) {
            return $this->alert('error', '¡El campo de Contacto no puede estar vacio!', [
                'position' => 'center',
                'toast' => false,
            ]);
        }

        if ($this->eventoParentesco == null) {
            return $this->alert('error', '¡El campo de Parentesco no puede estar vacio!', [
                'position' => 'center',
                'toast' => false,
            ]);
        }

        if ($this->eventoLugar == null) {
            return $this->alert('error', '¡El campo de Lugar no puede estar vacio!', [
                'position' => 'center',
                'toast' => false,
            ]);
        }

        if ($this->eventoTelefono == null) {
            return $this->alert('error', '¡El campo de Telefono no puede estar vacio!', [
                'position' => 'center',
                'toast' => false,
            ]);
        }

        if ($this->eventoLocalidad == null) {
            return $this->alert('error', '¡El campo de Localidad no puede estar vacio!', [
                'position' => 'center',
                'toast' => false,
            ]);
        }


        $this->updateEvento();
    }
    public function getDias()
    {

        $this->dias = [];
        if ($this->diaEvento != null && $this->diaFinal != null) {
            $period = CarbonPeriod::create($this->diaEvento, $this->diaFinal);

            foreach ($period as $i => $date) {
                $this->dias[$i] = $date->format("Y-m-d");
                // dd(empty($this->serviciosListDia));
                if (empty($this->serviciosListDia)) {
                    $this->serviciosListDia[$this->dias[$i]] = [];
                }
            }
            $lenght = count($this->dias);
            $servListLength = count($this->serviciosListDia);
            if ($lenght < $servListLength) {

                array_splice($this->serviciosListDia, $lenght);
            }

            $this->dia = $this->dias[0];
        }
    }

    public function setupClient($id)
    {

        $this->cliente = $this->clientes->find($id);
        $this->trato = $this->cliente->trato;
        $this->nombre = $this->cliente->nombre;
        $this->apellido = $this->cliente->apellido;
        $this->tipoCalle = $this->cliente->tipoCalle;
        $this->calle = $this->cliente->calle;
        $this->numero = $this->cliente->numero;
        $this->direccionAdicional1 = $this->cliente->direccionAdicional1;
        $this->direccionAdicional2 = $this->cliente->direccionAdicional2;
        $this->direccionAdicional3 = $this->cliente->direccionAdicional3;
        $this->codigoPostal = $this->cliente->codigoPostal;
        $this->ciudad = $this->cliente->ciudad;
        $this->nif = $this->cliente->nif;
        $this->tlf1 = $this->cliente->tlf1;
        $this->tlf2 = $this->cliente->tlf2;
        $this->tlf3 = $this->cliente->tlf3;
        $this->email1 = $this->cliente->email1;
        $this->email2 = $this->cliente->email2;
        $this->email3 = $this->cliente->email3;
        $this->confPostal = $this->cliente->confPostal;
        $this->confEmail = $this->cliente->confEmail;
        $this->confSms = $this->cliente->confSms;
    }

    public function setupServicioEventoList($serviciosListDia)
    {

        foreach ($serviciosListDia as $dia => $servicioEventoList) {
            foreach ($servicioEventoList as $key => $servicioEvento) {
                $programas = [];
                $programas = Programa::where("id_servicioEvento", $servicioEvento["id"])->get()->toArray();
                $servicio = Servicio::where("id", $servicioEvento["id_servicio"])->first();
                $pack = ServicioPack::where("id", $servicio->id_pack)->first();
                // dd($programas);
                foreach ($programas as $i => $programa) {
                    $this->type = substr($programa["comienzoEvento"], 0, 4);
                    $programas[$i]["comienzoEvento"] = substr($programa["comienzoEvento"], 0, 5);
                    $programas[$i]["comienzoMontaje"] = substr($programa["comienzoMontaje"], 0, 5);

                    $programas[$i]["costoDesplazamiento"] = is_null($programas[$i]["costoDesplazamiento"]) ? 0 : $programas[$i]["costoDesplazamiento"];
                }
                $servicioEvento["programas"] = $programas;
                $horaMontaje = substr($servicioEvento["comienzoMontaje"], 0, 5);
                $servicioEvento["comienzoMontaje"] = $horaMontaje;
                $horaInicio = substr($servicioEvento["horaInicio"], 0, 5);
                $servicioEvento["horaInicio"] = $horaInicio;
                $servicioEvento["horaFin"] = $this->horaFinalizacion($servicioEvento);
                $servicioEvento["pack"] = $pack->nombre;


                $servicioEventoList[$key] =  $servicioEvento;
                $this->serviciosListDia[$dia] = $servicioEventoList;
                $this->refreshEndTime($key, $dia);
                $this->addProgramServiceField($servicioEvento["numMonitores"], $key, $dia);
            }
        }
    }

    //Selecciona un cliente y rellena los campos con los datos del mismo
    public function selectClient($solicitante)
    {
        $cliente = $this->clientes->where("id", $solicitante)->first();
        $this->id_cliente = $solicitante;
        $this->trato = $cliente->trato;
        $this->nombre = $cliente->nombre;
        $this->apellido = $cliente->apellido;
        $this->tipoCalle = $cliente->tipoCalle;
        $this->calle = $cliente->calle;
        $this->numero = $cliente->numero;
        $this->direccionAdicional1 = $cliente->direccionAdicional1;
        $this->direccionAdicional2 = $cliente->direccionAdicional2;
        $this->direccionAdicional3 = $cliente->direccionAdicional3;
        $this->codigoPostal = $cliente->codigoPostal;
        $this->ciudad = $cliente->ciudad;
        $this->nif = $cliente->nif;
        $this->tlf1 = $cliente->tlf1;
        $this->tlf2 = $cliente->tlf2;
        $this->tlf3 = $cliente->tlf3;
        $this->email1 = $cliente->email1;
        $this->email2 = $cliente->email2;
        $this->email3 = $cliente->email3;
        $this->emit("refreshComponent");
    }

    //Crea un cliente o lo actualiza si existe
    public function submitClient()
    {

        $clientValidate = $this->validate(
            [
                'nombre' => 'required',
                'apellido' => 'required',
                'tipoCalle' => 'required',
                'calle' => 'required',
                'nif' => 'required',
                'numero' => 'required',
                'codigoPostal' => 'required',
                'ciudad' => 'required',
                'tlf1' => 'required',
                'email1' => 'required',

            ],
            // Mensajes de error
            [
                'nombre.required' => "El nombre es obligatorio",
                'apellido.required' => "Los apellidos son obligatorios",
                'tipoCalle.required' => "El tipo de calle es obligatorio",
                'calle.required' => "La calle es obligatoria",
                'nif.required' => "El NIF/DNI es obligatorio",
                'numero.required' => "El numero es obligatorio",
                'codigoPostal.required' => "El código postal es obligatorio",
                'ciudad.required' => "La ciudad es obligatoria",
                'tlf1.required' => "El telefono es obligatorio",
                'email1.required' => "El email es obligatorio",
            ]
        );

        if ($this->clienteExistente) {
            $this->clienteIsSave = Cliente::where("id", $this->id_cliente)->update(array_merge(
                $clientValidate,
                [
                    "trato" => $this->trato,
                    "direccionAdicional1" => $this->direccionAdicional1,
                    "direccionAdicional2" => $this->direccionAdicional2,
                    "direccionAdicional3" => $this->direccionAdicional3,
                    "tlf2" => $this->tlf2,
                    "tlf3" => $this->tlf3,
                    "email2" => $this->email2,
                    "email3" => $this->email3,
                    "confPostal" => $this->confPostal,
                    "confEmail" => $this->confEmail,
                    "confSms" => $this->confSms,
                ]
            ));
            if ($this->clienteIsSave) {
                $this->clienteIsSave = $this->id_cliente;
            }
        } else {
            $this->clienteIsSave = Cliente::create(array_merge(
                $clientValidate,
                [
                    "trato" => $this->trato,
                    "direccionAdicional1" => $this->direccionAdicional1,
                    "direccionAdicional2" => $this->direccionAdicional2,
                    "direccionAdicional3" => $this->direccionAdicional3,
                    "tlf2" => $this->tlf2,
                    "tlf3" => $this->tlf3,
                    "email2" => $this->email2,
                    "email3" => $this->email3,
                    "confPostal" => $this->confPostal,
                    "confEmail" => $this->confEmail,
                    "confSms" => $this->confSms,
                ]
            ));
        }
        if ($this->clienteIsSave) {
            $this->id_cliente = $this->clienteIsSave;
            $this->alert('success', 'Cliente actualizado correctamente!', [
                'position' => 'center',
                'timer' => 1500,
                'toast' => false,
                'timerProgressBar' => true,
            ]);
        } else {
            $this->alert('error', '¡No se ha podido actualizar la información del usuario!', [
                'position' => 'center',
                'toast' => false,
            ]);
        }
    }

    //Crea un evento en la base de datos
    public function updateEvento()
    {
        $diaEvento = Carbon::parse($this->diaEvento);
        $diaFin = Carbon::parse($this->diaFinal);

        if ($diaEvento > $diaFin) {
            $this->diaFinal = $this->diaEvento;
        }

        $eventValidate = $this->validate(
            [
                'eventoNombre' => 'required',
                'eventoProtagonista' => 'required',
                'eventoNiños' => 'nullable',
                'eventoContacto' => 'required',
                'eventoParentesco' => 'required',
                'eventoTelefono' => 'required',
                'eventoLugar' => 'required',
                'eventoLocalidad' => 'required',
                'diaEvento' => 'required',
                'diaFinal' => 'required',
                'eventoMontaje' => 'nullable'

            ],
            // Mensajes de error
            [
                'eventoNombre.required' => "El nombre del evento es obligatorio",
                'eventoProtagonista.required' => "Los protagonistas son obligatorios",
                'eventoNiños.required' => "La cantidad de niños es obligatoria",
                'eventoContacto.required' => "El nombre del contacto es obligatorio",
                'eventoParentesco.required' => "El parentesco es obligatorio",
                'eventoTelefono.required' => "El telefono es obligatorio",
                'eventoLugar.required' => "El lugar es obligatorio",
                'eventoLocalidad.required' => "La localidad es obligatoria",
                'diaEvento.required' => "El día es obligatorio",
                'diaFinal.required' => "El día final es obligatorio",
            ]
        );

        $this->eventoIsSave = $this->evento->update($eventValidate);
        event(new \App\Events\LogEvent(Auth::user(), 12, $this->evento->id));

        if ($this->eventoIsSave) {
            $this->update();
        } else {
            $this->alert('error', '¡No se ha podido actualizar la información del evento!', [
                'position' => 'center',
                'toast' => false,
            ]);
        }
    }

    public function setUpServiceForm($i)
    {
        $dia = $this->dias[$i];
        if (empty($this->serviciosListDia[$dia])) {
            $this->addServiceField($dia);
        }
    }

    public function getImporteBaseServ($servicio)
    {
        $minMonitores = $servicio->minMonitor;
        $importeBase = $servicio->precioBase + ($minMonitores * $servicio->precioMonitor);
        return $importeBase;
    }

    //Servicios
    //Rellena los valores por defecto de un servicio a la hora de añadirlos
    public function setDefaultServicios($key, $id)
    {
        $this->key = $key;

        // dd( $this->serviciosListDia);
        $serviciosDiaList = $this->serviciosListDia;
        $serviciosDia = $serviciosDiaList[$this->dia];


        $servicio = $this->servicios->find($id);
        $pack = ServicioPack::where("id", $servicio->id_pack)->first();
        $servicioEvento["precioMonitor"] = $servicio->precioMonitor;
        $minMonitores = $servicio->minMonitor;
        $importeBase = $this->getImporteBaseServ($servicio);
        $tiempoMontaje = $servicio->tiempoMontaje;
        $tiempoDesmontaje = $servicio->tiempoDesmontaje;


        $serviciosDia[$key] = array(
            'id_servicio' => $id,
            'horaInicio' => '00:00',
            'tiempo' => 1,
            'numMonitores' => $minMonitores,
            "id_evento" => $this->id_evento,
            "dia" => $this->dia,
            "importe" => $importeBase,
            "descuento" => 0,
            "importeBase" => $importeBase,
            "horaFin" => "00:00",
            "programas" => [],
            "comienzoMontaje" => "00:00",
            "tiempoDesmontaje" => $tiempoDesmontaje,
            "tiempoMontaje" => $tiempoMontaje,
            "pack" => $pack->nombre,
        );

        $serviciosDiaList[$this->dia] = $serviciosDia;
        $this->serviciosListDia = $serviciosDiaList;

        $this->addProgramServiceField($minMonitores, count($serviciosDia) - 1);

        $this->getTotalPrice();
    }


    // public function servicioMonitores($key, $id, $dia)
    // {
    //     $this->servicioEvento = $this->servicioEventoList[$key];
    //     $minMonitores = $this->servicios->find($id)->minMonitor;
    //     $this->minMonitores = $this->servicios->find($id)->minMonitor;

    //     $servicioEvento["numMonitores"] = $this->servicios->find($id)->minMonitor;

    //     $this->numMonitores = $servicioEvento["numMonitores"];

    //     return $minMonitores;
    // }

    public function nombreMonitor(int $id)
    {
        $nombreCompleto =  sprintf("%s %s", $this->monitores->find($id)->nombre,  $this->monitores->find($id)->apellidos);
        return $nombreCompleto;
    }

    public function checkTime($key, $i)
    {
        $servicioEvento = $this->servicioEventoList[$key];
        $programa = $servicioEvento["programas"][$i];
        $hora = $programa["horas"];

        if (!is_numeric($hora) || $hora <= 0) {
            $hora = 1;
        }
        $servicioEvento["programas"][$i]["horas"] = $hora;
        $this->servicioEventoList[$key] = $servicioEvento;
    }

    //Calcula y aplica el descuento de un servicio
    public function applyServiceDiscount($key)
    {
        $serviciosDia = $this->serviciosListDia[$this->dia];
        $servicioEvento = $serviciosDia[$key];

        $servicio = $this->servicios->find($servicioEvento["id_servicio"]);
        $numMonitores = $servicioEvento["numMonitores"];
        $importeBase = $servicioEvento["importeBase"];
        if ($importeBase == 0) {
            $importeBase = $servicio->precioBase + ($numMonitores * $servicio->precioMonitor);
        }

        if (
            $servicioEvento["descuento"] === null ||
            !is_numeric($servicioEvento["descuento"]) ||
            $servicioEvento["descuento"] < 0
        ) {
            $this->serviciosListDia[$this->dia][$key]["descuento"] = 0;
            $servicioEvento["descuento"] = 0;
        }
        if ($servicioEvento["descuento"] > 100) {
            $this->serviciosListDia[$this->dia][$key]["descuento"] = 100;
            $servicioEvento["descuento"] = 100;
        }


        $descuentoPC = abs($servicioEvento["descuento"]) / 100;
        $descuento = $importeBase * $descuentoPC;

        $importe = $importeBase - $descuento;

        $servicioEvento["importe"] = $importe;

        $serviciosDia[$key] = $servicioEvento;

        $this->serviciosListDia[$this->dia] = $serviciosDia;

        $this->getTotalPrice();
    }

    //Comprueba el min de monitores y atualiza la lista del formulario
    public function refreshNumMonitores($key)
    {
        $serviciosDia = $this->serviciosListDia[$this->dia];
        $servEv = $serviciosDia[$key];
        $serv = $servEv["id_servicio"];
        $minMonitores = $this->servicios->find($serv)->minMonitor;
        $numMonitores = $servEv["numMonitores"];
        if (!is_numeric($numMonitores) || $numMonitores < $minMonitores) {
            $this->type = !is_numeric($numMonitores);
            $this->lowerN = $numMonitores < $minMonitores;
            $numMonitores = $minMonitores;
            $servEv["numMonitores"] = $minMonitores;
        }
        $serviciosDia[$key] = $servEv;
        $this->serviciosListDia[$this->dia] = $serviciosDia;
        $this->addProgramServiceField($numMonitores, $key, $this->dia);
        $this->applyServiceDiscount($key);
    }

    public function refreshEndTime($key, $dia)
    {
        $serviciosDia = $this->serviciosListDia[$dia];
        $servicioEvento = $serviciosDia[$key];
        $horaInicio = $servicioEvento["horaInicio"];
        $tiempo  = $servicioEvento["tiempo"];


        $servicioEvento["horaFin"] = $this->horaFinalizacion($servicioEvento);

        $serviciosDia[$key] = $servicioEvento;

        $this->serviciosListDia[$dia] = $serviciosDia;


        $comienzoMontaje = $servicioEvento["comienzoMontaje"];
        $tiempo  = $servicioEvento["tiempoMontaje"];
        $minMontaje = strtotime("$comienzoMontaje + $tiempo minutes");

        if (strtotime($horaInicio) < $minMontaje) {
            $this->refreshMountTime($key, $dia);
        }
    }

    // public function setInicioPrograma($keyServicio, $keyPrograma)
    // {
    //     $servicioEvento = $this->servicioEventoList[$keyServicio];
    //     $programa = $servicioEvento["programas"][$keyPrograma];


    //     $programa["comienzoMontaje"] = $servicioEvento["comienzoMontaje"];


    //     $servicioEvento["programas"][$keyPrograma] = $programa;

    //     $this->servicioEventoList[$keyServicio] = $servicioEvento;
    // }


    public function refreshMountTime($key)
    {
        $serviciosDia = $this->serviciosListDia[$this->dia];
        $servicioEvento = $serviciosDia[$key];
        $horaInicio = $servicioEvento["horaInicio"];
        $tiempo  = $servicioEvento["tiempoMontaje"];
        $horaMontaje = $servicioEvento["comienzoMontaje"];

        $this->timeDif = strtotime("$horaInicio - $tiempo minutes");
        if (strtotime("$horaInicio - $tiempo minutes") < strtotime($horaMontaje)) {
            $horaMontaje  = date("H:i", strtotime("$horaInicio - $tiempo minutes"));

            $servicioEvento["comienzoMontaje"] = $horaMontaje;

            $serviciosDia[$key] = $servicioEvento;
            $this->serviciosListDia[$this->dia]  = $serviciosDia;
        }
    }





    //Saca el nombre de un servicio
    public function servicioNombre($id)
    {
        return $this->servicios->find($id)->nombre;
    }




    //Calcula la hora final del servicio a partir de su hora de inicio y su duracion
    public function horaFinalizacion($servicioEvento)
    {
        $horaInicio = $servicioEvento["horaInicio"];
        $tiempo  = $servicioEvento["tiempo"];
        $hora = date("H:i:s", strtotime("$horaInicio + $tiempo hours"));
        // $hora = $servicioEvento->horaInicio->modify("+$servicioEvento->tiempo hours");
        return $hora;
    }

    public function addServiceField()
    {
        $servicioEventoList = $this->serviciosListDia[$this->dia];
        $servicioEventoList[count($servicioEventoList)] = array(
            'id_servicio' => 0,
            'horaInicio' => '00:00',
            'tiempo' => 0,
            'numMonitores' => 0,
            "id_evento" => $this->id_evento,
            "dia" => $this->dia,
            "importe" => 0,
            "descuento" => 0,
            "importeBase" => 0,
            "horaFin" => "00:00",
            "programas" => [],
            "comienzoMontaje" => "00:00",
            "tiempoDesmontaje" => 0,
            "tiempoMontaje" => 0,
            "pack" => "",
        );

        $this->serviciosListDia[$this->dia] = $servicioEventoList;

        // $this->programas[count($this->servicioEventoList)] = [];
    }

    public function addServiceFieldFromPack()
    {
        $servicios = Servicio::where("id_pack", $this->pack)->get();
        $pack = ServicioPack::where("id", $this->pack)->first();
        // dd($servicios);
        $servicioEventoList = $this->serviciosListDia[$this->dia];


        foreach ($servicios as $index => $servicio) {
            // dd($index);
            $serv = [
                'id_servicio' => $servicio->id,
                'horaInicio' => '00:00',
                'tiempo' => 0,
                'numMonitores' => $servicio->minMonitor,
                "id_evento" => $this->id_evento,
                "dia" => $this->dia,
                "importe" => $servicio->precioBase,
                "descuento" => 0,
                "importeBase" => $servicio->precioBase,
                "horaFin" => "00:00",
                "programas" => [],
                "comienzoMontaje" => "00:00",
                "tiempoDesmontaje" => $servicio->tiempoDesmontaje,
                "tiempoMontaje" => $servicio->tiempoMontaje,
                "pack" => $pack->nombre,
            ];

            if ($servicioEventoList[0]["id_servicio"] === 0 && $index == 0) {
                $servicioEventoList[0] = $serv;
            } else {
                array_push($servicioEventoList, $serv);
            }
        }
        $this->serviciosListDia[$this->dia] = $servicioEventoList;
        // dd($servicioEventoList);

        foreach ($servicioEventoList as $index => $servicio) {
            // dd($servicio);
            $this->addProgramServiceField($servicio["numMonitores"], $index);
        }
    }



    public function addProgramServiceField($numMonitores, $index)
    {

        $serviciosDia = $this->serviciosListDia[$this->dia];
        $servicioEvento = $serviciosDia[$index];

        // dd($serviciosDia);
        $id = $servicioEvento["id_servicio"];

        if ($id > 0) {
            $precioMonitor = $this->servicios->find($id)->precioMonitor;

            $programas = [];

            $this->empty = empty($servicioEvento["programas"]);
            $i = 0;
            if (!empty($servicioEvento["programas"])) {
                $i = count($servicioEvento["programas"]);
            }

            $programas = $servicioEvento["programas"];

            for ($i; $i < $numMonitores; $i++) {

                $programas[$i] = array(
                    "id_monitor" => 0,
                    "id_servicioEvento" => 0,
                    "dia" => $this->dia,
                    "precioMonitor" => $precioMonitor,
                    "costoDesplazamiento" => 0,
                    "comienzoMontaje" => $servicioEvento["comienzoMontaje"],
                    "comienzoEvento" => $servicioEvento["horaInicio"],
                    "horas" => $servicioEvento["tiempo"],
                    "tiempoDesmontaje" => 0,
                    "horaFin" => $this->horaFinalizacion($servicioEvento),
                );
            }




            if ($numMonitores < count($servicioEvento["programas"])) {

                $this->currentProgram = array_slice($servicioEvento["programas"], 0, $numMonitores);


                $programas = array_slice($servicioEvento["programas"], 0, $numMonitores);
            }

            $servicioEvento["importeBase"] += $precioMonitor * $numMonitores;
            $servicioEvento["importe"] += $precioMonitor * $numMonitores;
            $servicioEvento["programas"] = $programas;
            $serviciosDia[$index] =  $servicioEvento;
            $this->serviciosListDia[$this->dia] =  $serviciosDia;
            $this->getTotalPrice();
        }
        // dd($this->serviciosListDia);
    }

    public function sumarCostoMonitor($servicio, $key, $i)
    {

        $serviciosDia = $this->serviciosListDia[$this->dia];
        $servicioEvento = $serviciosDia[$key];
        $serv = $this->servicios->find($servicio);
        $baseMonitor = $serv->precioMonitor;


        $this->importeBase = $servicioEvento["importeBase"] + $servicioEvento["programas"][$i]["precioMonitor"] - $baseMonitor;
        // $servicioEvento["importeBase"] += $servicioEvento["programas"][$i]["precioMonitor"] - $baseMonitor;

        $this->servicioEvento = $servicioEvento;
        $this->baseMonitor = $baseMonitor;
        $this->servicioEvento = $servicioEvento;

        $precioMonitor = $servicioEvento["programas"][$i]["precioMonitor"];
        $precioDesplazamiento = $servicioEvento["programas"][$i]["costoDesplazamiento"];

        if ($precioMonitor < $baseMonitor) {
            $servicioEvento["programas"][$i]["precioMonitor"] = $baseMonitor;
            $precioMonitor = $baseMonitor;
        }

        if ($precioDesplazamiento < 0) {
            $servicioEvento["programas"][$i]["costoDesplazamiento"] = 0;
            $precioDesplazamiento = 0;
        } else {
            $precioDesplazamiento = $servicioEvento["programas"][$i]["costoDesplazamiento"];
        }


        $this->precioMonitor = $precioMonitor;

        // $descuentoPC = abs($servicioEvento["descuento"]) / 100;

        $servicioEvento["importeBase"] =
            $this->getImporteBaseServ($serv) +
            $servicioEvento["programas"][$i]["precioMonitor"] - $baseMonitor +
            $precioDesplazamiento;


        // $descuento =  $servicioEvento["importeBase"] * $descuentoPC;


        $servicioEvento["importe"] = $servicioEvento["importeBase"];
        // $servicioEvento["importe"] = $servicioEvento["importeBase"] - $descuento;



        $serviciosDia[$key] = $servicioEvento;
        $this->serviciosListDia[$this->dia] = $serviciosDia;



        $this->applyServiceDiscount($key);
    }


    public function removeServicio($key)
    {

        //Miro si existe en la bd y si es asi lo borro


        if (isset($this->serviciosListDia[$this->dia][$key]["id"])) {
            ServicioEvento::find($this->serviciosListDia[$this->dia][$key]["id"])->delete();
        }

        //Lo quito de la lista de servicios
        unset($this->serviciosListDia[$this->dia][$key]);


        $this->getTotalPrice();
    }


    //Calcula el precio total teniendo en cuenta el precio de la lista de los servicios y aplica un descuento si es necesario
    public function getTotalPrice()
    {
        $total = 0;
        if ($this->adelanto < 0 || $this->adelanto === null || $this->adelanto === "") {
            $this->adelanto = 0;
        }
        foreach ($this->serviciosListDia as $dia => $servicioEventoList) {
            foreach ($servicioEventoList as $servicio) {
                $total += $servicio["importe"];
            }
            if ($this->addDiscount) {
                $descTotal = $total * (abs($this->descuento) / 100);
                $this->precioFinal = $total - $descTotal;
                $this->entregaDiscount = $this->precioFinal * ($this->adelanto / 100);
            } else {
                $this->precioFinal = $total;
                $this->precioBase = $total;
                $this->entrega = $total *  ($this->adelanto / 100);
            }
        }
    }

    //alterna entre activar y descativar el descuento total
    public function allowDisabDisc()
    {
        if ($this->presupuesto->descuento > 0 && $this->addDiscount) {
            $this->descuento = 0;
            $this->addDiscount = !$this->addDiscount;
        } else {
            $this->addDiscount = !$this->addDiscount;
        }

        $this->getTotalPrice();
    }

    //Trigger para crear cliente
    public function crearCliente()
    {
        $this->addCliente = true;
    }

    public function checkClient()
    {
        $this->clienteIsSave = true;
    }

    public function uncheckClient()
    {

        $this->clienteIsSave = false;
        $this->setupClient($this->id_cliente);
    }

    public function uncheckEvent()
    {
        $this->eventoIsSave = false;
        $this->setupEvento($this->id_evento);
    }

    public function checkEvent()
    {
        $this->eventoIsSave = true;
    }

    //Trigger para añadir observaciones
    public function allowObs()
    {
        $this->addObservaciones = true;
    }

    //Pasar la lista de servicios a una lista de modelos de servicio NO TESTED YET
    public function submitServicioList()
    {
        $servicios = [];
        $programas = [];
        $i = 0;
        foreach ($this->serviciosListDia as $servicioEventoList) {
            // dd($servicioEventoList);
            foreach ($servicioEventoList as $index => $servicio) {


                // $programas[$i + $index] = $servicio["programas"];
                $exist = isset($servicio["id"]);
                $programas = $servicio["programas"];

                unset($servicio["programas"]);
                unset($servicio["horaFin"]);
                $exist = isset($servicio["id"]);
                if ($exist) {
                    ServicioEvento::find($servicio["id"])->update($servicio);
                    $serv = ServicioEvento::find($servicio["id"])->toArray();
                    // dd($serv);
                } else {
                    $serv = ServicioEvento::create($servicio)->toArray();
                }

                if ($serv) {
                    foreach ($programas as $programa) {
                        $programa["id_servicioEvento"] = $serv["id"];
                        if (array_key_exists("id", $programa)) {
                            Programa::find($programa["id"])->update($programa);
                        } else {
                            Programa::create($programa);
                        }
                    }
                    $servicios[$index] = $serv;
                    // dd($programa);
                    $programa[$index]["id_servicioEvento"] = $serv["id"];
                    // dd($programa);
                    $programas[$i + $index] = $programa;
                }
            }
            $i++;
        }
        // dd($programas);
        $this->servicio = $servicios;
        $this->programas = $programas;
        return $servicios;
    }

    //
    public function submitProgramas($servicios)
    {
        $programasConf = [];
        foreach ($this->programas as $index => $programas) {
            foreach ($programas as $i => $programa) {
                // unset($programa["montador"]);
                // dd($this->programas);
                // $programa["id_servicioEvento"] = $servicios[$index]["id"];

                if (array_key_exists("id", $programa)) {
                    $prg = Programa::find($programa["id"])->update($programa);
                } else {
                    $prg = Programa::create($programa);
                }

                if ($prg) {
                    $programasConf[$index] = $prg;
                }
            }
        }
        return $programasConf;
    }
    // Al hacer submit en el formulario
    public function update()
    {
        $tieneAcce=false;
        $accesorionecesatio = false;
        foreach ($this->listaServicios as $servicio) {
            $accesorio = Articulos::find($servicio['articulo_seleccionado']);
            if( isset($accesorio)){
                $accesorionecesatio = $accesorio->accesorio;
            }
            if ($accesorionecesatio) {
                foreach ($this->listaServicios as $servicio1) {
                    if ($servicio1['id'] == 112){
                        $tieneAcce=true;
                        break;
                    }
                }
                if($tieneAcce) {

                }else{
                    $this->alert('error', '¡Necesita introducir el accesorio!', [
                        'position' => 'center',
                        'toast' => false,]);
                        return;
                }

            }
        }
        $this->precioBase = $this->precioFinal;
        $this->precioFinal = $this->precioBase - $this->descuento;
        $this->id_evento = $this->evento->id;
        $diaEvento = Carbon::parse($this->diaEvento);
        $diaFin = Carbon::parse($this->diaFinal);

        if ($diaEvento > $diaFin) {
            $this->diaFinal = $this->diaEvento;
        }

        $validatedData = $this->validate(
            [
                'fechaEmision' => 'required',
                'fechaVencimiento' => 'nullable',
                'id_evento' => 'required',
                'estado' => 'required',
                'id_cliente' => 'required',
                'precioBase' => 'required',
                'precioFinal' => 'required',
                'descuento' => 'nullable',
                'adelanto' => 'nullable',
                'observaciones' => 'nullable',
                'categoria_evento_id' => 'nullable',
                'diaEvento' => 'required',
                'diaFinal' => "required",
                'iva_id' => 'nullable',
                'iva_valor' => 'nullable',
                'factura_propia' => 'nullable',
                'cliente_vip' => 'nullable',

            ],
            // Mensajes de error
            [
                'fechaEmision.required' => 'La fecha de emision es obligatoria.',
                'id_evento.required' => 'La fecha de emision es obligatoria.',
                'id_cliente.required' => 'El alumno es obligatorio.',
                'precioBase.required' => 'El curso es obligatorio.',
                'precioFinal.required' => 'Los detalles son obligatorios',
                'categoria_evento_id' => 'nullable',
                'estado.required' => 'El estado es obligatorio',
            ]
        );

        // Guardar datos validados
        $presupuesosSave = $this->presupuesto->update($validatedData);
        event(new \App\Events\LogEvent(Auth::user(), 4, $this->presupuesto->id));

        foreach ($this->listaServiciosEliminar as $servicio) {
            $this->presupuesto->servicios()->detach($servicio['id']);
        }

        foreach ($this->listaPacksEliminar as $pack) {
            $this->presupuesto->packs()->detach($pack['id']);
        }

        foreach ($this->listaServicios as $servicio) {
            if ($servicio['existente'] == 0) {
                $this->presupuesto->servicios()->attach(
                    $servicio['id'],
                    [
                        'numero_monitores' => $servicio['numero_monitores'],
                        'precio_final' => $servicio['precioFinal'],
                        'tiempo' => $servicio['tiempo'],
                        'tiempo_montaje' => $servicio['tiempo_montaje'],
                        'tiempo_desmontaje' => $servicio['tiempo_desmontaje'],
                        'hora_inicio' => $servicio['hora_inicio'],
                        'hora_finalizacion' => $servicio['hora_finalizacion'],
                        'hora_montaje' => $servicio['hora_montaje'],
                        'sueldo_monitores' => json_encode($servicio['sueldo_monitores']),
                        'id_monitores' => json_encode($servicio['id_monitores']),
                        'gasto_gasoil' => json_encode($servicio['gasto_gasoil']),
                        'pago_pendiente' => json_encode($servicio['pago_pendiente']),
                        'articulo_seleccionado' => $servicio['articulo_seleccionado'],
                        'num_art_indef' => $servicio['num_art_indef'],
                        'concepto'=> $servicio['concepto'],
                        'visible'=> $servicio['visible'],
                    ]

                );
            } else {
                ServicioPresupuesto::where('id', $servicio['id_pivot'])->update([
                    'articulo_seleccionado' => $servicio['articulo_seleccionado'],
                    'sueldo_monitores' => json_encode($servicio['sueldo_monitores']),
                    'id_monitores' => json_encode($servicio['id_monitores']),
                    'gasto_gasoil' => json_encode($servicio['gasto_gasoil']),
                    'pago_pendiente' => json_encode($servicio['pago_pendiente']),

                ]);
            }
        }
        foreach ($this->listaPacks as $pack) {
            if ($pack['existente'] == 0) {
                $this->presupuesto->packs()->attach($pack['id'], [
                    'numero_monitores' => json_encode($pack['numero_monitores']),
                    'precio_final' => $pack['precioFinal'],
                    'tiempos' => json_encode($pack['tiempos']),
                    'horas_inicio' => json_encode($pack['horas_inicio']),
                    'horas_finalizacion' => json_encode($pack['horas_finalizacion']),
                    'tiempos_montaje' => json_encode($pack['tiempos_montaje']),
                    'tiempos_desmontaje' => json_encode($pack['tiempos_desmontaje']),
                    'horas_montaje' => json_encode($pack['horas_montaje']),
                    'sueldos_monitores' => json_encode($pack['sueldos_monitores']),
                    'id_monitores' => json_encode($pack['id_monitores']),
                    'gastos_gasoil' => json_encode($pack['gastos_gasoil']),
                    'pagos_pendientes' => json_encode($pack['pagos_pendientes']),
                    'articulos_seleccionados' => json_encode($pack['articulos_seleccionados']),
                ]);
            }else{
                PackPresupuesto::where('id', $pack['id_pivot'])->update([
                    'articulos_seleccionados' => json_encode($pack['articulos_seleccionados']),
                    'sueldos_monitores' => json_encode($pack['sueldos_monitores']),
                    'id_monitores' => json_encode($pack['id_monitores']),
                    'gastos_gasoil' => json_encode($pack['gastos_gasoil']),
                    'pagos_pendientes' => json_encode($pack['pagos_pendientes']),
                ]);
            }
        }

        // Alertas de guardado exitoso
        //continuar aqui
        if ($presupuesosSave) {
            $this->alert('success', '¡Presupuesto actualizado correctamente!', [
                'position' => 'center',
                'toast' => false,
                'showConfirmButton' => true,
                'onConfirmed' => 'confirmed',
                'confirmButtonText' => 'ok',
                'timerProgressBar' => true,
            ]);
        } else {
            $this->alert('error', '¡No se ha podido actualizar la información del presupuesto!', [
                'position' => 'center',
                'toast' => false,
            ]);
        }
    }



    public function destroy()
    {

        foreach ($this->listaServicios as $servicio) {
            $this->presupuesto->servicios()->detach($servicio['id']);
        }

        foreach ($this->listaPacks as $pack) {
            $this->presupuesto->packs()->detach($pack['id']);
        }
        $contrato = Contrato::firstWhere('id_presupuesto', $this->presupuesto->id);

        event(new \App\Events\LogEvent(Auth::user(), 7, $this->presupuesto->id));
        event(new \App\Events\LogEvent(Auth::user(), 13, $this->evento->id));
        if (isset($contrato)){event(new \App\Events\LogEvent(Auth::user(), 16, $contrato->id));}
        $this->presupuesto->evento->delete();
        if (isset($contrato)){ $contrato->delete();}


        // Guardar datos validados
        $presupuesosSave = $this->presupuesto->delete();



        // Alertas de guardado exitoso
        if ($presupuesosSave) {
            return redirect()->route('presupuestos.index');
        } else {
            $this->alert('error', '¡No se ha podido actualizar la información del presupuesto!', [
                'position' => 'center',
                'toast' => false,
            ]);
        }
    }

    public function facturarPresupuesto()
    {
        session()->flash('presupuesto', $this->identificador);
        return redirect()->route('facturas.create');
    }
    public function aceptarPresupuesto()
    {
        $presupuesosSave = $this->presupuesto->update(['estado' => 'Aceptado']);

        // Alertas de guardado exitoso
        if ($presupuesosSave) {
            $this->alert('success', '¡Presupuesto aceptado correctamente!', [
                'position' => 'center',
                'toast' => false,
                'showConfirmButton' => true,
                'onConfirmed' => 'confirmed',
                'confirmButtonText' => 'ok',
                'timerProgressBar' => true,
            ]);
        } else {
            $this->alert('error', '¡No se ha podido aceptar el presupuesto!', [
                'position' => 'center',
                'toast' => false,
            ]);
        }
    }

    public function cancelarPresupuesto()
    {
        $presupuesosSave = $this->presupuesto->update(['estado' => 'Cancelado']);


        // Alertas de guardado exitoso
        if ($presupuesosSave) {
            $this->alert('success', '¡Presupuesto cancelado correctamente!', [
                'position' => 'center',
                'toast' => false,
                'showConfirmButton' => true,
                'onConfirmed' => 'confirmed',
                'confirmButtonText' => 'ok',
                'timerProgressBar' => true,
            ]);
        } else {
            $this->alert('error', '¡No se ha podido cancelar el presupuesto!', [
                'position' => 'center',
                'toast' => false,
            ]);
        }
    }

    // Función para cuando se llama a la alerta
    public function getListeners()
    {
        return [
            'confirmed',
            'destroy',
            'aceptarPresupuesto',
            'cancelarPresupuesto',
            'facturarPresupuesto',
            'imprimirPresupuesto',
            'updatePresupuestoValidacion',
            'updateEvento',
            'confirmedImprimir',
            'confirmed2'
        ];
    }

    public function sumarTiempos($id)
    {
        $totalMinutos = 0;

        foreach ($this->listaPacks[$id]['tiempos'] as $tiempo) {
            $partes = explode(':', $tiempo);
            $horas = $partes[0];
            $minutos = $partes[1];
            $totalMinutos += ($horas * 60) + $minutos;
        }

        $horasResultado = floor($totalMinutos / 60);
        $minutosResultado = $totalMinutos % 60;

        return sprintf('%02d:%02d', $horasResultado, $minutosResultado);
    }
    public function cambioPrecioPack()
    {
        $pack = $this->packs->where('id', $this->pack_seleccionado)->first()->servicios();
        $this->precioFinalPack = 0;
        foreach ($pack as $keyPack => $servicio) {
            if (isset($this->preciosMonitores[$keyPack])) {
                $this->preciosBasePack[$keyPack] = ($servicio->precioBase + ($this->preciosMonitores[$keyPack] * $servicio->precioMonitor));
            } else {
                $this->preciosMonitores[$keyPack] = $servicio->minMonitor;
                $this->preciosBasePack[$keyPack] = $servicio->precioBase;
                $this->preciosMonitores[$keyPack] = $servicio->minMonitor;
                try {
                    $this->tiemposMontajePack[$keyPack] = Carbon::createFromFormat('H:i:s', $servicio->tiempoMontaje)->format('H:i');
                } catch (\Exception $e) {
                    // Si falla, intenta con el formato 'H:i'
                    try {
                        $this->tiemposMontajePack[$keyPack] = Carbon::createFromFormat('H:i', $servicio->tiempoMontaje)->format('H:i');
                    } catch (\Exception $e) {
                    }
                }

                try {
                    $this->tiemposDesmontajePack[$keyPack] = Carbon::createFromFormat('H:i:s', $servicio->tiempoDesmontaje)->format('H:i');
                } catch (\Exception $e) {
                    // Si falla, intenta con el formato 'H:i'
                    try {
                        $this->tiemposDesmontajePack[$keyPack] = Carbon::createFromFormat('H:i', $servicio->tiempoDesmontaje)->format('H:i');
                    } catch (\Exception $e) {
                    }
                }

                try {
                    $this->tiemposPack[$keyPack] = Carbon::createFromFormat('H:i:s', $servicio->tiempoServicio)->format('H:i');
                } catch (\Exception $e) {
                    // Si falla, intenta con el formato 'H:i'
                    try {
                        $this->tiemposPack[$keyPack] = Carbon::createFromFormat('H:i', $servicio->tiempoServicio)->format('H:i');
                    } catch (\Exception $e) {
                    }
                }
            }
            $this->precioFinalPack += ($servicio->precioBase + ($this->preciosMonitores[$keyPack] * $servicio->precioMonitor));
        }
    }

    public function cambioTiempoPack()
    {
        if ($this->pack_seleccionado != 0) {
            $pack = Servicio::whereJsonContains('id_pack', $this->pack_seleccionado)->get();;
            foreach ($pack as $keyPack => $servicio) {
                if ($this->indicador_montaje == 1) {
                    if (isset($this->tiemposMontajePack[$keyPack]) && isset($this->horasMontajePack[$keyPack])) {
                        $inicio = Carbon::createFromFormat('H:i', $this->horasMontajePack[$keyPack]);
                        $fin = Carbon::createFromFormat('H:i', $this->tiemposMontajePack[$keyPack]);
                        list($horas, $minutos) = explode(':', $fin->format('H:i'));
                        $this->horasInicioPack[$keyPack] = $inicio->addHours($horas)->addMinutes($minutos)->format('H:i');
                        $this->emit('refresh');
                    }
                    if (isset($this->tiemposPack[$keyPack]) && isset($this->horasInicioPack[$keyPack])) {
                        if (isset($this->tiemposDesmontajePack[$keyPack])) {
                            $inicio = Carbon::createFromFormat('H:i', $this->horasInicioPack[$keyPack]);
                            $medio = Carbon::createFromFormat('H:i', $this->tiemposDesmontajePack[$keyPack]);
                            $fin = Carbon::createFromFormat('H:i', $this->tiemposPack[$keyPack]);
                            list($horas, $minutos) = explode(':', $medio->format('H:i'));
                            list($horas2, $minutos2) = explode(':', $fin->format('H:i'));
                            $this->horasFinalizacionPack[$keyPack] = $inicio->addMinutes((int)$minutos)->addHours((int)$horas)->addMinutes((int)$minutos2)->addHours((int)$horas2)->format('H:i');
                            $this->emit('refresh');
                        } else {
                            $inicio = Carbon::createFromFormat('H:i', $this->horasInicioPack[$keyPack]);
                            $fin = Carbon::createFromFormat('H:i', $this->tiemposPack[$keyPack]);
                            list($horas, $minutos) = explode(':', $fin->format('H:i'));
                            $this->horasFinalizacionPack[$keyPack] = $inicio->addMinutes((int)$minutos)->addHours((int)$horas)->format('H:i');
                            $this->emit('refresh');
                        }
                    }
                } else {
                    if (isset($this->tiemposPack[$keyPack]) && isset($this->horasInicioPack[$keyPack])) {
                        $inicio = Carbon::createFromFormat('H:i', $this->horasInicioPack[$keyPack]);
                        $fin = Carbon::createFromFormat('H:i', $this->tiemposPack[$keyPack]);
                        list($horas, $minutos) = explode(':', $fin->format('H:i'));
                        $this->horasFinalizacionPack[$keyPack] = $inicio->addMinutes((int)$minutos)->addHours((int)$horas)->format('H:i');
                        $this->emit('refresh');
                    }
                }
            }
        } else {
            $this->alert('error', 'Selecciona un pack.');
            $this->numero_monitores = 0;
            $this->tiempo = 0;
            $this->hora_inicio = 0;
            $this->hora_finalizacion = 0;
            $this->precioFinalServicio = 0;
        }
    }

    public function updatedDiaEvento($value)
    {

        if (!is_null($value) && !is_null($this->diaFinal)) {
            $this->realizarAccion();
        }
    }

    public function updatedDiaFinal($value)
    {
        if (!is_null($value) && !is_null($this->diaEvento)) {
            $this->realizarAccion();
        }
    }

    public function realizarAccion()
    {
        $this->validate([
            'diaEvento' => 'required|date',
            'diaFinal' => 'required|date|after_or_equal:diaEvento'
        ],[
            'diaFinal.after' => 'El dia final del evento no puede se anterio al inicio'
        ]);

        $articulosEnUso = DB::table('presupuestos')
        ->join('servicio_presupuesto', 'presupuestos.id', '=', 'servicio_presupuesto.presupuesto_id')
        ->where(function($query) {
            $query->where('presupuestos.diaEvento', '<=', $this->diaFinal)
                  ->where('presupuestos.diaFinal', '>=', $this->diaEvento);
        })
        ->pluck('servicio_presupuesto.articulo_seleccionado');

        $this->articulos = Articulos::whereNotIn('id', $articulosEnUso)->orWhere('stock',1)->get();
    }

    public function cambioPrecioServicio()
    {
        if ($this->servicio_seleccionado != 0) {
            $servicio = $this->servicios->where('id', $this->servicio_seleccionado)->first();
            if (($this->precioFinalServicio == 0 || $this->precioFinalServicio == $servicio->minMonitor || $this->precioFinalServicio == ($servicio->precioBase + (($this->numero_monitores - 1) * $servicio->precioMonitor))) ||
                ($this->precioFinalServicio == ($servicio->precioBase + (($this->numero_monitores + 1) * $servicio->precioMonitor)))
            ) {
                $this->precioFinalServicio = ($servicio->precioBase + ($this->numero_monitores * $servicio->precioMonitor));
            }
            if ($this->numero_monitores < $servicio->minMonitor) {
                $this->numero_monitores = $servicio->minMonitor;
            }
            try {
                $this->tiempoMontaje = Carbon::createFromFormat('H:i:s', $servicio->tiempoMontaje)->format('H:i');
            } catch (\Exception $e) {
                // Si falla, intenta con el formato 'H:i'
                try {
                    $this->tiempoMontaje = Carbon::createFromFormat('H:i', $servicio->tiempoMontaje)->format('H:i');
                } catch (\Exception $e) {
                }
            }

            try {
                $this->tiempoDesmontaje = Carbon::createFromFormat('H:i:s',  $servicio->tiempoDesmontaje)->format('H:i');
            } catch (\Exception $e) {
                // Si falla, intenta con el formato 'H:i'
                try {
                    $this->tiempoDesmontaje = Carbon::createFromFormat('H:i', $servicio->tiempoDesmontaje)->format('H:i');
                } catch (\Exception $e) {
                }
            }

            try {
                $this->tiempo = Carbon::createFromFormat('H:i:s',  $servicio->tiempoServicio)->format('H:i');
            } catch (\Exception $e) {
                // Si falla, intenta con el formato 'H:i'
                try {
                    $this->tiempo = Carbon::createFromFormat('H:i', $servicio->tiempoServicio)->format('H:i');
                } catch (\Exception $e) {
                }
            }
            $this->concepto = $servicio->nombre;
        } else {
            $this->alert('error', 'Selecciona un servicio.');
            $this->numero_monitores = 0;
            $this->tiempo = 0;
            $this->hora_inicio = 0;
            $this->hora_finalizacion = 0;
            $this->precioFinalServicio = 0;
        }
    }

    public function cambioTiempoServicio()
    {
        if ($this->servicio_seleccionado != 0) {
            if (isset($this->tiempoMontaje) && isset($this->horaMontaje) && ($this->tiempoMontaje != 0) && ($this->horaMontaje != 0)) {
                $inicio = Carbon::createFromFormat('H:i', $this->horaMontaje);
                $fin = Carbon::createFromFormat('H:i', $this->tiempoMontaje);
                list($horas, $minutos) = explode(':', $fin->format('H:i'));
                $this->hora_inicio = $inicio->addHours($horas)->addMinutes($minutos)->format('H:i');
                $this->emit('refresh');
            }
            if (isset($this->tiempo) && isset($this->hora_inicio) && ($this->tiempo != 0) && ($this->hora_inicio != 0)) {
                if (isset($this->tiempoDesmontaje) && ($this->tiempoDesmontaje != 0)) {
                    $inicio = Carbon::createFromFormat('H:i', $this->hora_inicio);
                    $medio = Carbon::createFromFormat('H:i', $this->tiempoDesmontaje);
                    $fin = Carbon::createFromFormat('H:i', $this->tiempo);
                    list($horas, $minutos) = explode(':', $medio->format('H:i'));
                    list($horas2, $minutos2) = explode(':', $fin->format('H:i'));
                    $this->hora_finalizacion = $inicio->addMinutes((int)$minutos)->addHours((int)$horas)->addMinutes((int)$minutos2)->addHours((int)$horas2)->format('H:i');
                    $this->emit('refresh');
                } else {
                    $inicio = Carbon::createFromFormat('H:i', $this->hora_inicio);
                    $fin = Carbon::createFromFormat('H:i', $this->tiempo);
                    list($horas, $minutos) = explode(':', $fin->format('H:i'));
                    $this->hora_finalizacion = $inicio->addMinutes((int)$minutos)->addHours((int)$horas)->format('H:i');
                    $this->emit('refresh');
                }
            }
        } else {
            $this->alert('error', 'Selecciona un servicio.', [
                'position' => 'center',
                'toast' => false,
                'showConfirmButton' => true,
                'confirmButtonText' => 'ok',
                'timerProgressBar' => true,
            ]);
            $this->numero_monitores = 0;
            $this->tiempo = 0;
            $this->hora_inicio = 0;
            $this->hora_finalizacion = 0;
            $this->precioFinalServicio = 0;
        }
    }

    public function addPack()
    {
        if ($this->pack_seleccionado != 0) {
            $packId = $this->pack_seleccionado;


            $fechaEvento = $this->diaEvento; // Fecha en la que deseas verificar el stock
            // Variable para rastrear si el stock se supera
            $stockSeSupera = false;
            // Obtener los artículos relacionados con el servicio
            foreach ($this->packs->where('id', $packId)->first()->servicios() as $servicioIndex => $servicio) {
                if (isset($this->articulos_seleccionados[$servicioIndex])) {
                    $servicioId = $servicio->id;
                    $articulo = $this->articulos->where('id', $this->articulos_seleccionados[$servicioIndex])->first();

                    $fechaEvento = $this->diaEvento; // Fecha en la que deseas verificar el stock
                    // Obtener los artículos relacionados con el servicio

                    // Variable para rastrear si el stock se supera
                    $stockSeSupera = false;
                    // Iterar a través de los artículos del servicio y verificar el stock para cada uno

                    // Obtener la cantidad total utilizada de este artículo en la fecha indicada
                    if($articulo->stock == 0){
                        $sumaStockUsado = DB::table('presupuestos')
                        ->join('servicio_presupuesto', 'presupuestos.id', '=', 'servicio_presupuesto.presupuesto_id')
                        ->where(function($query) {
                                $query->where('presupuestos.diaEvento', '<=', $this->diaFinal)
                                      ->where('presupuestos.diaFinal', '>=', $this->diaEvento);
                            })                            ->where('servicio_presupuesto.articulo_seleccionado', $articulo->id)
                            ->count();

                        $sumadepacksusados = DB::table('presupuestos')
                            ->join('pack_presupuesto', 'presupuestos.id', '=', 'pack_presupuesto.presupuesto_id')
                            ->where(function($query) {
                                $query->where('presupuestos.diaEvento', '<=', $this->diaFinal)
                                      ->where('presupuestos.diaFinal', '>=', $this->diaEvento);
                            })                            ->whereRaw('JSON_CONTAINS(pack_presupuesto.articulos_seleccionados, \'["' . $articulo->id . '"]\')')
                            ->count();

                        $sumaTotalStockUsado = $sumadepacksusados + $sumaStockUsado;

                        if ($sumaTotalStockUsado < 1){
                            $sumaTotalStockUsadoGeneral = DB::table('presupuestos')
                            ->join('servicio_presupuesto', 'presupuestos.id', '=', 'servicio_presupuesto.presupuesto_id')
                            ->where(function($query) {
                                $query->where('presupuestos.diaEvento', '<=', $this->diaFinal)
                                      ->where('presupuestos.diaFinal', '>=', $this->diaEvento);
                            })
                            ->where('servicio_presupuesto.servicio_id', $servicioId)
                            ->selectRaw('SUM(CASE
                                WHEN servicio_presupuesto.articulo_seleccionado != 0 THEN 1
                                ELSE servicio_presupuesto.num_art_indef
                                END) AS total_stock_usado')
                            ->value('total_stock_usado');

                            $sumadepacks = DB::table('presupuestos')
                            ->join('pack_presupuesto', 'presupuestos.id', '=', 'pack_presupuesto.presupuesto_id')
                            ->join('servicios', 'pack_presupuesto.pack_id', '=', 'servicios.id_pack')
                            ->where(function($query) {
                                $query->where('presupuestos.diaEvento', '<=', $this->diaFinal)
                                      ->where('presupuestos.diaFinal', '>=', $this->diaEvento);
                            })
                            ->where('servicios.id', $servicioId)
                            ->count();


                            // Obtener el stock total fijo del servicio
                            $stockTotal = Articulos::where('id_categoria', $servicioId)->count();

                            // Obtener la cantidad de stock usado por el servicio que deseas agregar


                            $nuevaCantidadTotalgeneral = $sumaTotalStockUsadoGeneral + 1 + $sumadepacks;
                            if ($stockTotal < $nuevaCantidadTotalgeneral) {
                                $stockSeSupera = true;
                            }
                        }else{
                            $stockSeSupera = true;
                        }
                    }
                }
                if($stockSeSupera){ break;}
            }

            if($stockSeSupera == true) {
                $this->alert('error', 'Todo el stock de un artículo dado de este servicio está en uso en esta fecha.');
            } else {
                $numMonitores = $this->preciosMonitores;

                // Preparar arrays basados en numero_monitores
                $precioHora = Settings::first()->precio_gasoil_km;
                $time = explode(':',$this->tiempo);
                $horas = ($time[0] + $time[1] / 60)+((isset($time[2]) && $time[2] != 0) ?  $time[2] / 3600 : 0);
                $sueldo = $precioHora * $horas;
                $arraySueldo = array_fill(0, $this->numero_monitores, $sueldo);
                $defaultArray = array_fill(0, $this->numero_monitores, '0');                $defaultTimeArray = array_fill(0, count($numMonitores), '00:00');
                $defaultDoubleArray = array_map(function () use ($defaultArray) {
                    return $defaultArray;
                }, $numMonitores);
                $sueldoDoubleArray = array_map(function () use ($arraySueldo) {
                    return $arraySueldo;
                }, $numMonitores);

                $this->listaPacks[] = [
                    'id' => $this->pack_seleccionado,
                    'numero_monitores' => $this->preciosMonitores,
                    'precioFinal' => $this->precioFinalPack ?? '0',
                    'tiempos' => !empty($this->tiemposPack) ? $this->tiemposPack : $defaultTimeArray,
                    'horas_inicio' => !empty($this->horasInicioPack) ? $this->horasInicioPack : $defaultTimeArray,
                    'horas_finalizacion' => !empty($this->horasFinalizacionPack) ? $this->horasFinalizacionPack : $defaultTimeArray,
                    'tiempos_montaje' => !empty($this->tiemposMontajePack) ? $this->tiemposMontajePack : $defaultTimeArray,
                    'tiempos_desmontaje' => !empty($this->tiemposDesmontajePack) ? $this->tiemposDesmontajePack : $defaultTimeArray,
                    'horas_montaje' => !empty($this->horasMontajePack) ? $this->horasMontajePack : $defaultTimeArray,
                    'id_monitores' => !empty($this->idMonitoresPack) ? $this->idMonitoresPack : $defaultDoubleArray,
                    'sueldos_monitores' => !empty($this->sueldoMonitoresPack) ? $this->sueldoMonitoresPack : $sueldoDoubleArray,
                    'gastos_gasoil' => !empty($this->gastosGasoilPack) ? $this->gastosGasoilPack : $defaultDoubleArray,
                    'checks_gasoil' => !empty($this->gastosGasoilPack) ? $this->gastosGasoilPack : $defaultDoubleArray,
                    'pagos_pendientes' => !empty($this->sueldoMonitoresPack) ? $this->sueldoMonitoresPack : $defaultDoubleArray,
                    'articulos_seleccionados' => !empty($this->articulos_seleccionados) ? $this->articulos_seleccionados : $defaultDoubleArray,
                    'existente'  => 0,

                ];
                $this->pack_seleccionado = 0;
                $this->preciosMonitores = [];
                $this->tiemposPack = [];
                $this->horasInicioPack = [];
                $this->horasFinalizacionPack = [];
                $this->tiemposMontajePack = [];
                $this->tiemposDesmontajePack = [];
                $this->horasMontajePack = [];
                $this->articulos_seleccionados = [];
                $this->precioFinal += $this->precioFinalPack;
                $this->precioFinalPack = 0;
            }
        } else {
            $this->alert('error', '¡Selecciona un pack de servicios!', [
                'position' => 'center',
                'toast' => false,
                'showConfirmButton' => true,
                'confirmButtonText' => 'ok',
                'timerProgressBar' => true,
            ]);
        }
    }

    public function addServicio()
    {
        if ($this->servicio_seleccionado != 0) {
            $servicioId = $this->servicio_seleccionado;
            if ($this->articulo_seleccionado != 0) {
                $articuloId = $this->articulo_seleccionado;

                $fechaEvento = $this->diaEvento; // Fecha en la que deseas verificar el stock
                // Obtener los artículos relacionados con el servicio

                // Variable para rastrear si el stock se supera
                $stockSeSupera = false;
                // Iterar a través de los artículos del servicio y verificar el stock para cada uno
                $articulo = DB::table('articulos')->where('id', $articuloId)->first();
                // Obtener la cantidad total utilizada de este artículo en la fecha indicada
                if($articulo->stock == 0){
                    $sumaStockUsado = DB::table('presupuestos')
                    ->join('servicio_presupuesto', 'presupuestos.id', '=', 'servicio_presupuesto.presupuesto_id')
                    ->where(function($query) {
                        $query->where('presupuestos.diaEvento', '<=', $this->diaFinal)
                              ->where('presupuestos.diaFinal', '>=', $this->diaEvento);
                    })
                    ->where('servicio_presupuesto.articulo_seleccionado', $articulo->id)
                    ->count();

                    $sumadepacksusados = DB::table('presupuestos')
                        ->join('pack_presupuesto', 'presupuestos.id', '=', 'pack_presupuesto.presupuesto_id')
                        ->where(function($query) {
                            $query->where('presupuestos.diaEvento', '<=', $this->diaFinal)
                                  ->where('presupuestos.diaFinal', '>=', $this->diaEvento);
                        })
                        ->whereRaw('JSON_CONTAINS(pack_presupuesto.articulos_seleccionados, \'["' . $articuloId . '"]\')')
                        ->count();

                    $sumaTotalStockUsado = $sumadepacksusados + $sumaStockUsado;

                    if ($sumaTotalStockUsado < 1){

                        $sumaTotalStockUsadoGeneral = DB::table('presupuestos')
                        ->join('servicio_presupuesto', 'presupuestos.id', '=', 'servicio_presupuesto.presupuesto_id')
                        ->where(function($query) {
                            $query->where('presupuestos.diaEvento', '<=', $this->diaFinal)
                                  ->where('presupuestos.diaFinal', '>=', $this->diaEvento);
                        })
                        ->where('servicio_presupuesto.servicio_id', $servicioId)
                        ->selectRaw('SUM(CASE
                            WHEN servicio_presupuesto.articulo_seleccionado != 0 THEN 1
                            ELSE servicio_presupuesto.num_art_indef
                            END) AS total_stock_usado')
                        ->value('total_stock_usado');
                        $sumadepacks = DB::table('presupuestos')
                            ->join('pack_presupuesto', 'presupuestos.id', '=', 'pack_presupuesto.presupuesto_id')
                            ->join('servicios', 'pack_presupuesto.pack_id', '=', 'servicios.id_pack')
                            ->where(function($query) {
                                $query->where('presupuestos.diaEvento', '<=', $this->diaFinal)
                                      ->where('presupuestos.diaFinal', '>=', $this->diaEvento);
                            })
                            ->where('servicios.id', $servicioId)
                            ->count();


                        // Obtener el stock total fijo del servicio
                        $stockTotal = Articulos::where('id_categoria', $servicioId)->count();

                        // Obtener la cantidad de stock usado por el servicio que deseas agregar

                        $nuevaCantidadTotalgeneral = $sumaTotalStockUsadoGeneral + 1 + $sumadepacks;

                        if ($stockTotal < $nuevaCantidadTotalgeneral) {
                            $stockSeSupera = true;
                        }
                    }else{
                        $stockSeSupera = true;
                    }
                }


                if($stockSeSupera == true) {
                    $this->alert('error', 'Todo el stock de un artículo dado de este servicio está en uso en esta fecha.');
                } else {
                    for ($i = 0; $i > $this->numero_monitores; $i++) {
                        $this->sueldoMonitores[] = $this->servicios->firstWhere('id', $this->servicio_seleccionado)->get('precioMonitor');
                    }
                    $precioHora = Settings::first()->precio_gasoil_km;
                    $time = explode(':',$this->tiempo);
                    $horas = ($time[0] + $time[1] / 60)+((isset($time[2]) && $time[2] != 0) ?  $time[2] / 3600 : 0);
                    $sueldo = $precioHora * $horas;
                    $arraySueldo = array_fill(0, $this->numero_monitores, $sueldo);
                    $defaultArray = array_fill(0, $this->numero_monitores, '0');

                    $this->listaServicios[] = [
                        'id' => $this->servicio_seleccionado,
                        'numero_monitores' => $this->numero_monitores,
                        'precioFinal' => $this->precioFinalServicio ?? '0',
                        'tiempo' => $this->tiempo ?? '00:00',
                        'hora_inicio' => $this->hora_inicio ?? '00:00',
                        'hora_finalizacion' => $this->hora_finalizacion ?? '00:00',
                        'hora_montaje' => $this->horaMontaje ?? '00:00',
                        'tiempo_montaje' => $this->tiempoMontaje ?? '00:00',
                        'tiempo_desmontaje' => $this->tiempoDesmontaje ?? '00:00',
                        'articulo_seleccionado' => $this->articulo_seleccionado ?? '0',
                        'sueldo_monitores' => !empty($this->sueldoMonitores) ? $this->sueldoMonitores : $arraySueldo,
                        'id_monitores' => !empty($this->idMonitores) ? $this->idMonitores : $defaultArray,
                        'gasto_gasoil' => !empty($this->gastosGasoil) ? $this->gastosGasoil : $defaultArray,
                        'check_gasoil' => !empty($this->gastosGasoil) ? $this->gastosGasoil : $defaultArray,
                        'pago_pendiente' => !empty($this->sueldoMonitores) ? $this->sueldoMonitores : $defaultArray,
                        'num_art_indef' => $this->num_arti,
                        'concepto'=> $this->concepto,
                        'visible'=> $this->visible,
                        'existente'  => 0,
                    ];
                    $this->servicio_seleccionado = 0;
                    $this->numero_monitores = 0;
                    $this->tiempo = 0;
                    $this->tiempoMontaje = 0;
                    $this->tiempoDesmontaje = 0;
                    $this->hora_inicio = 0;
                    $this->hora_finalizacion = 0;
                    $this->horaMontaje = 0;
                    $this->precioFinal += $this->precioFinalServicio;
                    $this->precioFinalServicio = 0;
                    $this->articulo_seleccionado = 0;
                    $this->concepto;
                    $this->visible = 1;
                    $this->num_arti = 0;
                }
            }else{
                $this->alert('error', '¡Selecciona un articulo!', [
                    'position' => 'center',
                    'toast' => false,
                    'showConfirmButton' => true,
                    'confirmButtonText' => 'ok',
                    'timerProgressBar' => true,
                ]);
            }
        } else {
            $this->alert('error', '¡Selecciona un servicio!', [
                'position' => 'center',
                'toast' => false,
                'showConfirmButton' => true,
                'confirmButtonText' => 'ok',
                'timerProgressBar' => true,
            ]);
        }
    }

    public function updatedArticuloSeleccionado($value)
    {
        $articulo=Articulos::find($value);
        if(isset( $articulo->name)){
            $this->concepto = $articulo->name;
        }
    }

    public function addServicioSinArticulo()
    {
        if ($this->servicio_seleccionado != 0) {
            $servicioId = $this->servicio_seleccionado;

            $fechaEvento = $this->diaEvento; // Fecha en la que deseas verificar el stock

            // Variable para rastrear si el stock se supera
            $stockSeSupera = false;
            // Iterar a través de los artículos del servicio y verificar el stock para cada uno

            // Obtener la cantidad total utilizada de este artículo en la fecha indicada
            $sumaTotalStockUsado = DB::table('presupuestos')
            ->join('servicio_presupuesto', 'presupuestos.id', '=', 'servicio_presupuesto.presupuesto_id')
            ->where(function($query) {
                                $query->where('presupuestos.diaEvento', '<=', $this->diaFinal)
                                      ->where('presupuestos.diaFinal', '>=', $this->diaEvento);
                            })            ->where('servicio_presupuesto.servicio_id', $servicioId)
            ->selectRaw('SUM(CASE
                WHEN servicio_presupuesto.articulo_seleccionado != 0 THEN 1
                ELSE servicio_presupuesto.num_art_indef
                END) AS total_stock_usado')
            ->value('total_stock_usado');

            $sumadepacks = DB::table('presupuestos')
            ->join('pack_presupuesto', 'presupuestos.id', '=', 'pack_presupuesto.presupuesto_id')
            ->join('servicios', 'pack_presupuesto.pack_id', '=', 'servicios.id_pack')
            ->where(function($query) {
                                $query->where('presupuestos.diaEvento', '<=', $this->diaFinal)
                                      ->where('presupuestos.diaFinal', '>=', $this->diaEvento);
                            })            ->where('servicios.id', $servicioId)
            ->count();

            // Obtener el stock total fijo del artículo
            $stockTotal = Articulos::where('id_categoria', $servicioId)->count();

            // Obtener la cantidad de stock usado por el servicio que deseas agregar
            $cantidadStockUsadoNuevoServicio = $this->num_arti ;
            // Calcular la cantidad total que se usaría si se agrega el nuevo servicio
            $nuevaCantidadTotal = $sumaTotalStockUsado + $cantidadStockUsadoNuevoServicio + $sumadepacks;
            if ($nuevaCantidadTotal > $stockTotal) {
                $stockSeSupera = true;
            }

            if($stockSeSupera == true) {
                $this->alert('error', 'Todo el stock de un artículo dado de este servicio está en uso en esta fecha.');
            } else {
                for ($i = 0; $i > $this->numero_monitores; $i++) {
                    $this->sueldoMonitores[] = $this->servicios->firstWhere('id', $this->servicio_seleccionado)->get('precioMonitor');
                }
                $precioHora = Settings::first()->precio_gasoil_km;
                $time = explode(':',$this->tiempo);
                $horas = ($time[0] + $time[1] / 60)+((isset($time[2]) && $time[2] != 0) ?  $time[2] / 3600 : 0);
                $sueldo = $precioHora * $horas;
                $arraySueldo = array_fill(0, $this->numero_monitores, $sueldo);
                $defaultArray = array_fill(0, $this->numero_monitores, '0');


                $this->listaServicios[] = [
                    'id' => $this->servicio_seleccionado,
                    'numero_monitores' => $this->numero_monitores,
                    'precioFinal' => $this->precioFinalServicio ?? '0',
                    'tiempo' => $this->tiempo ?? '00:00',
                    'hora_inicio' => $this->hora_inicio ?? '00:00',
                    'hora_finalizacion' => $this->hora_finalizacion ?? '00:00',
                    'hora_montaje' => $this->horaMontaje ?? '00:00',
                    'tiempo_montaje' => $this->tiempoMontaje ?? '00:00',
                    'tiempo_desmontaje' => $this->tiempoDesmontaje ?? '00:00',
                    'sueldo_monitores' => !empty($this->sueldoMonitores) ? $this->sueldoMonitores : $arraySueldo,
                    'id_monitores' => !empty($this->idMonitores) ? $this->idMonitores : $defaultArray,
                    'gasto_gasoil' => !empty($this->gastosGasoil) ? $this->gastosGasoil : $defaultArray,
                    'check_gasoil' => !empty($this->gastosGasoil) ? $this->gastosGasoil : $defaultArray,
                    'pago_pendiente' => !empty($this->sueldoMonitores) ? $this->sueldoMonitores : $defaultArray,
                    'articulo_seleccionado' => $this->articulo_seleccionado ?? '0',
                    'num_art_indef' => $this->num_arti,
                    'concepto'=> $this->concepto,
                    'visible'=> $this->visible,
                    'existente'  => 0,
                ];
                $this->servicio_seleccionado = 0;
                $this->numero_monitores = 0;
                $this->tiempo = 0;
                $this->tiempoMontaje = 0;
                $this->tiempoDesmontaje = 0;
                $this->hora_inicio = 0;
                $this->hora_finalizacion = 0;
                $this->horaMontaje = 0;
                $this->precioFinal += $this->precioFinalServicio;
                $this->precioFinalServicio = 0;
                $this->articulo_seleccionado = 0;
                $this->concepto;
                $this->visible = 1;
                $this->num_arti = 0;
            }
        } else {
            $this->alert('error', '¡Selecciona un servicio!', [
                'position' => 'center',
                'toast' => false,
                'showConfirmButton' => true,
                'confirmButtonText' => 'ok',
                'timerProgressBar' => true,
            ]);
        }
    }


    public function deletePack($indice)
    {
        if ($this->listaPacks[$indice]['existente'] == 1) {
            $this->listaPacksEliminar[] = $this->listaPacks[$indice];
        }
        $this->precioFinal -= $this->listaPacks[$indice]['precioFinal'];
        unset($this->listaPacks[$indice]);
        $this->listaPacks = array_values($this->listaPacks);
    }

    public function deleteServicio($indice)
    {
        if ($this->listaServicios[$indice]['existente'] == 1) {
            $this->listaServiciosEliminar[] = $this->listaServicios[$indice];
        }
        $this->precioFinal -= $this->listaServicios[$indice]['precioFinal'];
        unset($this->listaServicios[$indice]);
        $this->listaServicios = array_values($this->listaServicios);
    }
    public function asignarValorInicial($keyPack, $value)
    {
        $this->preciosMonitores[$keyPack] = $value;
        $this->cambioPrecioPack();
    }
    // Función para cuando se llama a la alerta
    public function confirmed()
    {
        // Do something
        return redirect()->route('presupuestos.index');
    }

    public function confirmed2()
    {
        return redirect()->route('contratos.edit', $this->contrato_id);
    }

    public function alertaGuardar()
    {
        $this->alert('warning', 'Asegúrese de que todos los datos son correctos antes de guardar.', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'onConfirmed' => 'updatePresupuestoValidacion',
            'confirmButtonText' => 'Sí',
            'showDenyButton' => true,
            'denyButtonText' => 'No',
            'timerProgressBar' => true,
            'timer' => null
        ]);
    }

    public function alertaAceptar()
    {
        $this->alert('info', '¿Seguro que desea imprimir el presupuesto?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'onConfirmed' => 'imprimirPresupuesto',
            'confirmButtonText' => 'Sí',
            'showDenyButton' => true,
            'denyButtonText' => 'No',
            'timerProgressBar' => true,
            'timer' => null
        ]);
    }

    public function alertaCancelar()
    {
        $this->alert('info', '¿Seguro que desea imprimir el contrato?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'onConfirmed' => 'confirmedImprimir',
            'confirmButtonText' => 'Sí',
            'showDenyButton' => true,
            'denyButtonText' => 'No',
            'timerProgressBar' => true,
            'timer' => null
        ]);
    }

    public function alertaCancelar2()
    {
        $this->alert('info', 'Comprueba que has guardado todo antes de visitar la página del contrato.', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'onConfirmed' => 'confirmed2',
            'confirmButtonText' => 'Sí',
            'showDenyButton' => true,
            'denyButtonText' => 'No',
            'timerProgressBar' => true,
            'timer' => null
        ]);
    }

    public function alertaImprimir()
    {
        $this->alert('info', '¿Desea descargar el presupuesto en PDF?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'onConfirmed' => 'imprimirPresupuesto',
            'confirmButtonText' => 'Sí',
            'showDenyButton' => true,
            'denyButtonText' => 'No',
            'timerProgressBar' => true,
        ]);
    }

    public function alertaFacturar()
    {
        $this->alert('error', '¿Seguro que desea facturar el presupuesto? No se puede revertir este proceso.', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'onConfirmed' => 'confirmed',
            'confirmButtonText' => 'Sí',
            'showDenyButton' => true,
            'denyButtonText' => 'No',
            'timerProgressBar' => true,
        ]);
    }

    public function alertaEliminar()
    {
        $this->alert('error', '¿Seguro que desea eliminar el presupuesto? No se puede revertir este proceso.', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'onConfirmed' => 'destroy',
            'confirmButtonText' => 'Sí',
            'showDenyButton' => true,
            'denyButtonText' => 'No',
            'timerProgressBar' => true,
            'timer' => null
        ]);
    }

    public function imprimirPresupuesto()
    {
        $presupuesto = Presupuesto::find($this->identificador);
        $cliente = Cliente::where('id', $presupuesto->id_cliente)->first();
        $evento = Evento::where('id', $presupuesto->id_evento)->first();
        $listaServicios = [];
        $listaPacks = [];
        $packs = ServicioPack::all();

        foreach ($presupuesto->servicios()->get() as $servicio) {
            $listaServicios[] = [
                'id' => $servicio->id,
                'numero_monitores' => $servicio->pivot->numero_monitores,
                'precioFinal' => $servicio->pivot->precio_final,
                'tiempo' => $servicio->pivot->tiempo,
                'hora_inicio' => $servicio->pivot->hora_inicio,
                'hora_finalizacion' => $servicio->pivot->hora_finalizacion,
                'existente' => 1,
                'concepto' => $servicio->pivot->concepto,
                'visible' => $servicio->pivot->visible ];
        }

        foreach ($presupuesto->packs()->get() as $pack) {
            $listaPacks[] = ['id' => $pack->id, 'numero_monitores' => json_decode($pack->pivot->numero_monitores, true), 'precioFinal' => $pack->pivot->precio_final, 'existente' => 1];
        }

        $nombreEvento = TipoEvento::find($evento->eventoNombre);

        $datos =  [
            'presupuesto' => $presupuesto, 'cliente' => $cliente, 'id_presupuesto' => $presupuesto->id, 'fechaEmision' => $this->fechaEmision, 'fechaVencimiento' => $this->fechaVencimiento,
            'evento' => $evento, 'listaServicios' => $listaServicios, 'listaPacks' => $listaPacks, 'packs' => $packs, 'observaciones' => '','nombreEvento' => $nombreEvento->nombre, 'servicios' => Servicio::all(),
        ];

        $pdf = Pdf::loadView('livewire.presupuestos.contract-component', $datos)->setPaper('a4', 'vertical')->output(); //
        return response()->streamDownload(
            fn () => print($pdf),
            'export_protocol.pdf'
        );
    }

    public function guardarContrato()
    {
        $validatedData = $this->validate(
            [
                "id_presupuesto" => "required",
                'metodoPago' => 'required',
                'cuentaTransferencia' => 'required',
                'observaciones' => 'nullable',
                'authImagen' => 'required',
                'authMenores' => 'required',
                'dia' => 'required'
            ],
            // Mensajes de error
            [
                'metodoPago.required' => 'La contraseña es obligatoria.',
                'cuentaTransferencia.required' => 'La cuenta es obligatoria',
            ]
        );




        // Guardar datos validados
        $contratoSave = Contrato::create($validatedData);

        event(new \App\Events\LogEvent(Auth::user(), 14, $contratoSave->id));


        // Alertas de guardado exitoso
        if ($contratoSave) {
            if ($this->imprimir == 1) {
                $this->alert('success', '¡Contrato registrado correctamente!', [
                    'position' => 'center',
                    // 'timer' => 1500,
                    'toast' => false,
                    'showConfirmButton' => true,
                    'onConfirmed' => 'confirmedImprimir',
                    'confirmButtonText' => 'Imprimir contrato',
                    // 'timerProgressBar' => true,
                ]);
            } else {
                $this->alert('success', '¡Contrato registrado correctamente!', [
                    'position' => 'center',
                    // 'timer' => 1500,
                    'toast' => false,
                    'showConfirmButton' => true,
                    'onConfirmed' => 'confirmed',
                    'confirmButtonText' => 'ok',
                    // 'timerProgressBar' => true,
                ]);
            }
        } else {
            $this->alert('error', '¡No se ha podido guardar la información del contrato!', [
                'position' => 'center',
                'timer' => 3000,
                'toast' => false,
            ]);
        }
    }

    public function confirmedImprimir()
    {
        $this->diaMostrar = Carbon::now()->locale('es_ES')->isoFormat('D [de] MMMM [de] Y');
        $presupuesto = Presupuesto::find($this->identificador);
        $cliente = Cliente::where('id', $presupuesto->id_cliente)->first();
        $evento = Evento::where('id', $presupuesto->id_evento)->first();
        $nombreEvento = TipoEvento::find($evento->eventoNombre);
        $packs = ServicioPack::all();
        foreach ($presupuesto->servicios()->get() as $servicio) {
            $listaServicios[] = ['id' => $servicio->id, 'numero_monitores' => $servicio->pivot->numero_monitores, 'precio_final' => $servicio->pivot->precio_final, 'tiempo' => $servicio->pivot->tiempo, 'hora_inicio' => $servicio->pivot->hora_inicio, 'hora_finalizacion' => $servicio->pivot->hora_finalizacion, 'existente' => 1, 'concepto' => $servicio->pivot->concepto, 'visible' => $servicio->pivot->visible ];
        }

        foreach ($presupuesto->packs()->get() as $pack) {
            $listaPacks[] = ['id' => $pack->id, 'numero_monitores' => json_decode($pack->pivot->numero_monitores, true), 'precio_final' => $pack->pivot->precio_final, 'existente' => 1];
        }

        $filename = Carbon::now()->format('Y-m-d_H-i-s') . '.pdf';
        $ruta = '/contratos/' . $filename;



        if (count($presupuesto->packs) > 0 && count($presupuesto->servicios) > 0) {
            $datos =  [
                'presupuesto' => $presupuesto, 'cliente' => $cliente, 'metodoPago' => $this->metodoPago, 'servicios' => Servicio::all(),
                'evento' => $evento, 'listaServicios' => $listaServicios, 'listaPacks' => $listaPacks, 'packs' => $packs, 'observaciones' => '', 'gestor' => User::firstWhere('id', $this->gestor_id),
                'nContrato' => $this->nPresupuesto, 'fechaContrato' => $this->fechaEmision, 'authImagen' => $this->authImagen, 'authMenores' => $this->authMenores, 'fechaMostrar' => $this->diaMostrar,
                'nombreEvento' =>$nombreEvento,
            ];
        } else if (count($presupuesto->packs) > 0 && count($presupuesto->servicios) <= 0) {
            $datos =  [
                'presupuesto' => $presupuesto, 'cliente' => $cliente, 'metodoPago' => $this->metodoPago, 'servicios' => Servicio::all(),
                'evento' => $evento, 'listaPacks' => $listaPacks, 'packs' => $packs, 'observaciones' => '', 'gestor' => User::firstWhere('id', $this->gestor_id),
                'nContrato' => $this->nPresupuesto, 'fechaContrato' => $this->fechaEmision, 'authImagen' => $this->authImagen, 'authMenores' => $this->authMenores, 'fechaMostrar' => $this->diaMostrar,
                'nombreEvento' =>$nombreEvento,
            ];
        } else if (count($presupuesto->packs) <= 0 && count($presupuesto->servicios) > 0) {
            $datos =  [
                'presupuesto' => $presupuesto, 'cliente' => $cliente, 'metodoPago' => $this->metodoPago, 'servicios' => Servicio::all(),
                'evento' => $evento, 'listaServicios' => $listaServicios, 'packs' => $packs, 'observaciones' => '', 'gestor' => User::firstWhere('id', $this->gestor_id),
                'nContrato' => $this->nPresupuesto, 'fechaContrato' => $this->fechaEmision, 'authImagen' => $this->authImagen, 'authMenores' => $this->authMenores, 'fechaMostrar' => $this->diaMostrar,
                'nombreEvento' =>$nombreEvento,
            ];
        } else {
            $datos =  [
                'presupuesto' => $presupuesto, 'cliente' => $cliente, 'metodoPago' => $this->metodoPago, 'servicios' => Servicio::all(),
                'evento' => $evento, 'packs' => $packs, 'observaciones' => '', 'gestor' => User::firstWhere('id', $this->gestor_id),
                'nContrato' => $this->nPresupuesto, 'fechaContrato' => $this->fechaEmision, 'authImagen' => $this->authImagen, 'authMenores' => $this->authMenores, 'fechaMostrar' => $this->diaMostrar,
                'nombreEvento' =>$nombreEvento,
            ];
        }


        $path = public_path('contratos');

        if (!File::exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        $pdf = Pdf::loadView('livewire.contratos.contract-component', $datos)->setPaper('a4', 'vertical')->save(public_path() . $ruta)->output(); //

        if ($this->contrato_id === null) {
            // Guardar datos validados
            $contratoSave = Contrato::create([
                "id_presupuesto" => $this->identificador,
                'metodoPago' => $this->metodoPago,
                'cuentaTransferencia' => $this->cuentaTransferencia,
                'observaciones' => $this->observaciones,
                'authImagen' => $this->authImagen,
                'authMenores' => $this->authMenores,
                'dia' => $this->diaEvento,
            ]);
            $this->contrato_id = $contratoSave->id;

            event(new \App\Events\LogEvent(Auth::user(), 14, $contratoSave->id));
            $this->confirmed2();
        } else {
            $contrato = Contrato::find($this->contrato_id);
            $contrato->update([
                "id_presupuesto" => $this->identificador,
                'metodoPago' => $this->metodoPago,
                'cuentaTransferencia' => $this->cuentaTransferencia,
                'observaciones' => $this->observaciones,
                'authImagen' => $this->authImagen,
                'authMenores' => $this->authMenores,
                'dia' => $this->diaEvento,
            ]);
            $this->confirmed2();
        }

        return response()->streamDownload(
            fn () => print($pdf),
            $filename
        );
    }

    public function generarFactura()
    {
        $facturaexistente = Facturas::where('id_presupuesto', $this->presupuesto->id)->first();
        if(isset($facturaexistente)){
            return redirect()->route('facturas.edit', $facturaexistente->id);
        }
        $now = Carbon::now();
        $ultimaFactura = Facturas::whereMonth('created_at', $now->month)->whereYear('created_at', $now->year)->orderBy('numero_factura', 'desc')->first();
        //Formato de numero de factura 2025-01-000001
        if(isset($ultimaFactura)){
            $partes = explode('-', $ultimaFactura->numero_factura);
            $numeroFactura =  $now->year . '-' . $now->month . '-' . sprintf('%06d', $partes[2]+ 1);
        }else{
            $numeroFactura =  $now->year . '-' . $now->month . '-' . sprintf('%06d', 1);
        }

        $data = [
            'numero_factura' => $numeroFactura,
            'id_presupuesto' => $this->presupuesto->id,
            'fecha_emision' => Carbon::parse($this->fechaEmision)->format('d/m/Y'),
            'fecha_vencimiento' => Carbon::parse($this->fechaVencimiento)->format('d/m/Y'),
            'descripcion' => $this->observaciones,
            'estado' => 'Pendiente',
            'metodo_pago' => $this->metodoPago,
        ];

        $factura = Facturas::create($data);

        return redirect()->route('facturas.index');

    }
}
