<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Presupuesto;

class Cliente extends Model
{
    use HasFactory;


    protected $table = "cliente";

    protected $fillable = [
        "trato",
        "nombre",
        "apellido",
        "tipoCalle",
        "calle",
        "numero",
        "direccionAdicional3",
        "direccionAdicional1",
        "direccionAdicional2",
        "codigoPostal",
        "ciudad",
        "provincia",
        "nif",
        "tlf3",
        "tlf1",
        "tlf2",
        "email1",
        "email2",
        "email3",
        "confPostal",
        "confEmail",
        "confSms",
        'tipo_cliente',
        'codigo_organo_gestor',
        'codigo_unidad_tramitadora',
        'codigo_oficina_contable'
    ];

    public function eventos(){
        return $this->hasMany("app\Models\Evento");
    }

    public function presupuestos(){
        return $this->hasMany(Presupuesto::class,'id_cliente');
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
