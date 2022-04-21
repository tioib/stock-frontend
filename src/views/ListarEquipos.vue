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

        check: false, //para ver si hay un campo de los filtros seleccionado

        item: null, //cual de las columnas de la tabla es la ordenada
        direction: null, //ascendente o descendente

        showModal: false, //mostrar o no el modal de seleccion de equipo

        single: //datos dentro del modal
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

        categorias: null, //lista de categorias para los filtros
        modelos: null, //lista de modelos para los filtros
        estados: null, //lista de estados para los filtros

        info: null, //por si hay errores
        data: null, //elementos de la tabla
        status: null, //estado HTTP
        list: ['ID','Número de serie','Categoría','Modelo','Abonado','MAC','Estado', 'Ult. fecha estado','Info adicional'],
        //headers para la tabla

        categoria: 0, //categoria seleccionada
        estado: 0, //estado seleccionado
        modelo: 0, //modelo seleccionado
        mc: null, //MAC ingresada
        abo: null, //abonado ingresado
        ser: null, //número de serie ingresado

        sorted: null, //para el ordenamiento de la tabla

        add: //para el filtrado y la edición de un equipo
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
      prefiltro(check) //verifica si se seleccionó o no un campo de los filtros
      {
        this.check = check;
      },
      filtrar()
      {
        if(this.check) this.filter() //si hay un campo seleccionado, filtra
      },
      filter() //traer resultados filtrados
      {
        this.axios
          .get("http://192.168.88.246:80/stockapip/filter.php?sort="+this.sorted+this.sorting())
          .then(response => {
            this.data = response.data;
          })
          .catch(e => this.info = e);
      },
      sorting() //junta los datos de los filtros para enviarlos como request http
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
      assingle(item,component) //ocurre si se cambia un dato en el modal, para editarlo
      {
        switch(component) //se cambia el valor a enviar según qué item del form se cambió
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
      assign(item,component) //cuando cambia un valor de los filtros
      {
        switch(component) //se cambia el valor a filtrar según qué item de los filtros se cambió
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
      eliminar() //eliminar un equipo con el id
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
      editar() //editar un equipo con el id
      {
        //primero verificar que los valores textuales no sean null, sino se cambia por string vacío
        this.single.abonado = this.single.abonado != null ? "&abonado="+this.single.abonado : '';
        this.single.mac = this.single.mac != null ? "&mac="+this.single.mac : '';
        this.single.info = this.single.info != null ? "&info="+this.single.info : '';
        //luego recién se realiza el request
        this.axios
          .get("http://192.168.88.246:80/stockapip/update.php?id="+this.single.id+"&categoria="+this.single.id_categoria+"&modelo="+this.single.id_modelo+"&estado="+this.single.id_estado+"&serial="+this.single.serial+this.single.abonado+this.single.mac+this.single.info+"&cual=equipo")
          .then(response => {
            if (response.data)
              this.sort(this.item,this.direction);
          })
          .catch(e => this.info = e);
        this.showModal = false;
      },
      getCat() //trae la lista de categorías para los filtros
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
      getMod() //trae la lista de modelos para los filtros
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
      getEst() //trae la lista de estados para los filtros
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
      modal(key) //muestra o saca el modal
      {
        this.showModal = true;
        this.show(key)
      },
      show(key) //trae el equipo a mostrar en el modal
      {
        this.axios
          .get("http://192.168.88.246:80/stockapip/showsingle.php?id="+key+"&cual=equipo")
          .then(response => {
            this.single = response.data;
            Object.keys(this.single).forEach(key =>
            {
              //porque algunos strings llegan con espacios al final
              if(this.single[key] != null)
              {
                this.single[key] = this.single[key].trim();
              }
            });
          })
          .catch(e => this.info = e);
        if(this.showModal) //para sacar la opcion de "todos" en las listas de cat/modelos/estados
        {
          this.categorias.pop({id: "0", descripcion: "Todas"});
          this.modelos.pop({id: "0", descripcion: "Todos"});
          this.estados.pop({id: "0", descripcion: "Todos"});
        }
      },
      close() //cerrar el modal
      {
        this.showModal = false;
      },
      fetch() //trae los datos de la tabla por primera vez
      {
        this.axios
          .get("http://192.168.88.246:80/stockapip/show.php?cual=equipo")
          .then(response => {
            this.data = response.data;
            this.status = response.status;
          })
          .catch(e => this.info = e);
      },
      async sort(item,direction) //para ordenar la tabla segun el header que se cliquea
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
      showModal(value) //si cambia el valor del modal
      {
        if(!value) //si el valor es false se agrega la opcion de nuevo de traer todas las cat/mod/es
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
