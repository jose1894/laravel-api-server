<?php

namespace App\Http\Controllers;

use App\Http\Requests\TipoPolizaRequest;
use App\Models\TipoPoliza;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;

class TipoPolizaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tipoPolizas=Cache::remember('cachetipopolizas',15/60,function() {
			return TipoPoliza::simplePaginate(10);  // Paginamos cada 10 elementos.
		});
		
        return response()->json([
            'status'=>'ok', 
            'siguiente'=>$tipoPolizas->nextPageUrl(),
            'anterior'=>$tipoPolizas->previousPageUrl(),
            'data'=>$tipoPolizas->items()
        ],200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TipoPolizaRequest $request)
    {
		// Insertamos los datos recibidos en la tabla.
		$tipoPoliza = TipoPoliza::create($request->all());

		// Devolvemos la respuesta Http 201 (Created) + los datos del nuevo fabricante + una cabecera de Location + cabecera JSON
		return response()->json([
            'data'=>$tipoPoliza
        ],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tipoPoliza = TipoPoliza::findOrFail($id);

		// Devolvemos la información encontrada.
		return response()->json(['status'=>'ok','data'=>$tipoPoliza],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TipoPolizaRequest $request, $id)
    {

		$tipoPoliza = TipoPoliza::findOrFail($id);
        $tipoPoliza->update($request->all());
        $tipoPoliza->save();
		return response()->json(['status'=>'ok','data'=>$tipoPoliza],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tipoPoliza = TipoPoliza::where('id', $id)->withTrashed()->first();

        if ($tipoPoliza->trashed()){
            $tipoPoliza->restore();
            return response()->json(['status' => 'ok', 'data' => $tipoPoliza, 'restored' => 'ok']);
        }else{
            $tipoPoliza->delete();
            return response()->json(['status' => 'ok', 'data' => $tipoPoliza, 'deleted' => 'ok']);
        }
    }
}
