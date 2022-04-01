<template>
  <vue-final-modal :esc-to-close="true" v-model="showModal">
    <div class="form separate" style="background-color: black; color: white">
      <div style="display: flex; align-items: center; flex-direction: column">
        <span @click="close" id="x" style="color: red">X</span>
        <span class="modal__title">ID: {{single.id}}</span>
      </div>
      <hr>
      <div>
        <div class="restrict" ><FormItem @agregar="assingle" ref="serials" title="Número de serie" type="text" :item="single.serial" /></div>
        <div class="restrict" ><FormItem @agregar="assingle" ref="categorias" title="Categoría" type="select" :item="single.id_categoria" :select="categorias" /></div>
        <div class="restrict" ><FormItem @agregar="assingle" ref="modelos" title="Modelo" type="select" :item="single.id_modelo" :select="modelos"/></div>
        <div class="restrict" ><FormItem @agregar="assingle" ref="abonados" title="Abonado" type="text" :item="single.abonado" /></div>
        <div class="restrict" ><FormItem @agregar="assingle" ref="mcs" title="MAC" type="text" :item="single.mac" /></div>
        <div class="restrict" ><FormItem @agregar="assingle" ref="estados" title="Estado" type="select" :item="single.id_estado" :select="estados"/></div>
        <div class="restrict" ><FormItem @agregar="assingle" ref="info" title="Info adicional" type="text" :item="single.info" /></div>
        <div class="buttoncontain">
          <div style="flex-direction: row">
            <button @click="editar" style="background-color: lightblue; color: white">Editar</button>
            <button @click="eliminar" style="background-color: red; color: white">Eliminar</button>
          </div>
        </div>
      </div>
    </div>
  </vue-final-modal>

  <pre v-if="status !== 200" style="margin-top: 8vh">{{info}}</pre>
  <div class="separate">
    <h2 style="text-align: center; margin: 0">EQUIPOS: Lista</h2>
    <hr>
    <div style="display: flex; flex-direction: row; align-items: center; justify-content: center">
      <div class="restrict" ><FormItem @prefiltro="prefiltro" @enter="filtrar" @agregar="assign" ref="categoria" type="select" title="Categoría" :item="categoria" :select="categorias"/></div>
      <div class="restrict" ><FormItem @prefiltro="prefiltro" @enter="filtrar" @agregar="assign" ref="modelo" type="select" title="Modelo" :item="modelo" :select="modelos"/></div>
      <div class="restrict" ><FormItem @prefiltro="prefiltro" @enter="filtrar" @agregar="assign" ref="estado" type="select" title="Estado" :item="estado" :select="estados"/></div>
      <div class="restrict" ><FormItem @prefiltro="prefiltro" @enter="filtrar" @agregar="assign" ref="serial" type="text" title="Número de serie" :item="ser"/></div>
      <div class="restrict" ><FormItem @prefiltro="prefiltro" @enter="filtrar" @agregar="assign" ref="abonado" type="text" title="Abonado" :item="abo"/></div>
      <div class="restrict" ><FormItem @prefiltro="prefiltro" @enter="filtrar" @agregar="assign" ref="mc" type="text" title="MAC" :item="mc"/></div>
      <button @click="filter" style="background-color: cyan;">Filtrar</button>
    </div>
    <table style="margin-top: 1vh">
      <thead>
        <TableRow @sort="sort" :title="true" :items="list" />
      </thead>
      <tbody>
        <TableRow @click="modal(value.id)" v-for="value in data" :title="false" :items="value"/>
      </tbody>
    </table>
  </div>
</template>

<script>
  import TableRow from '@/components/TableRow';
  import FormItem from '@/components/FormItem';

  export default {
    components: {TableRow,FormItem},
    name: 'ListarEquipos',
    data()
    {
      return {
        title: "EQUIPOS",

        check: false,

        item: null,
        direction: null,

        showModal: false,

        single:
        {
          id: null,
          categoria: null,
          modelo: null,
          abonado: null,
          id_modelo: null,
          id_categoria: null,
          mac: null,
          estado: null,
          id_estado: null,
          info: null,
        },

        categorias: null,
        modelos: null,
        estados: null,

        info: null,
        data: null,
        status: null,
        list: ['ID','Número de serie','Categoría','Modelo','Abonado','MAC','Estado', 'Ult. fecha estado','Info adicional'],

        categoria: 0,
        estado: 0,
        modelo: 0,
        mc: null,
        abo: null,
        ser: null,

        sorted: null,

        add:
        {
          categoria: null,
          estado: null,
          modelo: null,
          serial: null,
          abonado: null,
          mac: null,
          url: null,
        }

      }
    },
    methods:
    {
      prefiltro(check)
      {
        console.log("check = "+check);
        this.check = check;
      },
      filtrar()
      {
        console.log("filtrar");
        if(this.check) this.filter()
      },
      assingle(item,component)
      {
        switch(component)
        {
          case this.$refs.serials:
             this.single.serial = item !== '' ? item : null;
            break;

          case this.$refs.categorias:
            this.single.id_categoria =  item !== '' ? item : null;
            break;

          case this.$refs.modelos:
            this.single.id_modelo =  item !== '' ? item : null;
            break;

          case this.$refs.mcs:
            this.single.mac =  item !== '' ? item : null;
            break;

          case this.$refs.abonados:
            this.single.abonado =  item !== '' ? item : null;
            break;

          case this.$refs.estados:
            this.single.id_estado =  item !== '' ? item : null;
            break;

          case this.$refs.info:
            this.single.info =  item !== '' ? item : null;
            break;
        }
      },
      assign(item,component)
      {
        switch(component)
        {
          case this.$refs.serial:
            this.add.serial = item !== '' ? item : null;
            break;

          case this.$refs.categoria:
            this.add.categoria =  item !== '' ? item : null;
            this.filter();
            break;

          case this.$refs.modelo:
            this.add.modelo =  item !== '' ? item : null;
            this.filter();
            break;

          case this.$refs.mc:
            this.add.mac =  item !== '' ? item : null;
            break;

          case this.$refs.abonado:
            this.add.abonado =  item !== '' ? item : null;
            break;

          case this.$refs.estado:
            this.add.estado =  item !== '' ? item : null;
            this.filter();
            break;
        }
      },
      sorting()
      {
        let toadd = "";

        Object.keys(this.add).forEach(
          key =>
          {
            if(this.add[key] != null)
            {
              switch (key)
              {
                case "categoria": this.categoria = this.add[key]; toadd += "&categoria="+this.add[key]; break;
                case "modelo": this.modelo = this.add[key]; toadd += "&modelo="+this.add[key]; break;
                case "estado": this.estado = this.add[key]; toadd += "&estado="+this.add[key]; break;
                case "abonado": this.abo = this.add[key]; toadd += "&abonado="+this.add[key]; break;
                case "mac": this.mc = this.add[key]; toadd += "&mac="+this.add[key]; break;
                case "serial": this.ser = this.add[key]; toadd += "&serial="+this.add[key]; break;
              }
            }
          }
        );
        return toadd;
      },
      filter()
      {
        this.axios
          .get("http://192.168.88.246:80/stockapip/filter.php?sort="+this.sorted+this.sorting())
          .then(response => {
            this.data = response.data;
          })
          .catch(e => this.info = e);
      },
      eliminar()
      {
        this.axios
          .get("http://192.168.88.246:80/stockapip/delete.php?id="+this.single.id+"&cual=equipo")
          .then(response =>
          {
            if(response.data)
            {
              this.sort(this.item, this.direction);
            }
          })
          .catch(e => this.info = e);
        this.showModal = false;
      },
      editar()
      {
        this.single.abonado = this.single.abonado != null ? "&abonado="+this.single.abonado : '';
        this.single.mac = this.single.mac != null ? "&mac="+this.single.mac : '';
        this.single.info = this.single.info != null ? "&info="+this.single.info : '';
        this.axios
          .get("http://192.168.88.246:80/stockapip/update.php?id="+this.single.id+"&categoria="+this.single.id_categoria+"&modelo="+this.single.id_modelo+"&estado="+this.single.id_estado+"&serial="+this.single.serial+this.single.abonado+this.single.mac+this.single.info+"&cual=equipo")
          .then(response => {
            if (response.data)
            {
              this.sort(this.item,this.direction);
            }
          })
          .catch(e => this.info = e);
        this.showModal = false;
      },
      getCat()
      {
        this.axios
          .get("http://192.168.88.246:80/stockapip/show.php?cual=categoria")
          .then(response =>
          {
            this.categorias = response.data;
            this.categorias.push({id: "0", descripcion: "Todas"});
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
            this.modelos.push({id: "0", descripcion: "Todos"});
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
            this.estados.push({id: "0", descripcion: "Todos"});
          })
          .catch(e => this.info = e);
      },
      modal(key)
      {
        this.showModal = true;
        this.show(key)
      },
      show(key)
      {
        this.axios
          .get("http://192.168.88.246:80/stockapip/showsingle.php?id="+key+"&cual=equipo")
          .then(response => {
            this.single = response.data;
            Object.keys(this.single).forEach(key =>
            {
              if(this.single[key] != null)
              {
                this.single[key] = this.single[key].trim();
              }
            });
          })
          .catch(e => this.info = e);
        if(this.showModal)
        {
          this.categorias.pop({id: "0", descripcion: "Todas"});
          this.modelos.pop({id: "0", descripcion: "Todos"});
          this.estados.pop({id: "0", descripcion: "Todos"});
        }
      },
      close()
      {
        this.showModal = false;
      },
      fetch()
      {
        this.axios
          .get("http://192.168.88.246:80/stockapip/show.php?cual=equipo")
          .then(response => {
            this.data = response.data;
            this.status = response.status;
          })
          .catch(e => this.info = e);
      },
      async sort(item,direction)
      {
        this.item = item; this.direction = direction;

        let opcion = 0;
        switch (item)
        {
          case "ID":
            direction ? opcion = 0 : opcion = 1;
            break;
          case "Número de serie":
            direction ? opcion = 2 : opcion = 3;
            break;
          case "Categoría":
            direction ? opcion = 4 : opcion = 5;
            break;
          case "Modelo":
            direction ? opcion = 6 : opcion = 7;
            break;
          case "Abonado":
            direction ? opcion = 8 : opcion = 9;
            break;
          case "MAC":
            direction ? opcion = 10 : opcion = 11;
            break;
          case "Estado":
            direction ? opcion = 12 : opcion = 13;
            break;
          case "Ult. fecha estado":
            direction ? opcion = 14 : opcion = 15;
            break;

          case "Info adicional":
            direction ? opcion = 16 : opcion = 17;
            break;
        }
        this.sorted = opcion;
        await this.axios.get("http://192.168.88.246:80/stockapip/sort.php?cual="+opcion+"&dir=equipo"+this.sorting())
          .then(response =>
          {
            this.data = response.data;
          });
      }
    },
    mounted()
    {
      this.$emit("title",this.title);

      this.fetch();

      this.getCat();
      this.getMod();
      this.getEst();
    },
    watch:
    {
      showModal(value)
      {
        if(!value)
        {
          this.categorias.push({id: "0", descripcion: "Todas"});
          this.modelos.push({id: "0", descripcion: "Todos"});
          this.estados.push({id: "0", descripcion: "Todos"});
        }
      }
    },
  };
</script>

<style scoped>
  #x:hover
  {
    cursor: pointer;
  }
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
  .restrict
  {
    padding: 2px;
  }
  table
  {
    width: 100%;
    border-collapse: collapse;
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
  .modal__title {
    align-self: flex-start;
    margin: 0 2rem 0 0;
    font-size: 1.5rem;
    font-weight: 700;
  }
</style>
