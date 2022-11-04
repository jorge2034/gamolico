<template>
  <q-page class="q-pa-xs">
    <q-card>
      <q-badge class="text-h6 full-width text-center" color="secondary" >Direccion tributaria VERIFICACION FINAL</q-badge>
      <q-btn @click="mistramites" icon="refresh" label="refrescar" color="secondary" class="q-mt-xs"/>
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
<!--              <q-badge color="orange">-->
                {{ props.row.tramite }}
<!--              </q-badge>-->
            </q-td>
<!--            <q-td key="fecha" :props="props">-->
<!--&lt;!&ndash;              <q-badge color="primary">&ndash;&gt;-->
<!--                {{ props.row.fecha }}-->
<!--&lt;!&ndash;              </q-badge>&ndash;&gt;-->
<!--            </q-td>-->
            <q-td key="dias" :props="props">
              <q-badge :color="props.row.dias<=2?'green':props.row.dias==2?'warning':'negative'">
                {{ props.row.dias }} dias
              </q-badge>
            </q-td>
             <q-td key="dirtributaria" :props="props">
              <q-icon v-if="props.row.dirtributaria=='1'" name="check_circle" size="32px" color="positive" />
            </q-td>
             <q-td key="smeh" :props="props">
              <q-icon v-if="props.row.smeh=='1'" name="check_circle" size="32px" color="positive" />
            </q-td>
<!--            <q-td key="estado" :props="props">-->
<!--              <q-badge :color="props.row.estado=='ENPROCESO'?'green':'warning'">-->
<!--                {{ props.row.estado }}-->
<!--              </q-badge>-->
<!--            </q-td>-->
            <q-td key="unidad" :props="props">
                {{ props.row.unidad }}
            </q-td>
<!--            <q-td key="requisitos" :props="props">-->
<!--              <ul style="padding: 0px;margin: 0px;border: 0px">-->
<!--                <li v-for="(r,i) in props.row.requisitos" :key="i" style="padding: 0px;margin: 0px;border: 0px">-->
<!--&lt;!&ndash;                  <q-badge :label="r.nombre" class="q-pa-none q-ma-none" />&ndash;&gt;-->
<!--                  {{ r.nombre }}-->
<!--                </li>-->
<!--              </ul>-->
<!--            </q-td>-->
            <q-td key="action" :props="props">
              <!--              <q-badge color="amber">-->
              <q-btn color="light-green-6" label="DAR VISTO BUENO" icon="check_circle_outline" size="xs" @click="ver(props.row)"/>
              <!-- <q-btn v-if="props.row.unidad=='DIRECCION TRIBUTARIA'" color="negative" label="Enviar" icon="login" size="xs" @click="daraltae(props.row)"/> -->
              <!-- <q-btn v-else color="accent" label="Espectaculos publicos" icon="login" size="xs" @click="daraltae(props.row)"/> -->
              <!--              </q-badge>-->
            </q-td>
          </q-tr>
        </template>
      </q-table>
      <!-- <q-dialog v-model="requisitos">
        <q-card>
          <q-card-section><div class="text-h">Requisitos presentados</div></q-card-section>
          <q-card-section class="q-pt-none">
            <ul>
              <li v-for="(r,i) in misrequisitos" :key="i">{{r.nombre}}</li>
            </ul>
          </q-card-section>
          <q-card-section aling="right">
            <q-btn flat label="ok" icon="trash" color="negative" v-close-popup/>
          </q-card-section>
        </q-card>
      </q-dialog> -->

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
                    </div>
                    <div class="text-h6 text-center">DATOS Y UBICACION DE LA ACTIVIDAD</div>
                    <div class="row">
                             <div class="col-9"><q-input dense outlined v-model="tramite.caso.clasificacion" label="Actividad" /></div>
                            <div class="col-3"><q-input dense outlined v-model="tramite.caso.inicio" label="Horario" /></div>

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
                         <!-- <q-form @submit.prevent="vistobueno"> -->
                          <div class="row">
                            <div class="col-6"></div>
                          </div>
                          <div class="row">
                            <div class="col-6"><div class="row">
<!--                              <q-select dense label="Asignar Tecnico" :options="users" v-model="user" option-label="name" outlined  />-->
 <div class="col-4">
                                <q-input outlined dense v-model="tramite.numcomprobante" type="text" label="Num Comprobante"/></div>
                                <div class="col-4"><q-input outlined dense v-model="tramite.licencia.num" type="text" label="Numero"/></div>
                                <div class="col-4"><q-input outlined dense v-model="tramite.licencia.numlicencia" type="text" label="N Licencia"/></div>
                                </div>
                            </div>
                            <div class="col-3 flex flex-center" >
<!--              </ul>-->
                              <q-btn color="positive" label="Aprobacion Final" icon="send" @click="vistobueno(idtramite)"/>
                            </div>
                            <div class="col-3 flex flex-center" >
                              <q-btn  label="Cancelar" v-close-popup color="red" icon="delete"/>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-6"><div class="row">
<!--                              <q-select dense label="Asignar Tecnico" :options="users" v-model="user" option-label="name" outlined  />-->
 <div class="col-4">
  <q-input outlined dense v-model="tramite.nzona" type="text" label="Zona"/></div>
                                <div class="col-4"><q-input outlined dense v-model="tramite.nsector" type="text" label="Sector"/></div>
                                <div class="col-4"><q-input outlined dense v-model="tramite.negocio.fechaini" type="date" label="FechaInicio"/></div>
                                </div>
                            </div>
                            </div>
                        <!-- </q-form> -->
                      </div>
                    </div>
                  </div>
                </div>
              </q-card-section>

            </q-form>
          </q-card>
        </q-dialog>
    </q-card>
  </q-page>
</template>

<script>
import { date } from 'quasar'
const { addToDate } = date
import $ from 'jquery'
import { jsPDF } from "jspdf";


export default {
  // name: 'Local',
  data(){
    return{
      filter:'',
      dialogtramite:false,
      misrequisitos:[],
      columns:[
        { name: 'tramitador', label: 'Titular', field: 'tramitador'},
        { name: 'tipo', label: 'tipo', field: 'tipo'},
        { name: 'clasificacion', label: 'clasificacion', field: 'clasificacion'},
        { name: 'tramite', label: 'tramite', field: 'tramite'},
        // { name: 'fecha', label: 'fecha', field: 'fecha'},
        { name: 'dias', label: 'dias', field: 'dias'},
        { name: 'dirtributaria', label: 'Direccion Tributaria', field: 'dirtributaria'},
        { name: 'smeh', label: 'Secretaria de economia y hacienda', field: 'smeh'},
        // { name: 'estado', label: 'estado', field: 'estado'},
        { name: 'unidad', label: 'Estado', field: 'unidad'},
        // { name: 'requisitos', label: 'requisitos', field: 'requisitos',    align: 'left'},
        // { name: 'tipo', label: 'tipo', field: 'tipo'},
        { name: 'action', label: 'action', field: 'action'},
      ],
      tramites:[],
      idtramite:''
    }
  },
  created(){
    // console.log('aa')
    // setTimeout(() => {
    //   console.log("Refresh")
    // }, 1000)
    // console.log('ias')
    // window.location.reload(true)

    this.mistramites()
  },
  methods:{
    ver(tramite){
       console.log(tramite)
      // return false
      this.tramite=tramite
      this.idtramite=tramite.id
      this.tramite.negocio.fachada=this.tramite.negocio.fachada=='1'?true:false
      this.tramite.negocio.acera=this.tramite.negocio.acera=='1'?true:false
      this.tramite.negocio.iluminacion=this.tramite.negocio.iluminacion=='1'?true:false
      this.tramite.negocio.letrero=this.tramite.negocio.letrero=='1'?true:false
      this.tramite.requisitos.forEach(element => {
        element.estado=true;
      });
      this.dialogtramite=true
    },

    vistobueno(i){
      let id = i;
      console.log("id",id)
      this.$q.dialog({
        title:'Seguro de ENVIAR a ventanilla unica para ser entregado al titular?',
        // message:''
        cancel:true
      }).onOk(()=>{
        this.$q.loading.show()
        // console.log(i)
        this.$axios.post(process.env.API+'/verificadofinaltributaria/'+id,{
          estado:'FINALIZADO',
          estado2:'VENTANILLA UNICA',
          nombre:'ENVIADO A VENTANILLA UNICA PARA SER ENTREGADO AL TITULAR',
          observacion:'LISTO PARA ENTREGAR',
          infraestructura:false,
          seguridad:false,
          medio:false,
          salubridad:false,
        }).then(res=>{
          // console.log(res.data)
          this.dialogtramite=false
          this.mistramites()
        })
      })
    },

    verrequisitos(r){
      // console.log(r)
      this.requisitos=true
      this.misrequisitos=r
    },
    mistramites(){
      this.$q.loading.show()
      this.$axios.post(process.env.API+'/mistramitesverificadosfinal').then(res=>{
        console.log(res)
        // console.log(res.data)
        this.tramites=[]
        // res.data.forEach(r=>{
        //   const date1 = new Date()
        //   const date2 = date.extractDate(r.fecha, 'YYYY-MM-DD')
        //   const unit = 'days'
        //   const diff = date.getDateDiff(date1, date2, unit)
        //   // console.log(r)
        //   this.tramites.push({
        //     'tramitador':r.tramitador,
        //     'id':r.id,
        //     'tipo':r.tipo,
        //     'clasificacion':r.caso.clasificacion,
        //     'usuario':r.user.name,
        //     'tramite':r.nrotramite,
        //     'fecha':r.fecha,
        //     'contribuyente':r.contribuyente,
        //     'dias':diff,
        //     'estado':r.estado2,
        //     'unidad':r.estado=='PROCESO'?'EN PROCESO Y REVISADO':r.estado,
        //     // 'action':'',
        //     'requisitos':r.requisitos,
        //     'caso':r.caso
        //   })
        // })
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
          this.tramites.push(d)
        })


        // this.contribuyentes=res.data
        this.$q.loading.hide()
      })
    }
  }
}
</script>
