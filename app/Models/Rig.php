<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Rig extends Model {
    protected $fillable = ['numero','nombre','activo'];
    protected $casts = ['activo' => 'boolean'];
}
