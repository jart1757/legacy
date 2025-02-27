<?php

namespace App\Models;

class Cart
{
    // Agregar producto al carrito
    public static function add(Product $product){
        
        // add the product to cart
        \Cart::session(userID())->add(array(
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->precio_venta,
            'quantity' => 1,
            'attributes' => array(),
            'associatedModel' => $product
        ));

    }

    // Obtener contenido del carrito
    public static function getCart(){
        $cart = \Cart::session(userID())->getContent();
        return $cart->sort();
    }

    //Devolver total
    public static function getTotal(){
        return \Cart::session(userId())->getTotal();
    }

    //Decrementar cantidad
    public static function decrement($id){
       \Cart::session(userId())->update($id,[
        'quantity' => -1
       ]);
    }

    //Incrementar cantidad
    public static function increment($id){
        \Cart::session(userId())->update($id,[
         'quantity' => +1
        ]);
     }

    //Eliminar item
    public static function removeItem($id){
        \Cart::session(userId())->remove($id);
     }

    //Limpiar carrito
    public static function clear(){
        \Cart::session(userId())->clear();
     }

    //Total articulos
    public static function totalArticulos(){
       return \Cart::session(userId())->getTotalQuantity();
     }


}
