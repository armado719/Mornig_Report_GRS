<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Pozo extends Model {
    protected $fillable = ['nombre','operador','municipio','departamento','activo'];
    protected $casts = ['activo' => 'boolean'];
}
