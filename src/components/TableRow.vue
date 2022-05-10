<template>
  <tr class="row">
      <template v-if="title">
        <template v-if="select"><th>Sel.</th></template>
        <th @click="sort"  ref="itemsH" v-for="item in items">
          {{item}}
          <template v-if="item == sorted">
            <template v-if="direction">⬆</template>
            <template v-else>⬇</template>
          </template>
        </th>
      </template>
      <template v-else>
        <template v-if="select"><td><input @click="sel" type="checkbox" ref="checkbox"></td></template>
        <td @click="$emit('single')" class="item" ref="itemsD" v-for="item in items">{{item}}</td>
      </template>
  </tr>
</template>

<script>
  export default {
    name: 'TableRow',
    data()
    {
      return{
        select: this.$route.name == "equipo",
        sorted: null, //nombre de la columna ordenada
        direction: true, //ascendente o desc
      }
    },
    props:
    {
      id: Number, //id del elemento
      items: Object, //items de la fila
      title: Boolean //si es header o fila
    },
    mounted()
    {
      if(this.title) //para que esté la flecha en la columna ID de la tabla al inicio
        this.sorted = this.$refs.itemsH[0].innerText;
    },
    methods:
    {
      sel()
      {
        if(this.$refs.checkbox.checked) this.$emit('select');
        else this.$emit('unselect')
      },
      sort(event) //cuando se hace clic en un header se ordena esa columna
      {
        //si se hizo clic en la columna ya ordenada se cambia de dirección
        if(event.target.innerText.includes(this.sorted)) this.direction = !this.direction;
        else //sino se cambia la columna ordenada y la direccion es asc
        {
          this.sorted = event.target.innerText;
          this.direction = true;
        }
        //por ultimo se emite el evento para el componente que busca los datos de la tabla
        this.$emit("sort",this.sorted,this.direction);
      }
    }
  };
</script>

<style scoped>
  th,td
  {
    border: 1px solid black;
  }

  th:hover
  {
    cursor: pointer;
    background-color: #42b983;
  }

  .row:hover .item
  {
    cursor: pointer;
    background-color: beige;
  }


</style>
