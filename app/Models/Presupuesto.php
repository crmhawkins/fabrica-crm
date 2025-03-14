<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Presupuesto extends Model
{
    use HasFactory;

    protected $table = "presupuestos";

    protected $fillable = [
        'id_evento',
        'id_cliente',
        'precioFinal',
        'precioBase',
        'descuento',
        'adelanto',
        'fechaEmision',
        'fechaVencimiento',
        "nPresupuesto",
        'seguros_sociales',
        'observaciones',
        'pago_pendiente',
        'materiales',
        "estado",
        'categoria_evento_id',
        'gasoilDistancia',
        'diaEvento',
        'diaFinal',
        'gestor_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'iva_valor',
        'iva_id',
        'factura_propia',
        'cliente_vip',

    ];

    // public function programas()
    // {
    //     return $this->hasMany('app\Models\Programa');
    // }

    public function contrato(): HasOne
    {
        return $this->hasOne(Contrato::class, "id_presupuesto", "id");
    }
    /**
     * Mutaciones de fecha.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at', 'deleted_at',
    ];

    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'servicio_presupuesto', 'presupuesto_id', 'servicio_id')
        ->withPivot('numero_monitores', 'precio_final', 'tiempo', 'tiempo_montaje', 'tiempo_desmontaje', 'hora_montaje', 'hora_inicio', 'hora_finalizacion', 'id_monitores', 'sueldo_monitores', 'gasto_gasoil', 'pago_pendiente', 'articulo_seleccionado','num_art_indef','visible','concepto');
    }

    public function packs()
    {
        return $this->belongsToMany(ServicioPack::class, 'pack_presupuesto', 'presupuesto_id', 'pack_id')
        ->withPivot('numero_monitores', 'precio_final', 'tiempos', 'tiempos_montaje', 'tiempos_desmontaje', 'horas_montaje','horas_inicio', 'horas_finalizacion', 'id_monitores', 'sueldos_monitores', 'gastos_gasoil', 'pagos_pendientes', 'articulos_seleccionados');
    }
    public function evento()
    {
        return $this->belongsTo(Evento::class, 'id_evento');
    }
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }
    public function gestor()
    {
        return $this->belongsTo(User::class, 'gestor_id');
    }

    public function categoria()
    {
        return $this->belongsTo(CategoriaEvento::class, 'categoria_evento_id');
    }



    public function serviciosPresupuesto()
    {
        return $this->hasMany(ServicioPresupuesto::class, 'presupuesto_id');
    }

}
