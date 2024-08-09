<?php

namespace App\Http\Livewire\Clientes;

use App\Models\Cliente;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

class EditComponent extends Component
{
    use LivewireAlert;

    public $identificador;

    public $trato;
    public $nombre;
    public $apellido;
    public $tipoCalle;
    public $tipo_cliente;
    public $codigo_organo_gestor;
    public $codigo_unidad_tramitadora;
    public $codigo_oficina_contable;
    public $calle;
    public $numero;
    public $direccionAdicional1;
    public $direccionAdicional2;
    public $direccionAdicional3;
    public $codigoPostal;
    public $ciudad;
    public $provincia;
    public $nif;
    public $tlf1;
    public $tlf2;
    public $tlf3;
    public $email1;
    public $email2;
    public $email3;
    public $confPostal;
    public $confEmail;
    public $confSms;

    public function mount()
    {
        $cliente = Cliente::find($this->identificador);


        $this->trato = $cliente->trato;
        $this->nombre = $cliente->nombre;
        $this->tipoCalle = $cliente->tipoCalle;
        $this->tipo_cliente = $cliente->tipo_cliente;
        if($this->tipo_cliente != 0){
            $this->codigo_organo_gestor = $cliente->codigo_organo_gestor;
            $this->codigo_unidad_tramitadora = $cliente->codigo_unidad_tramitadora;
            $this->codigo_oficina_contable = $cliente->codigo_oficina_contable;
        }else{
            $this->apellido = $cliente->apellido;
        }
        $this->calle = $cliente->calle;
        $this->numero = $cliente->numero;
        $this->direccionAdicional1 = $cliente->direccionAdicional1;
        $this->direccionAdicional2 = $cliente->direccionAdicional2;
        $this->direccionAdicional3 = $cliente->direccionAdicional3;
        $this->codigoPostal = $cliente->codigoPostal;
        $this->ciudad = $cliente->ciudad;
        $this->provincia = $cliente->provincia;
        $this->nif = $cliente->nif;
        $this->tlf1 = $cliente->tlf1;
        $this->tlf2 = $cliente->tlf2;
        $this->tlf3 = $cliente->tlf3;
        $this->email1 = $cliente->email1;
        $this->email2 = $cliente->email2;
        $this->email3 = $cliente->email3;
        $this->confPostal = $cliente->confPostal;
        $this->confEmail = $cliente->confEmail;
        $this->confSms = $cliente->confSms ;
    }


    public function render()
    {
        return view('livewire.clientes.edit-component');
    }


    // Al hacer update en el formulario
    public function update()
    {
        // Validación de datos
        $this->validate([
            'nombre' => 'required',
            'apellido' => 'nullable',
            'tipoCalle' => 'required',
            'tipo_cliente' => 'required',
            'calle' => 'required',
            'numero' => 'required',
            'codigo_organo_gestor' => 'nullable',
            'codigo_unidad_tramitadora' => 'nullable',
            'codigo_oficina_contable' => 'nullable',
            'codigoPostal'=> 'required',
            'ciudad' => 'required',
            'provincia' => 'required',
            'nif' => 'required',
            'tlf1' => 'required',
            'email1' => 'required',
            "confPostal" => 'nullable',
            "confEmail" =>'nullable',
            "confSms" => 'nullable',
        ],
            // Mensajes de error
            [
                'nombre.required' => 'El nombre es obligatorio.',
                'apellido.required' => 'Los protagonistas son obligatorio.',
                'tipoCalle.required' => 'La cantidad de niños es obligatoria.',
                'calle.required' => 'El nombre de usuario es obligatorio.',
                'numero.required' => 'La contraseña es obligatoria.',
                'codigoPostal.required' => 'El lugar es obligatorio.',
                'ciudad.required' => 'La localidad es obligatoria.',
                'provincia.required' => 'La Provincia es obligatoria.',
                'nif.required' => 'El telefono es obligatorio.',
                'tlf1.required' => 'El telefono es obligatorio.',
                'email1.required' => 'El telefono es obligatorio.',

            ]);

        // Encuentra el identificador
        $cliente = Cliente::find($this->identificador);

        // Guardar datos validados
        $clienteSave = $cliente->update([
            'nombre' => $this->nombre,
            'apellido'=>$this->apellido,
            'tipoCalle' => $this->tipoCalle,
            'calle' => $this->calle,
            'numero' => $this->numero,
            'codigo_organo_gestor' => $this->codigo_organo_gestor,
            'codigo_unidad_tramitadora' => $this->codigo_unidad_tramitadora,
            'codigo_oficina_contable' => $this->codigo_oficina_contable,
            'direccionAdicional1' => $this->direccionAdicional1,
            'direccionAdicional2' => $this->direccionAdicional2,
            'direccionAdicional3' => $this->direccionAdicional3,
            'codigoPostal' => $this->codigoPostal,
            'ciudad' => $this->ciudad,
            'tipo_cliente'=> $this->tipo_cliente,
            'provincia' => $this->provincia,
            'nif'=>$this->nif,
            'tlf1'=>$this->tlf1,
            'tlf2'=>$this->tlf2,
            'tlf3'=>$this->tlf3,
            'email1'=>$this->email1,
            'email2'=>$this->email2,
            'email3'=>$this->email3,
            "confPostal"=>$this->confPostal,
            "confEmail"=>$this->confEmail,
            "confSms"=>$this->confSms,
        ]);
        event(new \App\Events\LogEvent(Auth::user(), 9, $cliente->id));

        if ($clienteSave) {
            $this->alert('success', 'Usuario actualizado correctamente!', [
                'position' => 'center',
                'timer' => 3000,
                'toast' => false,
                'showConfirmButton' => true,
                'onConfirmed' => 'confirmed',
                'confirmButtonText' => 'ok',
                'timerProgressBar' => true,
            ]);
        } else {
            $this->alert('error', '¡No se ha podido guardar la información del usuario!', [
                'position' => 'center',
                'timer' => 3000,
                'toast' => false,
            ]);
        }

        session()->flash('message', 'cliente actualizado correctamente.');

        $this->emit('eventUpdated');
    }

      // Eliminación
      public function destroy(){

        $this->alert('warning', '¿Seguro que desea borrar el usuario? No hay vuelta atrás', [
            'position' => 'center',
            'timer' => 3000,
            'toast' => false,
            'showConfirmButton' => true,
            'onConfirmed' => 'confirmDelete',
            'confirmButtonText' => 'Sí',
            'showDenyButton' => true,
            'denyButtonText' => 'No',
            'timerProgressBar' => true,
        ]);

    }

    // Función para cuando se llama a la alerta
    public function getListeners()
    {
        return [
            'confirmed',
            'update',
            'destroy',
            'confirmDelete'
        ];
    }

    // Función para cuando se llama a la alerta
    public function confirmed()
    {
        // Do something
        return redirect()->route('clientes.index');

    }
    // Función para cuando se llama a la alerta
    public function confirmDelete()
    {
        $cliente = Cliente::find($this->identificador);
        event(new \App\Events\LogEvent(Auth::user(), 10, $cliente->id));

        foreach ($cliente->presupuestos as $presupuesto) {
            // Elimina el contrato asociado, si existe
            optional($presupuesto->contrato)->delete();

            // Elimina el evento asociado, si existe
            optional($presupuesto->evento)->delete();

            // Elimina todos los serviciosPresupuesto relacionados
            foreach ($presupuesto->serviciosPresupuesto as $servicioPresupuesto) {
                $servicioPresupuesto->delete();
            }
            // Finalmente, elimina el presupuesto
            $presupuesto->delete();
        }
        $cliente->delete();
        return redirect()->route('clientes.index');

    }
}
