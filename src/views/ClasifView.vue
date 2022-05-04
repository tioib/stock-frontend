<template>
  <vue-final-modal :esc-to-close="true" v-model="showModal">
    <div class="form separate" style="background-color: black; color: white">
      <div style="display: flex; align-items: center; flex-direction: column">
        <span @click="showModal=false" id="x" style="color: red">X</span>
        <span class="modal__title">ID: {{single.id}}</span>
      </div>
      <hr>
      <div>
        <FormItem ref="modal" title="Descripción" type="text" :index="single.id" :item="single.descripcion" />
        <div class="buttoncontain">
          <div style="flex-direction: row">
            <button @click="editar" style="background-color: lightblue; color: white">Editar</button>
            <button @click="elim" style="background-color: red; color: white">Eliminar</button>
          </div>
        </div>
      </div>
    </div>
  </vue-final-modal>

  <pre v-if="status !== 200" style="margin-top: 8vh">{{info}}</pre>
  <div class="separate">
    <h2 style="text-align: center; margin: 0">EQUIPOS: {{title}}</h2>
    <hr style="margin-bottom: 2vh">
    <table>
      <TableRow @sort="sort" :title="true" :items="list" />
      <TableRow @click="show(item.id)" v-for="item in data" :title="false" :items="item"/>
    </table>
  </div>

  <div class="form separate">
    <h2>Agregar:</h2>
    <form>
      <FormItem ref="desc" type="text" title="Descripción:"/>
      <div class="buttoncontain">
        <button @click="agregar">Agregar</button>
      </div>
    </form>
  </div>
</template>

<script>
  import TableRow from '@/components/TableRow';
  import FormItem from '@/components/FormItem';
  import {getAuth, onAuthStateChanged} from "firebase/auth";

  export default {
    components: {TableRow,FormItem},
    name: 'ClasifView',
    props:
      {
        url: String,
        items: Array
      },
    data()
    {
      return {
        single: {id: null, descripcion: null}, //para el modal si se cliquea en un item de la tabla
        title: String,
        info: null, //por si hay errores
        data: null, //campos de la tabla
        status: null, //respuesta de la peticion HTTP
        list: ['ID','Descripción'], //headers de la tabla
        showModal: false //para el modal, si está abierto o cerrado
      }
    },
    methods:
      {
        sort(item,direction) //si se hace clic en un header en la tabla se ordenan los campos
        {
          //chequea en que header se hizo clic y si será ordenado ascendente o descendentemente
          let opcion = item === "ID" ? direction ? 0 : 1 : direction ? 2 : 3;
          this.axios.get("http://192.168.88.246:80/stockapip/sort.php?cual="+opcion+"&dir="+this.$route.name)
            .then(response => console.log(this.data = response.data));
        },
        changeTitle() //cambia el titulo segun la ruta
        {
          switch (this.$route.name)
          {
            case "categoria": this.title = "CATEGORÍAS"; break;
            case "modelo": this.title = "MODELOS"; break;
            case "estado": this.title = "ESTADOS"; break;
          }

          this.$emit("title",this.title)
        },
        show(key) //si se hace clic en un elemento de la tabla, busca el elemento en la bbdd
        {
          this.axios
            .get("http://192.168.88.246:80/stockapip/showsingle.php?id="+key+"&cual="+this.$route.name)
            .then(response => {
              this.single = response.data;
              this.status = response.status;
            })
            .catch(e => this.info = e);
          this.showModal = true;
        },
        elim() //eliminar el elemento
        {
          this.$refs.modal.eliminar();
          this.showModal = false;
        },
        agregar() //agregar elemento
        {
          this.$refs.desc.agregarClas()
        },
        editar() //editar el elemento
        {
          this.$refs.modal.editarClas();
        },
        fetch() //busca los valores de los elementos de la tabla
        {
          this.axios
            .get("http://192.168.88.246:80/stockapip/show.php?cual="+this.$route.name)
            .then(response =>
            {
              this.data = response.data;
              this.status = response.status;
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
      this.changeTitle();
      this.fetch();
    },
    updated()
    {
      this.changeTitle() //cambia el título de la pestaña si se actualiza el componente
    },
    watch:
    {
      $route(){this.fetch()}, //reinicia los datos de la tabla si se cambia de ruta
    }
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
  table
  {
    width: 100%;
    border-collapse: collapse;
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

  .modal__title {
    align-self: flex-start;
    margin: 0 2rem 0 0;
    font-size: 1.5rem;
    font-weight: 700;
  }
</style>
