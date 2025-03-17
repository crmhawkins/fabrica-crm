<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServicioPresupuesto extends Model
{
    use HasFactory;


    protected $table = "servicio_presupuesto";

    protected $fillable = [
        'servicio_id',
        'presupuesto_id',
        'numero_monitores',
        'precio_final',
        'tiempo',
        'hora_inicio',
        'hora_finalizacion',
        'tiempo_montaje',
        'tiempo_desmontaje',
        'hora_montaje',
        'id_monitores',
        'sueldo_monitores',
        'pago_monitores',
        'gasto_gasoil',
        'pago_pendiente',
        'articulo_seleccionado',
        'articulo_indefinido',
        'num_art_indef'
    ];

    public function servicios() {
       return $this->belongsToMany("App\Models\Servicio", "servicio_id", "id");
    }

    public function presupuestos() {
        return $this->belongsToMany("App\Models\Presupuesto", "presupuesto_id", "id");
     }

    /**
     * Mutaciones de fecha.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at', 'deleted_at',
    ];

    public function monitores()
    {
        return Monitor::whereJsonContains('id', $this->id_monitores);
    }
}
