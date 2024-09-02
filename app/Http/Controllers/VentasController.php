<?php

namespace App\Http\Controllers;
use App\Models\EggCategory;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class VentasController extends Controller
{
    //
    public function index()
    {

     /*   
     * Muestra la vista principal de ventas.
     *
     * @return \Illuminate\View\View
     */
       // return "hola";
       return view('ventas');
        /*
        $ventas = Sale::with('eggCategory', 'user')
        ->orderBy('created_at', 'desc')
        ->get();
        
        return view('sales-modal-content', compact('sales'));
        */



    }

    public function modalContent()
    {
    
        /**
         * Muestra el contenido del modal.
         *
         * @return \Illuminate\View\View
         */
        return view('modal-content');
    }
}



