<template>
  <tr class="row">
      <th @click="sort" v-if="title" ref="itemsH" v-for="item in items">
        {{item}}
        <template v-if="item == sorted">
          <template v-if="direction">⬆</template>
          <template v-else>⬇</template>
        </template>
      </th>
      <td v-else ref="itemsD" v-for="item in items">{{item}}</td>
  </tr>
</template>

<script>
  export default {
    name: 'TableRow',
    data()
    {
      return{
        sorted: null,
        direction: true,
        check: String,
      }
    },
    props:
    {
      id: Number,
      items: Object,
      title: Boolean
    },
    mounted()
    {
      if(this.title)
        this.sorted = this.$refs.itemsH[0].innerText;
    },
    methods:
    {
      sort(event)
      {
        if(event.target.innerText.includes(this.sorted)) this.direction = !this.direction;
        else
        {
          this.sorted = event.target.innerText;
          this.direction = true;
        }
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

  .row:hover td
  {
    cursor: pointer;
    background-color: beige;
  }

</style>
