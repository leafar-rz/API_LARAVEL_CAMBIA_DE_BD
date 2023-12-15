<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class consultationController extends Controller
{
    public function getStore1()
    {
        $tienda = DB::table('tienda')
        ->select('id','nombre', 'domicilio','telefono','rfc')
        ->get();
        return response()->json([
            'TIENDA'=> $tienda
        ],200);
    }

    public function getStore($tienda) {

        if($tienda == 'zamora') {
          $connection = 'zamora';
        } else if($tienda == 'lapiedad') {
          $connection = 'lapiedad';
        }
      
        config(['database.default' => $connection]);
      
        $data = DB::table('tienda')->get();
      
        return response()->json($data);
      
      }
    

    public function getDepartaments()
    {
        $departamentos = DB::table('departamentos')
        ->select('id','nombre')
        ->get();
        return response()->json([
            'DEPARTAMENTOS'=> $departamentos
        ],200);
    }

    public function getCategoriesforDepartament(Request $request)
    {
        $categorias = DB::table('categorias')
        ->select('id','descripcion')
        ->where('DEPARTAMENTOS_id', $request['DEPARTAMENTOS_id'])
        ->get();
        return response()->json([
            'CATEGORIAS'=> $categorias
        ],200);
    }

    public function getArticlesforDepartament(Request $request)
    {
        $articulos = DB::table('articulos')
        ->select('articulos.id','articulos.descripcion','articulos.talla','articulos.color','articulos.precio','articulos.existencias','articulos.img','articulos.CATEGORIAS_id')
        ->join('categorias', 'categorias.id', '=', 'articulos.CATEGORIAS_id')        
        ->join('departamentos', 'departamentos.id', '=', 'categorias.DEPARTAMENTOS_id')
        ->where('departamentos.id', $request['DEPARTAMENTOS_id'])
        ->get();
        return response()->json([
            'ARTICULOS'=> $articulos
        ],200);
    }

    public function getArticlesforCategory(Request $request)
    {
        $articulos = DB::table('articulos')
        ->select('id','descripcion','talla','color','precio','existencias','img','CATEGORIAS_id')
        ->where('CATEGORIAS_id', $request['CATEGORIAS_id'])
        ->get();
        return response()->json([
            'ARTICULOS'=> $articulos
        ],200);
    }

    
}
