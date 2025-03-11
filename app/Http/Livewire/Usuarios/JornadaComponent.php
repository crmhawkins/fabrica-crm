<?php

namespace App\Http\Livewire\Usuarios;

use App\Models\Jornada;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;

class JornadaComponent extends Component
{
    // public $search;
    public $usuarios;
    public $fechaInicio;
    public $fechaFin;
    public $arrayUsuarios;

    public function mount()
    {
        $this->fechaInicio = Carbon::parse(now()->startOfMonth()->format('Y-m-d'));
        $this->fechaFin = Carbon::parse(now()->endOfMonth()->format('Y-m-d'));
        $this->usuarios = User::all();

    }

    public function render()
    {
        $this->getjornadas();
        return view('livewire.usuarios.jornada-component');
    }

    public function horasTrabajadasDia($dia, $id){

        $totalWorkedSeconds = 0;
        $jornadas = Jornada::where('user_id', $id)
        ->whereDate('start_time', $dia)
        ->get();

        // Se recorren los almuerzos de hoy
        foreach($jornadas as $jornada){
            $workedSeconds = Carbon::parse($jornada->start_time)->diffInSeconds($jornada->end_time ?? Carbon::now());
            $totalPauseSeconds = $jornada->pauses->sum(function ($pause) {
                return Carbon::parse($pause->start_time)->diffInSeconds($pause->end_time ?? Carbon::now());
            });
            $totalWorkedSeconds += $workedSeconds - $totalPauseSeconds;
        }
        $horasTrabajadasFinal = $totalWorkedSeconds / 60;

        return $horasTrabajadasFinal;
    }
    public function horaInicioJornada($dia, $id){

        $jornada = Jornada::where('user_id', $id)
        ->whereDate('start_time', $dia)
        ->get()->first();
        if(!isset($jornada)){
            return 'N/A';
        }
        $inicio = Carbon::createFromFormat('Y-m-d H:i:s', $jornada->start_time, 'UTC');
        $inicioEspaña = $inicio->setTimezone('Europe/Madrid');

        return $inicioEspaña->format('H:i:s');
    }

    public function getjornadas(){
        $this->arrayUsuarios = [];
        $periodo = Carbon::parse($this->fechaInicio)->daysUntil($this->fechaFin);
        foreach ($this->usuarios as $usuario) {
            $totalHorasTrabajadas = 0;
            $todosLosDias = [];
            foreach ($periodo as $dia) {
                if (!in_array($dia->format('l'), ['Saturday', 'Sunday'])) { // Excluir fines de semana

                        $todosLosDias[$dia->format('Y-m-d')] = $dia->copy();
                }
            }
        $datosUsuario = [
            'id' => $usuario->id,
            'usuario' => $usuario->name . ' ' . $usuario->surname,
            'access_level_id' => $usuario->access_level_id,
            'horas_trabajadas' => [],
        ];
        foreach ($todosLosDias as $fecha => $dia) {
            $horaHorasTrabajadasdia = 0;
            $minutoHorasTrabajadasdia = 0;

            $jornadas = Jornada::where('user_id', $usuario->id)
            ->whereDate('start_time', $dia)
            ->whereNotNull('end_time')
            ->exists(); // Verifica si el usuario inició jornada
            if ($jornadas) {
                $horasTrabajadas = $this->horasTrabajadasDia($dia, $usuario->id);
                $totalHorasTrabajadas += $horasTrabajadas;

                $horaHorasTrabajadasdia = floor($horasTrabajadas / 60);
                $minutoHorasTrabajadasdia = ($horasTrabajadas % 60);

                $horaInicio = $this->horaInicioJornada($dia, $usuario->id);
                $datosUsuario['horas_trabajadas'][$fecha] = "$horaHorasTrabajadasdia h $minutoHorasTrabajadasdia min";
                $datosUsuario['inicio_jornada'][$fecha] = $horaInicio;

            }
        }

        $horaHorasTrabajadas = floor($totalHorasTrabajadas / 60);
        $minutoHorasTrabajadas = ($totalHorasTrabajadas % 60);
        $datosUsuario['total_horas_trabajadas'] = "$horaHorasTrabajadas h $minutoHorasTrabajadas min";
        $this->arrayUsuarios[] = $datosUsuario;
        }
    }


}
