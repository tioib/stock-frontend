<template>
  <label v-if="title.includes('*')" :for="'i'+index"><strong>{{title}}</strong></label>
  <label v-else :for="'i'+index">{{title}}</label>
  <input ref="valor" @keyup.enter="$emit('enter')" @focus="enfocus" @blur="enblur" v-if="type !== 'select'" :name="'i'+index" :id="'i'+index" :type="type" v-model="item">
  <select v-else :name="'i'+index" :id="'i'+index" v-model="item">
    <template v-for="value in select">
      <option v-if="item === value.id" :value="value.id">{{value.descripcion}}</option>
      <option v-else :value="value.id">{{value.descripcion}}</option>
    </template>
  </select>
</template>

<script>
  export default {
    name: 'FormItem',
    props:
    {
      item: String, //el valor del item
      title: String, //texto en el tag label
      type: String, //tipo de input
      index: Number, //número para identificarlo con el label
      select: Array //la lista de opciones del select
    },
    data()
    {
      return{
        export: Object,
        focus: false,
      }
    },
    methods:
    {
      enfocus() //cuando se selecciona el elemento
      {
        if(this.$route.name == "agregar") this.$emit("focused",this);
        else this.$emit('prefiltro',true)
        /*
        si se está en la ruta para agregar equipos, se emite el evento "focused"
        sino, se emite el evento "prefiltro" con true para el filtro de los equipos en la lista
         */
      },
      enblur() //cuando se retira el foco del elemento
      {
        if(this.$route.name == "agregar") this.$emit("blured");
        else this.$emit('prefiltro',false)
        /*
        si se está en la ruta para agregar equipos, se emite el evento "blured"
        sino, se emite el evento "prefiltro" con false para el filtro de los equipos en la lista
         */
      },
      agregarClas() //agregar categoria, estado o modelo
      {
        this.axios
          .get("http://192.168.88.246:80/stockapip/create.php?descripcion="+this.item+"&cual="+this.$route.name)
          .catch(e => this.info = e);
      },
      eliminar() //eliminar categoria, estado o modelo
      {
        this.axios
          .get("http://192.168.88.246:80/stockapip/find.php?cual="+this.$route.name+"&id="+this.index)
          .then(response =>
          {
            let flag; //para ver si hay campos en la tabla de equipos con esa cat./estado/modelo
            if(response.data == "0") flag = true; //si no hay campos, se puede borrar
            else flag = window.confirm("Se encontraron "+response.data+" equipo/s registrado/s con el elemento seleccionado, ¿desea borrarlo/s?");
            //se pide al usuario que confirme si quiere borrar el elemento si se encontraron campos
            if(flag)
            {
              this.axios
                .get("http://192.168.88.246:80/stockapip/delete.php?id="+this.index+"&cual="+this.$route.name)
                .then(() => {
                  location.reload();
                })
                .catch(e => this.info = e);
            }
          })
          .catch(e => this.info = e);
      },
      editarClas() //editar categoria, estado o modelo
      {
        this.axios
          .get("http://192.168.88.246:80/stockapip/update.php?id="+this.index+"&descripcion="+this.item+"&cual="+this.$route.name)
          .catch(e => this.info = e);
      },
    },
    watch:
    {
      //si cambia el valor del item, se emite el evento "agregar" para la vista de AgregarEquipos
      item(){this.$emit("agregar",this.item,this)}
    },
  };
</script>

<style scoped>
  input, select
  {
    width: 100%;
    margin: 8px 0;
    box-sizing: border-box;
    border-radius: 3px;
  }
</style>
