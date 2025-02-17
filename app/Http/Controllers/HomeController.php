<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\TipoEvento;
use Illuminate\Http\Request;
use App\Models\Evento;
use App\Models\Gastos;
use App\Models\Jornada;
use App\Models\Pause;
use App\Models\Presupuesto;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;



class HomeController extends Controller
{
    // /**
    //  * Create a new controller instance.
    //  *
    //  * @return void
    //  */
    // public function __construct()
    // {
    //     $middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $id = Auth::user()->id;
        $user = User::find($id);

        $timeWorkedToday = $this->calculateTimeWorkedToday($user);
        $jornadaActiva = $user->activeJornada();
        $pausaActiva = null;
        if ($jornadaActiva) {
            $pausaActiva = $jornadaActiva->pausasActiva();
        }
        $presupuestos = Presupuesto::where('estado', 'Aceptado')->orWhere('estado', 'Pendiente')->orderBy('fechaEmision', 'ASC')->get();
        $categorias = TipoEvento::all();

        $inicioSemana = Carbon::now()->startOfWeek();  // Lunes de esta semana
        $finSemana = Carbon::now()->endOfWeek();  // Domingo de esta semana
        $inicioMes = Carbon::now()->startOfMonth()->startOfWeek();  // Lunes de esta semana
        $finMes = Carbon::now()->endOfMonth()->endOfWeek();  // Domingo de esta semana
        $inicioMesPasado = Carbon::now()->startOfMonth()->startOfWeek()->subMonth();  // Lunes de esta semana
        $finMesPasado = Carbon::now()->endofMonth()->endofWeek()->subMonth();  // Domingo de esta semana
        $ingresos_mensuales = (float) ($presupuestos->whereBetween('fechaEmision', [$inicioMes, $finMes])->sum('precioFinal') - $presupuestos->whereBetween('fechaEmision', [$inicioMes, $finMes])->sum('adelanto'));
        $ingresos_mensuales_pasado = (float) ($presupuestos->whereBetween('fechaEmision', [$inicioMesPasado, $finMesPasado])->sum('precioFinal') - $presupuestos->whereBetween('fechaEmision', [$inicioMesPasado, $finMesPasado])->sum('adelanto'));
        $porcentaje_ingresos_mensuales = $ingresos_mensuales_pasado > 0 ? round(($ingresos_mensuales / $ingresos_mensuales_pasado) * 100) : 0;
        $pendiente = (float) ($presupuestos->where('estado', '!=', 'Facturado')->whereBetween('fechaEmision', [$inicioMes, $finMes])->sum('precioFinal') - $presupuestos->where('estado', '!=', 'Facturado')->whereBetween('fechaEmision', [$inicioMes, $finMes])->sum('adelanto'));

        $user = $request->user();
        $eventos = Evento::whereBetween('diaEvento', [$inicioSemana, $finSemana])->orderBy('diaEvento', 'ASC')->get();
        $presupuestosMes = Presupuesto::where('estado', 'Facturado')->whereBetween('fechaEmision', [$inicioMes, $finMes])->get();

        $gastos_caja = Caja::whereBetween('fecha', [$inicioSemana, $finSemana])->where('tipo_movimiento', 'Gasto')->sum('importe');
        $ingresos_caja = Caja::whereBetween('fecha', [$inicioSemana, $finSemana])->where('tipo_movimiento', 'Ingreso')->sum('importe');
        $resultados_caja = $ingresos_caja - $gastos_caja;

        return view('home', compact('timeWorkedToday','jornadaActiva','pausaActiva','user', 'presupuestos', 'categorias', 'porcentaje_ingresos_mensuales', 'eventos',  'ingresos_mensuales', 'ingresos_caja', 'gastos_caja', 'resultados_caja'));
    }

    private function calculateTimeWorkedToday($user)
    {


        $todayJornadas = $user->jornadas()->whereDate('start_time', Carbon::today())->get();

        $totalWorkedSeconds = 0;

        foreach ($todayJornadas as $jornada) {
            $workedSeconds = Carbon::parse($jornada->start_time)->diffInSeconds($jornada->end_time ?? Carbon::now());
            $totalPauseSeconds = $jornada->pauses->sum(function ($pause) {
                return Carbon::parse($pause->start_time)->diffInSeconds($pause->end_time ?? Carbon::now());
            });
            $totalWorkedSeconds += $workedSeconds - $totalPauseSeconds;
        }

        return $totalWorkedSeconds;
    }

    public function timeworked(){
        $user = Auth::user();
        $timeWorkedToday = $this->calculateTimeWorkedToday($user);
        return response()->json(['success' => true ,'time' => $timeWorkedToday]);
    }

    public function startJornada()
    {
        $user = User::find(Auth::user()->id);

        $activeJornada = $user->activeJornada();

        if ($activeJornada) {
            // Si ya hay una jornada activa, retornar un mensaje indicando que no se puede iniciar otra
            return response()->json([
                'success' => false,
                'message' => 'Ya existe una jornada activa.'
            ]);
        }

        $jornada =  Jornada::create([
            'user_id' => $user->id,
            'start_time' => Carbon::now(),
            'is_active' => true,
        ]);

        if($jornada){
            return response()->json(['success' => true]);
        }else{
            return response()->json(['success' => false,'mensaje' => 'Error al iniciar jornada']);
        }
    }

    public function endJornada()
    {
        $user = Auth::user();
        $jornada = Jornada::where('user_id', $user->id)->where('is_active', true)->first();
        if ($jornada) {
            $finJornada = $jornada->update([
                'end_time' => Carbon::now(),
                'is_active' => false,
            ]);
            $pause = Pause::where('jornada_id', $jornada->id)->whereNull('end_time')->first();
            if ($pause){
                $finPause = $pause->update([
                    'end_time' => Carbon::now(),
                    'is_active' => false,
                ]);
            }
            if($finJornada){
                return response()->json(['success' => true]);
            }else{
                return response()->json(['success' => false,'mensaje' => 'Error al iniciar jornada']);
            }
        }else{
            return response()->json(['success' => false,'mensaje' => 'Error al iniciar jornada']);
        }

    }

    public function startPause()
    {

        $user = Auth::user();
        $jornada = Jornada::where('user_id', $user->id)->where('is_active', true)->first();
        if ($jornada) {
            $pause =  Pause::create([
                'jornada_id' =>$jornada->id,
                'start_time' => Carbon::now(),
            ]);

            if($pause){
                return response()->json(['success' => true]);
            }else{
                return response()->json(['success' => false,'mensaje' => 'Error al iniciar jornada']);
            }
        }else{
            return response()->json(['success' => false,'mensaje' => 'Error al iniciar jornada']);
        }
    }

    public function endPause()
    {
        $user = Auth::user();
        $jornada = Jornada::where('user_id', $user->id)->where('is_active', true)->first();
        if ($jornada) {
            $pause = Pause::where('jornada_id', $jornada->id)->whereNull('end_time')->first();
            if ($pause){
                $finPause = $pause->update([
                    'end_time' => Carbon::now(),
                    'is_active' => false,
                ]);

                if($finPause){
                    return response()->json(['success' => true]);
                }else{
                    return response()->json(['success' => false,'mensaje' => 'Error al iniciar jornada']);
                }
            }else{
                return response()->json(['success' => false,'mensaje' => 'Error al iniciar jornada']);
            }
        }else{
            return response()->json(['success' => false,'mensaje' => 'Error al iniciar jornada']);
        }
    }

}
