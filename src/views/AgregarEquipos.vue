<template>
  <div class="form separate">
    <h2 style="text-align: center; margin: 0">EQUIPOS: Agregar</h2>
    <div >
      <div class="restrict" ><FormItem @focused="focus" @blured="blured" @agregar="assign" ref="cantidad" title="Cantidad: *" type="number" /></div>
      <div class="restrict" ><FormItem @focused="focus" @blured="blured" @agregar="assign" ref="serial" title="Número de serie *" type="text" /></div>
      <div class="restrict" ><FormItem @agregar="assign" ref="categoria" title="Categoría *" type="select" :select="categorias" /></div>
      <div class="restrict" ><FormItem @agregar="assign" ref="modelo" title="Modelo *" type="select"  :select="modelos"/></div>
      <div class="restrict" ><FormItem @focused="focus" @blured="blured" @agregar="assign" ref="mc" title="MAC" type="text"  /></div>
      <div class="restrict" ><FormItem @agregar="assign" ref="estado" title="Estado" item="1" type="select"  :select="estados"/></div>
      <div class="restrict" ><FormItem @agregar="assign" ref="info" title="Info adicional" type="text"  /></div>
      <div class="buttoncontain">
        <button @click="agregar">Agregar</button>
      </div>
    </div>
    <h3>Los primeros tres campos son obligatorios</h3>
  </div>
  <h3 ref="aviso"></h3>

  <!-- <h4 style="text-align: left; margin-left: 1vw">Cámara:</h4>
  <StreamBarcodeReader @decode="onDecode"></StreamBarcodeReader> -->
</template>

<script>
  import FormItem from '@/components/FormItem';
  import { StreamBarcodeReader } from "vue-barcode-reader";
  import { getAuth, onAuthStateChanged } from "firebase/auth";

  export default {
    components: {FormItem,StreamBarcodeReader},
    name: 'AgregarEquipos',
    data()
    {
      return{
        title: "AGREGAR EQUIPOS", //título de la pestaña

        focused: null, //el elemento del formulario seleccionado (para el scanner)

        add: //el objeto a enviar en el llamado GET a la API
        {
          cantidad: null,
          serial: null,
          categoria: null,
          modelo: null,
          abonado: null,
          mac: null,
          estado: null,
          info: null,
        },

        categorias: null, //para la selección de categorías en el formulario (ver getCat() en methods)
        modelos: null, //para la selección de modelos en el formulario (ver getMod() en methods)
        estados: null, //para la selección de estados en el formulario (ver getEst() en methods)
      }
    },
    methods:
    {
      focus(ref) //asigna el elemento de formulario seleccionado
      {
        this.focused = ref;
      },
      blured() //borra el elemento de formulario seleccionado si sale de foco
      {
        this.focused = null
      },
      onDecode(result) //para el scanner, escribe el valor dentro del elemento seleccionado
      {
        this.focused.$refs.valor.value = result;
      },
      assign(item,component) //ocurre cuando cambia un valor en el formulario
      {
        if(component == this.$refs.cantidad && item < 1)
        {
          this.$refs.aviso.style = "color: red";
          this.$refs.aviso.innerText = "La cantidad a agregar no puede ser menor a 1";
        }
        else
        {
          this.$refs.aviso.innerText = "";
          switch(component) //agrega el valor a enviar a la API según qué componente emitió el evento
          {
            case this.$refs.cantidad:
              this.add.cantidad = item !== '' ? "&cant="+item : null;
              break;

            case this.$refs.serial:
              this.add.serial = item !== '' ? "&serial="+item : null;
              break;

            case this.$refs.categoria:
              this.add.categoria =  item !== '' ? "&categoria="+item : null;
              break;

            case this.$refs.modelo:
              this.add.modelo =  item !== '' ? "&modelo="+item : null;
              break;

            case this.$refs.mc:
              this.add.mac =  item !== '' ? "&mac="+item : null;
              break;

            case this.$refs.info:
              this.add.info =  item !== '' ? "&info="+item : null;
              break;

            case this.$refs.estado:
              this.add.estado =  item !== '' ? "&estado="+item : null;
              break;
          }
        }
      },
      agregar() //ocurre al apretar el botón de "Agregar"
      {
        let toadd = ""; //string para juntar los valores a enviar a la API

        Object.keys(this.add).forEach( key =>
          {
            if(this.add[key] != null)
            {
              toadd += this.add[key];
            }
          }
        );
        if(!toadd.includes("&estado=")) toadd += "&estado=1";
        //si no se cambia el estado, se asigna el estado con id=1 como predeterminado

        this.axios
          .get("http://192.168.200.114:80/stockapip/create.php?"+toadd+"&cual=equipo")
          .then(response => {
            if(response.data) //si se agregó el equipo con éxito
            {
              this.$refs.aviso.style = "color: green";
              this.$refs.aviso.innerText = "¡Los equipos se agregaron con éxito!";
            }
            else //sino, avisar que hubo un error
            {
              this.$refs.aviso.style = "color: red";
              this.$refs.aviso.innerText = "Hubo un error al agregar los equipos";
            }
          })
          .catch(e => this.info = e);
      },
      getCat() //trae la lista de categorías
      {
        this.axios
          .get("http://192.168.200.114:80/stockapip/show.php?cual=categoria")
          .then(response =>
          {
            this.categorias = response.data;
          })
          .catch(e => this.info = e);
      },
      getMod() //trae la lista de modelos
      {
        this.axios
          .get("http://192.168.200.114:80/stockapip/show.php?cual=modelo")
          .then(response =>
          {
            this.modelos = response.data;
          })
          .catch(e => this.info = e);
      },
      getEst() //trae la lista de estados
      {
        this.axios
          .get("http://192.168.200.114:80/stockapip/show.php?cual=estado")
          .then(response =>
          {
            this.estados = response.data;
          })
          .catch(e => this.info = e);
      },
      auth()
      {
        onAuthStateChanged(getAuth(), (user) =>
        {
          if(!user) this.$router.replace({path: "/login"})
        })
      }
    },
    mounted()
    {
      this.auth();
      this.$emit("title",this.title);

      this.getCat();
      this.getMod();
      this.getEst();
    }
  };
</script>

<style scoped>
  .separate
  {
    margin-top: 8vh;
    padding: 1vw;
  }
  .form
  {
    margin-left: 1vw;
    text-align: left;
    width: 50%;
    border-radius: 5px;
    background-color: rgba(1,1,1,0.1);
  }

  .buttoncontain
  {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    margin: 0
  }
  button
  {
    background-color: greenyellow;
    color: grey;
    padding: 10px;
    font-size: 16px;
    border: 1px solid grey;
    align-self: flex-end;
    margin-left: 2px;
  }
  button:hover
  {
    cursor: pointer;
  }
</style>
