<?php

// Devuelve el id del usuario autenticado
function userID(){
    return auth()->user()->id;
}

// Devolver numero en formato moneda
function money($number){
    return 'Bs.'.number_format($number,0,',','.');
}

// Convertir numeros a letras
function numeroLetras($number){
    return App\Models\NumerosEnLetras::convertir($number,'Bolivianos',false,'Centavos');
}

// Devuelve el id del usuario autenticado
function isAdmin(){
    return auth()->user()->admin;
}
