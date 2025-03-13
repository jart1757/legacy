<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function client(){
        return $this->belongsTo(Client::class);
    }

    public function items(){
        return $this->belongsToMany(Item::class)->withPivot(['qty','fecha']);
    }
    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function pedidoImage()
    {
        return $this->morphOne(Image::class, 'imageable')->where('type', 'pedido');
    }

    public function boletaImage()
    {
        return $this->morphOne(Image::class, 'imageable')->where('type', 'boleta');
    }
}



