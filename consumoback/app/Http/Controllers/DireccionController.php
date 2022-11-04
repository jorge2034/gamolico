<?php

namespace App\Http\Controllers;

use App\Models\Licencia;
use App\Models\Seguimiento;
use App\Models\Tramite;
use App\Models\Negocio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function asignarcomprobante(Request $request){
        //        return $request;
                $tramite=Tramite::find($request->tramite_id);
                $tramite->user_id=$request->user_id;
                $tramite->tecnico_id=$request->user_id;
                $tramite->estado="COMPROBANTE";
                $tramite->save();

                $seguimiento= new Seguimiento();
                $seguimiento->nombre="Se designo al tecnico para liquidacion de comprobante".$request->name;
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
        $negocio->observacion=$request->negocio['observacion']!=""?$request->negocio['observacion']:'';
        $negocio->fechaini = $request->fechaini!=''?$request->fechaini:'';
        $negocio->save();

        $tramite=Tramite::find($request->tramite_id);
        $tramite->tipo=$request->caso['tipo'];
        $tramite->caso_id=$request->caso['id'];
        if($request->user()->tipo=='TECNICO'){
            $tramite->estado='VALIDADO';
            $tramite->estado2='ACTIVIDAD ECONOMICA';
        }
        if($tramite->estado=="COMPROBANTE" && $request->user()->tipo!='TECNICO'){
            $tramite->estado='COMPROBANTE';
            $tramite->estado2='ACTIVIDAD ECONOMICA';
        }
        if($tramite->estado=="FINALIZADO" && $tramite->estado2=="ACTIVIDAD ECONOMICA" && $request->user()->tipo!='TECNICO'){
            $tramite->estado='APROBADO';
            $tramite->estado2='ACTIVIDAD ECONOMICA';
            $tramite->numcomprobante=$request->numcomprobante;
            $tramite->nzona=$request->nzona;
            $tramite->nsector=$request->nsector;
            $tramite->licencia=$request->licencia['numlicencia'];
        }

        $tramite->save();

        $seguimiento= new Seguimiento();
        if($request->user()->tipo=='TECNICO'){
        $seguimiento->nombre="El tecnico ".$request->user()->name." verifico ";
        $seguimiento->observacion="VERIFICADO";
        }else{
            $seguimiento->nombre="Jefe de Actividades Economicas ".$request->user()->name." aprobo el tramite ";
            $seguimiento->observacion="APROBADO";
        }
        $seguimiento->fecha=date('Y-m-d');
        $seguimiento->hora=date('H:i:s');
        $seguimiento->tramite_id=$request->tramite_id;
        $seguimiento->user_id=$request->user()->id;
        $seguimiento->save();

        // $seguimiento= new Seguimiento();
        // $seguimiento->nombre="TRAMITE FINALIZADO";
        // $seguimiento->observacion="TERMINADO";
        // $seguimiento->fecha=date('Y-m-d');
        // $seguimiento->hora=date('H:i:s');
        // $seguimiento->tramite_id=$tramite->id;
        // $seguimiento->user_id=$request->user()->id;
        // $seguimiento->save();
        if($request->user()->tipo!='TECNICO'){
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

    }

    public function aprobarcomprobante(Request $request){
        $tramite=Tramite::find($request->tramite_id);
            $tramite->estado='FINALIZADO';
            $tramite->estado2='ACTIVIDAD ECONOMICA';
            $tramite->numcomprobante=$request->numcomprobante;
            $tramite->nzona=$request->nzona;
            $tramite->nsector=$request->nsector;


        $tramite->save();

        $negocio=Negocio::find($request->negocio['id']);
        $negocio->fechaini=$request->fechaini;
        $negocio->save();

        $seguimiento= new Seguimiento();
        if($request->user()->tipo=='TECNICO'){
        $seguimiento->nombre="El tecnico ".$request->user()->name." completo el comprobante ";
        $seguimiento->observacion="COMPROBANTE COMPLETADO";
        }else{
            $seguimiento->nombre="Jefe de Actividades Economicas ".$request->user()->name." aprobo el tramite ";
            $seguimiento->observacion="APROBADO";
        }
        $seguimiento->fecha=date('Y-m-d');
        $seguimiento->hora=date('H:i:s');
        $seguimiento->tramite_id=$request->tramite_id;
        $seguimiento->user_id=$request->user()->id;
        $seguimiento->save();
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
            ->orWhere('estado','VALIDADO')
            ->orWhere('estado','FINALIZADO')
            ->where('estado2','ACTIVIDAD ECONOMICA')
            ->get();
    }
    public function mistramitescomprobante(Request $request){
        return Tramite::
        with('user')
            ->with('caso')
            ->with('negocio')
            ->with('requisitos')
            ->with('contribuyente')
            ->with('seguimientos')
            ->with('licencia')
            ->where('estado','COMPROBANTE')
            ->where('estado2','ACTIVIDAD ECONOMICA')
            ->get();
    }
    public function mistramitesdt(Request $request){
        return Tramite::
        with('user')
            ->with('caso')
            ->with('negocio')
            ->with('requisitos')
            ->with('contribuyente')
            ->with('seguimientos')
            ->with('licencia')
            ->where('estado','REGISTRADO')
            ->where('estado2','DIRECCION TRIBUTARIA')
            ->get();
    }
    public function mistramitesdtvistobueno(Request $request){
        return Tramite::
        with('user')
            ->with('caso')
            ->with('negocio')
            ->with('requisitos')
            ->with('contribuyente')
            ->with('seguimientos')
            ->with('licencia')
            ->where('estado','FINALIZADO')
            ->where('estado2','DIRECCION TRIBUTARIA')
            ->get();
    }
    public function mistramitesverificadosfinal(Request $request){
        return Tramite::
        with('user')
            ->with('caso')
            ->with('negocio')
            ->with('requisitos')
            ->with('contribuyente')
            ->with('seguimientos')
            ->with('licencia')
            ->where('estado','FINALIZADO')
            ->where('estado2','DIRECCION TRIBUTARIA')
            ->where('smeh','1')
            ->where('dirtributaria','1')
            ->get();
    }
    public function mistramitessmehvistobueno(Request $request){
        return Tramite::
        with('user')
            ->with('caso')
            ->with('negocio')
            ->with('requisitos')
            ->with('contribuyente')
            ->with('seguimientos')
            ->with('licencia')
            ->where('estado','FINALIZADO')
            ->where('estado2','SECRETARIA MUNICIPAL DE ECONOMIA Y HACIENDA')
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
            ->where('estado','APROBADO')
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
            ->whereIn('estado',['FINALIZADO','ENTREGADO'])
            ->where('estado2','VENTANILLA UNICA')
            ->where('dirtributaria','1')
            ->where('smeh','1')
//            ->where('user_id',$request->user()->id)
            ->orderBy('id','desc')
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
        $tramite= Tramite::where('estado2','DIRECCION TRIBUTARIA')
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
        $tramite->estado2=$request->estado2;
        // if ($request->infraestructura)$tramite->infraestructura=$request->infraestructura;
        // if ($request->seguridad)$tramite->seguridad=$request->seguridad;
        // if ($request->medio)$tramite->medio=$request->medio;
        // if ($request->salubridad)$tramite->salubridad=$request->salubridad;
        $tramite->save();
        $seguimiento= new Seguimiento();
        $seguimiento->nombre=$request->nombre;
        $seguimiento->observacion=$request->observacion;
        $seguimiento->fecha=date('Y-m-d');
        $seguimiento->hora=date('H:i:s');
        $seguimiento->tramite_id=$tramite->id;
        $seguimiento->user_id=$request->user()->id;
        $seguimiento->save();
        return $tramite;
    }
    public function vistobuenotributaria(Request $request, $id)
    {
        $tramite=Tramite::find($id);
        $tramite->estado=$request->estado;
        $tramite->estado2=$request->estado2;
        $tramite->dirtributaria=$request->dirtributaria;
        // if ($request->infraestructura)$tramite->infraestructura=$request->infraestructura;
        // if ($request->seguridad)$tramite->seguridad=$request->seguridad;
        // if ($request->medio)$tramite->medio=$request->medio;
        // if ($request->salubridad)$tramite->salubridad=$request->salubridad;
        $tramite->save();
        $seguimiento= new Seguimiento();
        $seguimiento->nombre=$request->nombre;
        $seguimiento->observacion=$request->observacion;
        $seguimiento->fecha=date('Y-m-d');
        $seguimiento->hora=date('H:i:s');
        $seguimiento->tramite_id=$tramite->id;
        $seguimiento->user_id=$request->user()->id;
        $seguimiento->save();
        return $tramite;
    }
    public function vistobuenosmeh(Request $request, $id)
    {
        $tramite=Tramite::find($id);
        $tramite->estado=$request->estado;
        $tramite->estado2=$request->estado2;
        $tramite->smeh=$request->smeh;
        $tramite->save();
        $seguimiento= new Seguimiento();
        $seguimiento->nombre=$request->nombre;
        $seguimiento->observacion=$request->observacion;
        $seguimiento->fecha=date('Y-m-d');
        $seguimiento->hora=date('H:i:s');
        $seguimiento->tramite_id=$tramite->id;
        $seguimiento->user_id=$request->user()->id;
        $seguimiento->save();
        return $tramite;
    }
    public function verificadofinaltributaria(Request $request, $id)
    {
        $tramite=Tramite::find($id);
        $tramite->estado=$request->estado;
        $tramite->estado2=$request->estado2;
        $tramite->save();
        $seguimiento= new Seguimiento();
        $seguimiento->nombre=$request->nombre;
        $seguimiento->observacion=$request->observacion;
        $seguimiento->fecha=date('Y-m-d');
        $seguimiento->hora=date('H:i:s');
        $seguimiento->tramite_id=$tramite->id;
        $seguimiento->user_id=$request->user()->id;
        $seguimiento->save();
        return $tramite;
    }
    public function entregafinal(Request $request, $id)
    {
        $tramite=Tramite::find($id);
        $tramite->estado=$request->estado;
        $tramite->estado2=$request->estado2;
        $tramite->save();
        $seguimiento= new Seguimiento();
        $seguimiento->nombre=$request->nombre;
        $seguimiento->observacion=$request->observacion;
        $seguimiento->fecha=date('Y-m-d');
        $seguimiento->hora=date('H:i:s');
        $seguimiento->tramite_id=$tramite->id;
        $seguimiento->user_id=$request->user()->id;
        $seguimiento->save();
        return $tramite;
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
