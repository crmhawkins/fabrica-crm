<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Articulos extends Model
{
    use HasFactory;

    protected $table = "articulos";

    protected $fillable = [
        "name",
        "stock",
        "id_categoria",
        "accesorio",

    ];

    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'servicio_articulo', 'articulo_id', 'servicio_id')->withPivot('stock_usado');
    }
    public function categoriaServicio()
    {
        return $this->belongsTo(Servicio::class, 'id_categoria');
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
