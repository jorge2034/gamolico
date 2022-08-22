<?php

namespace App\Http\Controllers;

use App\Models\Licencia;
use App\Models\Seguimiento;
use App\Models\Tramite;
use App\Models\Negocio;
use Illuminate\Http\Request;

class DireccionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function asignar(Request $request){
//        return $request;
        $tramite=Tramite::find($request->tramite_id);
        $tramite->user_id=$request->user_id;
        $tramite->tecnico_id=$request->user_id;
        $tramite->estado="VERIFICADO";
        $tramite->save();

        $seguimiento= new Seguimiento();
        $seguimiento->nombre="Se designo al tecnico ".$request->name;
        $seguimiento->observacion="INICIADO";
        $seguimiento->fecha=date('Y-m-d');
        $seguimiento->hora=date('H:i:s');
        $seguimiento->tramite_id=$request->tramite_id;
        $seguimiento->user_id=$request->user()->id;
        $seguimiento->save();
      

    }
    public function aprobar(Request $request){
        $tramite=Tramite::find($request->tramite_id);
        
        $tramite->user_id=5;
        $tramite->save();

        $seguimiento= new Seguimiento();
        $seguimiento->nombre="El tecnico ".$request->user()->name." Se a aprobado ";
        $seguimiento->observacion="INICIADO";
        $seguimiento->fecha=date('Y-m-d');
        $seguimiento->hora=date('H:i:s');
        $seguimiento->tramite_id=$request->tramite_id;
        $seguimiento->user_id=$request->user()->id;
        $seguimiento->save();

    }
    public function aprobartecnico(Request $request){
        $negocio=Negocio::find($request->negocio['id']);
        $negocio->razon=$request->negocio['razon']!=""?$request->negocio['razon']:'';
        $negocio->descripcionactividad=$request->negocio['descripcionactividad']!=""?$request->negocio['descripcionactividad']:'';
        $negocio->telefono=$request->negocio['telefono']!=""?$request->negocio['telefono']:'';
        $negocio->numpiso=$request->negocio['numpiso']!=""?$request->negocio['numpiso']:'';
        $negocio->mts2=$request->negocio['mts2']!=""?$request->negocio['mts2']:'';
        $negocio->zona=$request->negocio['zona']!=""?$request->negocio['zona']:'';
        $negocio->barrio=$request->negocio['barrio']!=""?$request->negocio['barrio']:'';
        $negocio->calle=$request->negocio['calle']!=""?$request->negocio['calle']:'';
        $negocio->entrecalles=$request->negocio['entrecalles']!=""?$request->negocio['entrecalles']:'';
        $negocio->numeroagua=$request->negocio['numeroagua']!=""?$request->negocio['numeroagua']:'';
        $negocio->numeroelectrico=$request->negocio['numeroelectrico']!=null?$request->negocio['numeroelectrico']:'';
        $negocio->observacion=$request->negocio['observacion']!=""?$request->negocio['observacion']:'';
        $negocio->fachada=$request->negocio['fachada']!=null?$request->negocio['fachada']:false;
        $negocio->acera=$request->negocio['acera']!=null?$request->negocio['acera']:false;
        $negocio->iluminacion=$request->negocio['iluminacion']!=null?$request->negocio['iluminacion']:false;
        $negocio->letrero=$request->negocio['letrero']!=null?$request->negocio['letrero']:false;

        $negocio->datoestablecimiento=$request->negocio['datoestablecimiento']!=""?$request->negocio['datoestablecimiento']:'';
        $negocio->tipo=$request->negocio['tipo']!=""?$request->negocio['tipo']:'';

        $negocio->save();

        $tramite=Tramite::find($request->tramite_id);
        $tramite->tipo=$request->caso['tipo'];
        $tramite->caso_id=$request->caso['id'];
        $tramite->estado='FINALIZADO';
        $tramite->save();

        $seguimiento= new Seguimiento();
        $seguimiento->nombre="El tecnico ".$request->user()->name." Aprobo ";
        $seguimiento->observacion="INICIADO";
        $seguimiento->fecha=date('Y-m-d');
        $seguimiento->hora=date('H:i:s');
        $seguimiento->tramite_id=$request->tramite_id;
        $seguimiento->user_id=$request->user()->id;
        $seguimiento->save();

        $seguimiento= new Seguimiento();
        $seguimiento->nombre="TRAMITE FINALIZADO";
        $seguimiento->observacion="TERMINADO";
        $seguimiento->fecha=date('Y-m-d');
        $seguimiento->hora=date('H:i:s');
        $seguimiento->tramite_id=$tramite->id;
        $seguimiento->user_id=$request->user()->id;
        $seguimiento->save();

        $licencia=new Licencia();
        $licencia->num=$request->licencia['num'];
        $licencia->numlicencia=$request->licencia['numlicencia'];
        $licencia->fecha=$tramite->fecha;
        $licencia->fechaautorizacion=date('Y-m-d');
        $licencia->fechafin=date('Y-m-d', strtotime('+2 year'));
        $licencia->foto='';
        $licencia->hora=date('H:i:s');
        $licencia->fechalimite=$tramite->fechalimite;
        $licencia->tipo=$tramite->tipo;
        $licencia->estado='ACTIVO';
//        $licencia->entramite=$request->entramite;
        $licencia->user_id=$request->user()->id;
        $licencia->contribuyente_id=$negocio->contribuyente_id;
        $licencia->negocio_id=$tramite->negocio_id;
        $licencia->caso_id=$tramite->caso_id;
        $licencia->tramite_id=$tramite->id;
        $licencia->save();

    }
    public function aprobarrevisado(Request $request){
        $tramite=Tramite::find($request->tramite_id);
        $tramite->estado='COMPROBANTE';
        $tramite->save();
        $seguimiento= new Seguimiento();
        $seguimiento->nombre="El Usuario ".$request->user()->name." realizo el comprobante ";
        $seguimiento->observacion="INICIADO";
        $seguimiento->fecha=date('Y-m-d');
        $seguimiento->hora=date('H:i:s');
        $seguimiento->tramite_id=$request->tramite_id;
        $seguimiento->user_id=$request->user()->id;
        $seguimiento->save();

    }
    public function aprobartramite(Request $request){
        $tramite=Tramite::find($request->tramite_id);
        $tramite->estado='APROBADO';
        $tramite->save();
        $seguimiento= new Seguimiento();
        $seguimiento->nombre="El Usuario ".$request->user()->name." aprobo el comprobante ";
        $seguimiento->observacion="INICIADO";
        $seguimiento->fecha=date('Y-m-d');
        $seguimiento->hora=date('H:i:s');
        $seguimiento->tramite_id=$request->tramite_id;
        $seguimiento->user_id=$request->user()->id;
        $seguimiento->save();

    }
    public function aprobarterminar(Request $request){
        $tramite=Tramite::find($request->tramite_id);
        $tramite->estado='TERMINADO';
        $tramite->save();
        $seguimiento= new Seguimiento();
        $seguimiento->nombre="El Usuario ".$request->user()->name." entrego el tramite ";
        $seguimiento->observacion="INICIADO";
        $seguimiento->fecha=date('Y-m-d');
        $seguimiento->hora=date('H:i:s');
        $seguimiento->tramite_id=$request->tramite_id;
        $seguimiento->user_id=$request->user()->id;
        $seguimiento->save();

    }

    public function mistramites(Request $request){
        return Tramite::
        with('user')
            ->with('caso')
            ->with('negocio')
            ->with('requisitos')
            ->with('contribuyente')
            ->with('seguimientos')
            ->with('licencia')
            ->where('estado','REGISTRADO')
            ->get();
    }
    public function mistramitestecnico(Request $request){
        return Tramite::
        with('user')
            ->with('caso')
            ->with('negocio')
            ->with('requisitos')
            ->with('contribuyente')
            ->with('seguimientos')
            ->with('licencia')
            ->where('estado','VERIFICADO')
            ->where('user_id',$request->user()->id)
            ->get();
    }
    public function mistramitesaprobacion(Request $request){
        return Tramite::
        with('user')
            ->with('caso')
            ->with('negocio')
            ->with('requisitos')
            ->with('contribuyente')
            ->with('seguimientos')
            ->with('licencia')
            ->where('estado','COMPROBANTE')
//            ->where('user_id',$request->user()->id)
            ->get();
    }

    public function mistramitesterminar(Request $request){
        return Tramite::
        with('user')
            ->with('caso')
            ->with('negocio')
            ->with('requisitos')
            ->with('contribuyente')
            ->with('seguimientos')
            ->with('licencia')
            ->where('estado','APROBADO')
//            ->where('user_id',$request->user()->id)
            ->get();
    }
    public function mistramitesrevisado(Request $request){
        return Tramite::
        with('user')
            ->with('caso')
            ->with('negocio')
            ->with('requisitos')
            ->with('contribuyente')
            ->with('seguimientos')
            ->with('licencia')
            ->where('estado','REVISADO')
//            ->where('user_id',$request->user()->id)
            ->get();
    }
    public function index()
    {
        $tramite= Tramite::where('estado','DIRECCION TRIBUTARIA')
            ->with('user')
            ->with('caso')
            ->with('requisitos')
            ->with('contribuyente');
//            ->get();
        return Tramite::where('estado','PROCESO')
            ->where('infraestructura',true)
            ->where('seguridad',true)
            ->where('medio',true)
            ->where('salubridad',true)
            ->with('user')
            ->with('caso')
            ->with('requisitos')
            ->with('contribuyente')
            ->union($tramite)
            ->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id,Request $request)
    {
//        return $id;
        if ($id=='i'){
            return Tramite::where('estado','PROCESO')
                ->where('infraestructura',false)
                ->with('user')
                ->with('caso')
                ->with('requisitos')
                ->with('contribuyente')
                ->get();
        }
        if ($id=='s'){
            return Tramite::where('estado','PROCESO')
                ->where('seguridad',false)
                ->with('user')
                ->with('caso')
                ->with('requisitos')
                ->with('contribuyente')
                ->get();
        }
        if ($id=='m'){
            return Tramite::where('estado','PROCESO')
                ->where('medio',false)
                ->with('user')
                ->with('caso')
                ->with('requisitos')
                ->with('contribuyente')
                ->get();
        }
        if ($id=='sa'){
            return Tramite::where('estado','PROCESO')
                ->where('salubridad',false)
                ->with('user')
                ->with('caso')
                ->with('requisitos')
                ->with('contribuyente')
                ->get();
        }
        if ($id=='ac'){
            return Tramite::where('estado','ACTIVIDADES ECONOMICAS')
                ->where('infraestructura',true)
                ->where('salubridad',true)
                ->where('medio',true)
                ->where('salubridad',true)
                ->with('user')
                ->with('caso')
                ->with('requisitos')
                ->with('contribuyente')
                ->get();
        }
        if ($id=='todo'){
            return Licencia::
            with('user')
            ->with('contribuyente')
            ->with('caso')
            ->with('tramite')
            ->where('entramite','')
            ->get();
        }
        if ($id=='tra'){
            return Tramite::
                with('user')
                ->with('caso')
                ->with('requisitos')
                ->with('negocio')
                ->with('contribuyente')
                ->with('seguimientos')
                ->with('licencia')
                ->get();
        }
        if ($id=='usuario'){
            return Tramite::
                with('user')
                ->with('caso')
                ->with('requisitos')
                ->with('contribuyente')
                ->with('seguimientos')
                ->with('licencia')
                ->where('user_id',$request->user()->id)
                ->get();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $tramite=Tramite::find($id);
        $tramite->estado=$request->estado;
        if ($request->infraestructura)$tramite->infraestructura=$request->infraestructura;
        if ($request->seguridad)$tramite->seguridad=$request->seguridad;
        if ($request->medio)$tramite->medio=$request->medio;
        if ($request->salubridad)$tramite->salubridad=$request->salubridad;
        $tramite->save();
        $seguimiento= new Seguimiento();
        $seguimiento->nombre=$request->nombre;
        $seguimiento->observacion=$request->observacion;
        $seguimiento->fecha=date('Y-m-d');
        $seguimiento->hora=date('H:i:s');
        $seguimiento->tramite_id=$tramite->id;
        $seguimiento->user_id=$request->user()->id;
        $seguimiento->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
