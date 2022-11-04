
<template>
  <q-page>
    <div class="row">
      <div class="col-12">
        <q-banner dense inline-actions class="text-white bg-red text-center text-bold">
          ACTIVIDADES ECONOMICAS - APROBACION
        </q-banner>
      </div>
      <div class="col-12 q-pa-xs">
        <q-btn label="Actualizar" icon="refresh" @click="mistramites" color="info"/>
      </div>
      <div class="col-12">
        <q-table
          title="Mis tramites"
          :columns="columns"
          :rows="tramites"
          :filter="filter"
        >
          <template v-slot:top-right>
            <q-input borderless dense debounce="300" v-model="filter" placeholder="Search">
              <template v-slot:append>
                <q-icon name="search" />
              </template>
            </q-input>
          </template>
          <template v-slot:body="props">
            <q-tr :props="props">
              <q-td key="tramitador" :props="props">
                {{ props.row.tramitador }}
              </q-td>
              <q-td key="tipo" :props="props">
                <q-badge :color="props.row.tipo=='A'?'green':'warning'">
                  {{ props.row.tipo }}
                </q-badge>
              </q-td>
              <q-td key="clasificacion" :props="props">
                <q-badge :color="props.row.tipo=='A'?'green':'warning'">
                  {{ props.row.clasificacion }}
                </q-badge>
              </q-td>
              <q-td key="tramite" :props="props">
                {{ props.row.tramite }}
              </q-td>
              <q-td key="fecha" :props="props">
                {{ props.row.fecha }}
              </q-td>
              <q-td key="dias" :props="props">
                <q-badge :color="props.row.dias<=1?'green':props.row.dias==2?'warning':'negative'">
                  {{ props.row.dias }}
                </q-badge>
              </q-td>
              <q-td key="estado" :props="props">
                <q-badge :color="props.row.estado=='ENPROCESO'?'green':'warning'">
                  {{ props.row.estado }}
                </q-badge>
              </q-td>
              <q-td key="unidad" :props="props">
                {{ props.row.unidad }}
              </q-td>
              <q-td key="action" :props="props">
                <q-btn color="blue" label="padron " icon="print" size="xs" @click="imprimir(props.row)"/>
                  <q-btn color="light-green-6" label="Resolucion Adm. y envio a visto bueno" icon="print" size="xs" @click="resoladmin(props.row)"/><hr>
                  <q-btn color="blue-grey" label="licencia " icon="print" size="xs" @click="imprimir2(props.row)"/>
                  <!-- <q-btn color="teal" label="Enviar para visto bueno " icon="check_circle_outline" size="xs" @click="ver(props.row)"/> -->
                <!--              <q-btn color="negative" label="Dar alta" icon="login" size="xs" @click="daralta(props.row)"/>-->
              </q-td>
            </q-tr>
          </template>
        </q-table>
          <div id="qr_code">
    </div>
        <q-dialog full-width  v-model="dialogtramite" persistent>
          <q-card >
            <!--            <q-card-section>-->
            <!--              <div class="text-h6">Registro Nuevo Contribuyente</div>-->
            <!--            </q-card-section>-->
            <q-form >
              <!--              <q-select dense filled v-model="tram" :options="tramites" label="Nro Tramites" @update:model-value="cambio(tram.value)"/>-->
              <q-card-section>
                <div class="text-h6 text-center" >DATOS DE CONTRIBUYENTE</div>
                <q-tabs
                  v-model="tramite.negocio.tipo"
                  dense
                  class="text-grey"
                  active-color="white"
                  indicator-color="primary"
                  active-bg-color="accent"
                  align="justify"
                  narrow-indicator
                >
                  <q-tab name="N" label="NATURAL" />
                  <q-tab name="J" label="JURIDICO" />
                </q-tabs>
                <div class="row">
                  <div class="col-4">
                    <q-checkbox rigth-label v-model="r.estado" :label="r.nombre" v-for="(r,i) in tramite.requisitos" :key="i" class="full-width" />
                  </div>
                  <div class="col-8">
                    <div class="row">
                      <div class="col-6">
                        <q-input dense outlined v-model="tramite.contribuyente.cedula" label="Cedula de Identidad"/>
                      </div>
                      <div class="col-6">
                        <q-select dense filled v-model="tramite.contribuyente.expedido" :options="exp" label="Expedido" />
                      </div>
                    </div>
                      <div class="row">
                        <div class="col-3"><q-input dense outlined v-model="tramite.contribuyente.paterno" label="Paterno" /></div>
                        <div class="col-3"><q-input dense outlined v-model="tramite.contribuyente.materno" label="Materno" /></div>
                        <div class="col-3"><q-input dense outlined v-model="tramite.contribuyente.nombres" label="Nombres" /></div>
                        <div class="col-3"><q-input dense outlined v-model="tramite.contribuyente.esposo" label="Ap. Esposo" /></div>
                      </div>
                      <div class="row">
                        <div class="col-3"><q-input dense outlined v-model="tramite.contribuyente.nit" label="NIT" /></div>
                        <div class="col-3"><q-input dense outlined v-model="tramite.contribuyente.domicilio" label="zona/domicilio" /></div>
                        <div class="col-3"><q-input dense outlined v-model="tramite.contribuyente.calle" label="Calle" /></div>
                        <div class="col-3"><q-input dense outlined v-model="tramite.contribuyente.numero" label="Numero Casa" /></div>
                      </div>
                      <div class="row">
                        <div class="col-3"><q-input dense outlined v-model="tramite.contribuyente.telefono" label="Telefono" /></div>
                        <div class="col-3"><q-input dense outlined v-model="tramite.contribuyente.telofi" label="Telf Oficina" /></div>
                        <div class="col-3"><q-input dense outlined v-model="tramite.contribuyente.casilla" label="Casilla" /></div>
                        <div class="col-3"><q-input dense outlined v-model="tramite.contribuyente.fax" label="FAX" /></div>
                      </div>
                      <div class="row">
                        <div class="col-4"><q-checkbox dense v-model="tramite.contribuyente.extrangero" label="Extrangero" /></div>
                        <div class="col-4"><q-input dense outlined v-model="tramite.contribuyente.numeroextrangero" label="Nro Extrang" /></div>
                        <div class="col-4"><q-input dense outlined v-model="tramite.contribuyente.numerodni" label="Nro DNI" /></div>
                      </div>
                    <div class="text-h6 text-center">DATOS Y UBICACION DE LA ACTIVIDAD</div>
                    <div class="row">
                      <!--                      <div class="col-6"><q-select dense filled v-model="act" @update:model-value="listadosector(act)" :options="actividades" label="Actividad"/></div>-->
                      <!--                      <div class="col-6"><q-input dense outlined v-model="sectores" label="Sector" readonly /></div>-->
                      <div class="col-6"><q-input dense outlined v-model="tramite.caso.clasificacion" label="Actividad" /></div>
                      <div class="col-6"><q-input dense outlined v-model="tramite.caso.inicio" label="Horario" /></div>
                    </div>
                    <div class="row">

                      <div class="col-9"><q-input dense outlined v-model="tramite.negocio.razon" label="Nombre" /></div>
                    </div>
                    <div class="row">

                      <div class="col-9"><q-input dense outlined v-model="tramite.negocio.descripcionactividad" label="Descripcion" /></div>
                      <div class="col-3"><q-input dense outlined v-model="tramite.negocio.mts2" label="Sup mts2" /></div>
                    </div>
                    <hr>
                    <div class="row">
                      <div class="col-3"><q-input dense outlined v-model="tramite.negocio.zona" label="Zona" /></div>
                      <div class="col-3"><q-input dense outlined v-model="tramite.negocio.Barrio" label="Barrio" /></div>
                      <div class="col-3"><q-input dense outlined v-model="tramite.negocio.calle" label="AV/Calle" /></div>
                      <div class="col-3"><q-input dense outlined v-model="tramite.negocio.entrecalles" label="Entre Calles" /></div>
                    </div>
                    <div class="row">
                      <div class="col-3"><q-input dense outlined v-model="tramite.negocio.numpiso" label="Nro Piso" /></div>
                      <div class="col-3"><q-input dense outlined v-model="tramite.negocio.telefono" label="Telefono" /></div>
                      <div class="col-3"><q-input dense outlined v-model="tramite.negocio.numeroagua" label="No Med Agua" /></div>
                      <div class="col-3"><q-input dense outlined v-model="tramite.negocio.numeroelectrico" label="No Med Elec" /></div>
                    </div>
                    <div class="row">
                      <div class="col-4"><q-input dense outlined v-model="tramite.negocio.observaciones" label="Observacion" /></div>
                      <div class="col-2"><q-checkbox v-model="tramite.negocio.fachada" label="Fachada" /></div>
                      <div class="col-2"><q-checkbox v-model="tramite.negocio.acera" label="Acera" /></div>
                      <div class="col-2"><q-checkbox v-model="tramite.negocio.iluminacion" label="Iluminacion" /></div>
                      <div class="col-2"><q-checkbox v-model="tramite.negocio.letrero" label="Letrero" /></div>
                    </div>

                    <div class="row">
                      <q-radio v-model="tramite.negocio.datoestablecimiento" val="PROPIO" label="PROPIO" />
                      <q-radio v-model="tramite.negocio.datoestablecimiento" val="ALQUILADO" label="ALQUILADO" />
                      <q-radio v-model="tramite.negocio.datoestablecimiento" val="ANTICRETICO" label="ANTICRETICO" />
                      <q-radio v-model="tramite.negocio.datoestablecimiento" val="OTROS" label="OTROS" />
                    </div>
                    <div class="row">
                      <div class="col-12">
                        <q-form @submit.prevent="asignar">
                          <div class="row">
                            <div class="col-6">
<!--                              <q-select dense label="Asignar Tecnico" :options="users" v-model="user" option-label="name" outlined  />-->
                            </div>
                            <div class="col-3 flex flex-center" >
                              <q-btn type="submit" label="Aprobar " icon="send" color="positive"/>
                            </div>
                            <div class="col-3 flex flex-center" >
                              <q-btn  label="Cancelar" v-close-popup color="red" icon="delete"/>
                            </div>
                          </div>
                        </q-form>
                      </div>
                    </div>
                  </div>
                </div>
              </q-card-section>

            </q-form>
          </q-card>
        </q-dialog>
      </div>
    </div>
  </q-page>
</template>
<script>
import {date} from "quasar";
const { addToDate } = date
import $ from 'jquery'
import { jsPDF } from "jspdf";
import { font } from "./Fenix-Regular-normal";
export default {
  data(){
    return{
      exp:['OR','LP','PT','PD','SC','CB','CH','TJ','BE','EX'],
      columns:[
        { name: 'tramitador', label: 'tramitador', field: 'tramitador'},
        { name: 'tipo', label: 'tipo', field: 'tipo'},
        { name: 'clasificacion', label: 'clasificacion', field: 'clasificacion'},
        { name: 'tramite', label: 'tramite', field: 'tramite'},
        { name: 'dias', label: 'dias', field: 'dias'},
        { name: 'estado', label: 'estado', field: 'estado'},
        { name: 'unidad', label: 'unidad', field: 'unidad'},
        { name: 'action', label: 'action', field: 'action'},
      ],
      tramites:[],
      filter:'',
      tramite:{},
      dialogtramite:false,

      requisitos:false,
      tramites2:[],
      seguimientos:[],
      licencias:[],
      licencias2:[],
      licencia:[],
      contribuyente:{},
      negocio:{}
    }
  },
  created(){
    this.mistramites()
    // this.misusuarios()

  },
  methods:{
    imprimir(i){
      console.log(i)
      var doc = new jsPDF('p','cm','letter')
      // console.log(dat);
      doc.setFont("courier");
      doc.setFont(undefined, 'bold');



      var x=0,y=0;
      var width = doc.internal.pageSize.getWidth()
      doc.setFontSize(19);
      doc.setFont("courier", 'normal');

      /////////////////////////////////////////////////////////////////////////////////////////
      //ADD FONT NEW A JSPDF https://www.devlinpeck.com/content/jspdf-custom-font
      // doc.addFileToVFS("Fenix-Regular-normal.ttf", font);
      // doc.addFont('Fenix-Regular-normal.ttf', 'Fenix-Regular', 'normal');
      // doc.setFont("Fenix-Regular");


      doc.text(x+15.5, y+6.2,i.licencia.num);
      //doc.setFont("courier");
      doc.setFont("courier", 'normal');
      doc.setFontSize(12);
      doc.text(width/2, y+8, i.contribuyente.nombres + ' ' +i.contribuyente.paterno+ ' '+ i.contribuyente.materno, { align: 'center' });
      doc.text(width/2, y+9.3, i.negocio.razon, { align: 'center' });
      doc.text(width/2, y+10.5, i.negocio.calle+' '+ i.negocio.entrecalles, { align: 'center' });
      doc.text(x+3, y+12.5, i.licencia.fechaautorizacion);
      doc.text(x+7, y+12.5, i.licencia.fechafin);
      doc.text(x+12, y+12.5, i.licencia.caso.inicio+'-'+i.licencia.caso.fin);
      doc.text(x+17, y+12.5, i.licencia.numlicencia);
      doc.setFont("courier", 'normal');
      doc.setFontSize(12);
      doc.text(x+1.2, y+14, i.negocio.descripcionactividad.toString().substring(0,29));
      doc.text(x+1.2, y+14.5, i.negocio.descripcionactividad.toString().substring(29,59));
      doc.text(x+1.2, y+15, i.negocio.descripcionactividad.toString().substring(59,89));

      doc.text(x+1.2, y+16.5, i.licencia.caso.clasificacion);
      console.log(i.licencia)
      let miPrimeraPromise = new Promise((resolve, reject) => {
        // Llamamos a resolve(...) cuando lo que estabamos haciendo finaliza con éxito, y reject(...) cuando falla.
        // En este ejemplo, usamos setTimeout(...) para simular código asíncrono.
        // En la vida real, probablemente uses algo como XHR o una API HTML5.
        var qrcode = new QRCode(document.getElementById("qr_code"), {
          text: process.env.API2+"/consultalicencia/"+i.licencia.tramite_id,
          width: 128,
          height: 128,
          colorDark : "#000000",
          colorLight : "#ffffff",
          correctLevel : QRCode.CorrectLevel.H
        });
        setTimeout(function(){
          resolve("¡Éxito!"); // ¡Todo salió bien!
        }, 1000);
      });
      miPrimeraPromise.then((successMessage) => {
        // succesMessage es lo que sea que pasamos en la función resolve(...) de arriba.
        // No tiene por qué ser un string, pero si solo es un mensaje de éxito, probablemente lo sea.
        // console.log("¡Sí! " + successMessage);
        let base64Image = $('#qr_code img').attr('src');
        // console.log(base64Image);
        doc.addImage(base64Image, 'png', x+6.5, y+15.3,2, 2);
        window.open(doc.output('bloburl'), '_blank');
      });
    },




        imprimir2(i){
          let id=i.id
          this.$q.dialog({
        title:'Seguro de finalizar tramite y enviar para visto bueno?',
        // message:''
        cancel:true
      }).onOk(()=>{
        this.$q.loading.show()
        // console.log(i)
        this.$axios.put(process.env.API+'/direccion/'+id,{
          estado:'FINALIZADO',
          estado2:'DIRECCION TRIBUTARIA',
          nombre:'ENVIADO PARA VISTO BUENO A DIRECCION TRIBUTARIA',
          observacion:'PARA VISTO BUENO',
          infraestructura:false,
          seguridad:false,
          medio:false,
          salubridad:false,
        }).then(res=>{
          // console.log(res.data)
          this.mistramites()
      // console.log(i)
      var doc = new jsPDF('p','cm','letter')
      var width = doc.internal.pageSize.getWidth()
      // console.log(dat);
      doc.setFont("courier");
      doc.setFontSize(14);
      var x=0,y=0;
      doc.text(width/2, y+13.5, i.contribuyente.nombres+' '+ i.contribuyente.paterno+' '+ i.contribuyente.materno ,{align:"center"});
      doc.text(width/2, y+15.1, i.negocio.razon,{align:"center"});
      doc.text(width/2, y+17, i.negocio.calle+' '+ i.negocio.entrecalles,{align:"center"});

      doc.text(x+3, y+18.7, i.licencia.caso.tipo);
      doc.text(x+8, y+18.7, i.licencia.caso.inicio+'-'+i.licencia.caso.fin);
      doc.setFont("courier","bold");
      doc.setFontSize(14);
      doc.text(x+14, y+18.7, i.licencia.numlicencia);

      doc.setFont("courier","normal");
      doc.setFontSize(14);
      doc.text(x+3, y+20.5, i.licencia.fechaautorizacion);
      doc.text(x+12, y+20.5, i.licencia.fechafin);


      let miPrimeraPromise = new Promise((resolve, reject) => {
        // Llamamos a resolve(...) cuando lo que estabamos haciendo finaliza con éxito, y reject(...) cuando falla.
        // En este ejemplo, usamos setTimeout(...) para simular código asíncrono.
        // En la vida real, probablemente uses algo como XHR o una API HTML5.
        var qrcode = new QRCode(document.getElementById("qr_code"), {
          text: process.env.API2+"/consultalicencia/"+i.licencia.tramite_id,
          width: 128,
          height: 128,
          colorDark : "#000000",
          colorLight : "#ffffff",
          correctLevel : QRCode.CorrectLevel.H
        });
        setTimeout(function(){
          resolve("¡Éxito!"); // ¡Todo salió bien!
        }, 1000);
      });
      miPrimeraPromise.then((successMessage) => {
        // succesMessage es lo que sea que pasamos en la función resolve(...) de arriba.
        // No tiene por qué ser un string, pero si solo es un mensaje de éxito, probablemente lo sea.
        // console.log("¡Sí! " + successMessage);
        let base64Image = $('#qr_code img').attr('src');
        // console.log(base64Image);
        doc.addImage(base64Image, 'png', x+4, y+22,2, 2);
        window.open(doc.output('bloburl'), '_blank');
      });
    })
      })
    },
    asignar(){
      this.$q.loading.show()
      this.$axios.post(process.env.API+'/aprobartramite',{
        // user_id:this.user.id,
        // name:this.user.name,
        tramite_id:this.tramite.id
      }).then(res=>{
        this.$q.loading.hide()
        // console.log(res.data)
        // return false
        this.dialogtramite=false
        // console.log(res.data)
        this.mistramites()
        // this.$swal('Hello Vue world!!!');

        this.$swal({
          // position: 'top-end',
          icon: 'success',
          title: 'Tramite probado',
          showConfirmButton: false,
          timer: 2500
        })

        return false
      }).catch(err=>{
        this.$q.loading.hide()
        this.$q.notify({
          message:err.response.data.message,
          color:'red',
          icon:'error'
        })

      })
    },

    resoladmin(i){
          const mesActual = new Date();
          const meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Setiembre", "Octubre", "Noviembre", "Diciembre"];
          // this.$q.loading.hide()
          var doc = new jsPDF('p','cm','letter')
          var width = doc.internal.pageSize.getWidth()
          // console.log(i);
          doc.setFont("courier","bold");
          doc.setFontSize(14);
          let x=0,y=3;
          doc.text(x+15, y+1, 'No.');
          doc.setFont("courier","bold");
          doc.setFontSize(14);
          doc.text(width/2, y+2, "RESOLUCIÓN ADMINISTRATIVA",{align:"center"});
          doc.setFont("courier","bold");
          doc.setFontSize(10);
          doc.text(width/2, y+2.5, 'APERTURA',{align:"center"});
          doc.text(width/2, y+3, date.formatDate( Date.now(),'DD')+' de '+meses[mesActual.getMonth()]+' del '+date.formatDate( Date.now(),'YYYY'),{align:"center"});

          doc.text('VISTOS Y CONSIDERANDO', x+2, y+4);
          doc.setFont("courier","normal");
          let textLines=doc.splitTextToSize('Que la solicitud presentada por el o la Sr. (a) '+ i.contribuyente.nombres.trim()+' '+i.contribuyente.paterno.trim()+' '+i.contribuyente.materno.trim()+' impetrando al Gobierno Autonomo Municipal de Oruro comuna autorización para la apertura de: '+i.caso.clasificacion.trim()+'; los informes elevados por la unidad de Actividades Económicas',17)
          doc.text(textLines, x+2, y+5,{maxWidth: 17,align: "justify"});

          textLines=doc.splitTextToSize('Que, habiendo el (la) impetrante cancelado los derechos correspondientes en la caja del tesoro municipal según comprobante adjunto Nro. '+i.numcomprobante,17)
          doc.text(textLines, x+2, y+7,{maxWidth: 17,align: "justify"});
          doc.setFont("courier","bold");
          doc.text('POR TANTO:', x+2, y+9);
          doc.setFont("courier","normal");
          textLines=doc.splitTextToSize('SE RESUELVE: Autorizar al (la) Señor(a): '+ i.contribuyente.nombres.trim()+' '+i.contribuyente.paterno.trim()+' '+i.contribuyente.materno.trim()+', la apertura y legal funcionamiento de: '+i.caso.clasificacion.trim()+', denominado  '+i.negocio.razon.trim()+' ubicado en las calles '+i.negocio.calle+' '+i.negocio.entrecalles+' de la ciudad, debiendo cumplir con el pago de sus obligaciones conforme a la ordenanza de impuesto y patentes ',17)
          doc.text(textLines, x+2, y+10,{maxWidth: 17,align: "justify"});

          textLines=doc.splitTextToSize('Queda inscrita en el padrón municipal de: actividades económicas, bajo el Nro. '+i.licencia.num+'.',17)
          doc.text(textLines, x+2, y+12.5,{maxWidth: 17,align: "justify"});

          textLines=doc.splitTextToSize('De conformidad a la normativa municipal en vigencia la autorización de estas actividades queda bajo la responsabilidad de las unidades involucradas po lo que deberán prever conforme establece el art. 14 de la ley 1178(control previo) y disposiciones sobre la responsabilidad por la función publica',17)
          doc.text(textLines, x+2, y+14,{maxWidth: 17,align: "justify"});

          // textLines=doc.splitTextToSize('',17)
          doc.text('Regístrese, comuníquese y archívese.', x+2, y+16);

          // textLines=doc.splitTextToSize('ES CONFORME.',17)
          doc.setFont("courier","bold");
          doc.text('ES CONFORME.', x+2, y+17);
          doc.text('SECRETARIA MUNICIPAL DE ECONOMÍA Y HACIENDA.', x+10, y+18);
          doc.setFont("courier","normal");
          window.open(doc.output('bloburl'), '_blank');



    },

    // misusuarios(){
    //   this.$axios.get(process.env.API+'/user').then(res=>{
    //     // console.log(res.data)
    //     this.users=[]
    //     res.data.forEach(r=>{
    //       if (r.tipo=='TECNICO')
    //         this.users.push(r)
    //     })
    //     this.user=this.users[0]
    //   })
    // },
    ver(tramite){
      // console.log(tramite)
      // return false
      this.tramite=tramite

      this.tramite.negocio.fachada=this.tramite.negocio.fachada=='1'?true:false
      this.tramite.negocio.acera=this.tramite.negocio.acera=='1'?true:false
      this.tramite.negocio.iluminacion=this.tramite.negocio.iluminacion=='1'?true:false
      this.tramite.negocio.letrero=this.tramite.negocio.letrero=='1'?true:false

      this.dialogtramite=true
    },
    mistramites(){
      this.$q.loading.show()
      this.$axios.post(process.env.API+'/mistramitesaprobacion').then(res=>{
        console.log(res.data)
        this.tramites=[]
        res.data.forEach(r=>{
          const date1 = new Date()
          const date2 = date.extractDate(r.fecha, 'YYYY-MM-DD')
          const unit = 'days'
          const diff = date.getDateDiff(date1, date2, unit)
          let d=r
          d.clasificacion=r.caso.clasificacion
          d.dias=diff
          d.estado=r.estado
          d.unidad=r.estado2
          this.licencia.push(r.licencia)
          this.tramites.push(d)
          // this.tramites.push({
          //   'tramitador':r.tramitador,
          //   'id':r.id,
          //   'tipo':r.tipo,
          //   'clasificacion':r.caso.clasificacion,
          //   'usuario':r.user.name,
          //   'tramite':r.nrotramite,
          //   'fecha':r.fecha,
          //   'dias':diff,
          //   'estado':r.estado2,
          //   'unidad':r.estado,
          //   'requisitos':r.requisitos
          // })
        })
        this.$q.loading.hide()
      }).catch(err=>{
        this.$q.loading.hide()
        this.$q.notify({
          message:err.response.data.message,
          color:'red',
          icon:'error'
        })
      })
    }
  }
}
</script>
<style>
#qr_code{
  display: none;
}
</style>
