<?php

namespace App\Http\Livewire\Caja;

use App\Models\Cliente;
use App\Models\Presupuesto;
use App\Models\TipoEvento;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use App\Models\Caja;
use App\Models\Evento;
use App\Models\Monitor;
use App\Models\ServicioPresupuesto;
use Illuminate\Support\Facades\Auth;
use Nette\Utils\Json;

class CreateGastoComponent extends Component
{
    use LivewireAlert;

    public $tipo_movimiento = 'Gasto';
    public $metodo_pago;
    public $importe;
    public $descripcion;
    public $presupuesto_id;
    public $fecha;
    public $clientes;
    public $categorias;
    public $presupuestos;
    public $eventos;
    public $monitores;
    public $monitor_id;
    public $serviciosMonitores;
    public $pagos_seleccionados = [];


    public function mount()
    {
        $this->presupuestos = Presupuesto::all();
        $this->categorias = TipoEvento::all();
        $this->eventos = Evento::all();
        $this->clientes = Cliente::all();
        $this->monitores = Monitor::all();

    }
    public function render()
    {
        return view('livewire.caja.create-gasto-component');
    }
    public function submit()
    {
        // Validación de datos
        $validatedData = $this->validate(
            [
                'tipo_movimiento' => 'required',
                'metodo_pago' => 'required',
                'importe' => 'required',
                'descripcion' => 'required',
                'presupuesto_id' => 'nullable',
                'monitor_id' => 'nullable',
                'fecha' => 'required',

            ],
            // Mensajes de error
            [
                'nombre.required' => 'El nombre es obligatorio.',
            ]
        );


        if (!empty($this->pagos_seleccionados)) {
            $pagos_seleccionados = $this->pagos_seleccionados;
            foreach ($pagos_seleccionados as $pago) {
                $servicioPartes = explode("_", $pago);
                $servicio = ServicioPresupuesto::find($servicioPartes[0]);
                $pagoPendiente = json_decode($servicio->pago_pendiente, true);

                // Asigna 0 al valor correspondiente
                $pagoPendiente[$servicioPartes[1]] = 0;

                // Guarda el array modificado como JSON en el modelo
                $servicio->pago_pendiente = json_encode($pagoPendiente);
                $servicio->save();
            }
        }
        // Guardar datos validados
        $usuariosSave = Caja::create($validatedData);
        event(new \App\Events\LogEvent(Auth::user(), 52, $usuariosSave->id));

        // Alertas de guardado exitoso
        if ($usuariosSave) {
            $this->alert('success', '¡Movimiento registrado correctamente!', [
                'position' => 'center',
                'timer' => 3000,
                'toast' => false,
                'showConfirmButton' => true,
                'onConfirmed' => 'confirmed',
                'confirmButtonText' => 'ok',
                'timerProgressBar' => true,
            ]);
        } else {
            $this->alert('error', '¡No se ha podido guardar la información del movimiento!', [
                'position' => 'center',
                'timer' => 3000,
                'toast' => false,
            ]);
        }
    }

    // Función para cuando se llama a la alerta
    public function getListeners()
    {
        return [
            'confirmed',
            'submit'
        ];
    }

    // Función para cuando se llama a la alerta
    public function confirmed()
    {
        // Do something
        return redirect()->route('caja.index');
    }

    public function getCliente($id)
    {
        return $this->clientes->firstWhere('id', $this->presupuestos->firstWhere('id', $id)->id_cliente)->nombre . " " . $this->clientes->firstWhere('id', $this->presupuestos->firstWhere('id', $id)->id_cliente)->apellido;
    }

    public function updatedMonitorId($value)
    {
        if ($value == null || $value == "") {
            $this->monitor_id = null;
            $this->serviciosMonitores = null;
            return;
        }
       $monitor = Monitor::find($value);
       $monitorId = $monitor->id; // Supongamos que es un número
       $query = ServicioPresupuesto::where(function ($q) use ($monitorId) {
           $q->whereJsonContains('id_monitores', $monitorId) // Busca como número
             ->orWhereJsonContains('id_monitores', (string) $monitorId); // Busca como cadena
       });
       $this->serviciosMonitores = $query->get();

    }

}
