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
      item: String,
      title: String,
      type: String,
      index: Number,
      select: Array
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
      enfocus()
      {
        if(this.$route.name == "agregar") this.$emit("focused",this);
        else this.$emit('prefiltro',true)
      },
      enblur()
      {
        if(this.$route.name == "agregar") this.$emit("blured");
        else this.$emit('prefiltro',false)
      },
      agregarClas()
      {
        this.axios
          .get("http://192.168.88.246:80/stockapip/create.php?descripcion="+this.item+"&cual="+this.$route.name)
          .then(response => {
            console.log(response);
          })
          .catch(e => this.info = e);
      },
      eliminar()
      {
        this.axios
          .get("http://192.168.88.246:80/stockapip/find.php?cual="+this.$route.name+"&id="+this.index)
          .then(response =>
          {
            let flag;
            if(response.data == "0") flag = true;
            else flag = window.confirm("Se encontraron "+response.data+" equipo/s registrado/s con el elemento seleccionado, ¿desea borrarlo/s?");
            if(flag)
            {
              console.log(this.index);
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
      editarClas()
      {
        this.axios
          .get("http://192.168.88.246:80/stockapip/update.php?id="+this.index+"&descripcion="+this.item+"&cual="+this.$route.name)
          .then(response => {
            this.single = response.data;
          })
          .catch(e => this.info = e);
      },
    },
    watch:
    {
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
