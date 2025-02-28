<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    // Definir los atributos que se pueden asignar masivamente
    protected $fillable = ['name', 'slogan', 'telefono', 'email', 'direccion', 'ciudad'];

    // Relación polimórfica con Image
    public function image(){
        return $this->morphOne('App\Models\Image', 'imageable');
    }
}
