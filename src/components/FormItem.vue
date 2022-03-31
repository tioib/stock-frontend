<template>
  <label :for="'i'+index">{{title}}</label>
  <input @keyup.enter="$emit('enter')" @focus="$emit('prefiltro',true)" @blur="$emit('prefiltro',false)" v-if="type !== 'select'" :name="'i'+index" :id="'i'+index" :type="type" v-model="item">
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
      agregarClas()
      {
        this.axios
          .get("http://192.168.88.246:80/stockapip/create.php?descripcion="+this.item+"&cual="+this.$route.name)
          .then(response => {
            console.log(response);
            this.single = response.data;
            console.log(this.single)
          })
          .catch(e => this.info = e);
      },
      eliminar()
      {
        console.log(this.index);
        this.axios
          .get("http://192.168.88.246:80/stockapip/delete.php?id="+this.index+"&cual="+this.$route.name)
          .then(response => {
            this.single = response.data;
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
