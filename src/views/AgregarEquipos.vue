<template>
  <div class="form separate">
    <h2 style="text-align: center; margin: 0">EQUIPOS: Agregar</h2>
    <div >
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

  export default {
    components: {FormItem,StreamBarcodeReader},
    name: 'AgregarEquipos',
    data()
    {
      return{
        title: "AGREGAR EQUIPOS",

        focused: null,

        add:
        {
          serial: null,
          categoria: null,
          modelo: null,
          abonado: null,
          mac: null,
          estado: null,
          info: null,
        },

        categorias: null,
        modelos: null,
        estados: null,
      }
    },
    methods:
    {
      focus(ref)
      {
        this.focused = ref;
      },
      blured()
      {
        this.focused = null
      },
      onDecode(result)
      {
        this.focused.$refs.valor.value = result;
      },
      assign(item,component)
      {
        switch(component)
        {
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
      },
      agregar()
      {
        console.log(this.add);
        let toadd = "";

        Object.keys(this.add).forEach( key =>
          {
            if(this.add[key] != null)
            {
              toadd += this.add[key];
            }
            cont++
          }
        );
        if(!toadd.includes("&estado=")) toadd += "&estado=1";
        console.log(toadd);

        this.axios
          .get("http://192.168.88.246:80/stockapip/create.php?"+toadd+"&cual=equipo")
          .then(response => {
            console.log(response);
            if(response.data === true)
            {
              this.$refs.aviso.style = "color: green";
              this.$refs.aviso.innerText = "¡El equipo fue agregado con éxito!";
            }
            else
            {
              this.$refs.aviso.style = "color: red";
              this.$refs.aviso.innerText = "Hubo un error al agregar el equipo";
            }
          })
          .catch(e => this.info = e);
      },
      getCat()
      {
        this.axios
          .get("http://192.168.88.246:80/stockapip/show.php?cual=categoria")
          .then(response =>
          {
            this.categorias = response.data;
          })
          .catch(e => this.info = e);
      },
      getMod()
      {
        this.axios
          .get("http://192.168.88.246:80/stockapip/show.php?cual=modelo")
          .then(response =>
          {
            this.modelos = response.data;
          })
          .catch(e => this.info = e);
      },
      getEst()
      {
        this.axios
          .get("http://192.168.88.246:80/stockapip/show.php?cual=estado")
          .then(response =>
          {
            this.estados = response.data;
          })
          .catch(e => this.info = e);
      },
    },
    mounted()
    {
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
