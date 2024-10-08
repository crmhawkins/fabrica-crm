<?php

namespace App\Models;

use App\Models\Servicio;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServicioPack extends Model
{
    use HasFactory;


    protected $table = "servicio_packs";

    protected $fillable = [
        'nombre',
    ];

    public function servicios()
    {
        return Servicio::whereJsonContains('id_pack', strval($this->id))->get();
    }

    public function presupuestos()
    {
        return $this->belongsToMany(Presupuesto::class, 'servicio_presupuesto', 'pack_id', 'presupuesto_id')->withPivot('numero_monitores', 'precioFinal', 'tiempos', 'tiempos_montaje', 'tiempos_desmontaje', 'horas_montaje','horas_inicio', 'horas_finalizacion', 'id_monitores', 'sueldos_monitores', 'gastos_gasoil', 'pagos_pendientes');
    }

    /**
     * Mutaciones de fecha.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at', 'deleted_at',
    ];
}
