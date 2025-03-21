<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable= [
        'name','identificacion','telefono','email','empresa','nit','category_id'
    ];



    public function sales(){
        return $this->hasMany(Sale::class);
    }

    
    public function category(){
        return $this->belongsTo(Category::class);
    }

    
}
