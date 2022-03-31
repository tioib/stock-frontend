<template>
  <div id="dropdown">
    <button id="dropbtn">{{title}}</button>
    <div id="dropcont">
      <DropdownItem v-for="value in items" :title="value.title" :link="value.link" />
    </div>
  </div>
</template>

<script>
  import DropdownItem from '@/components/DropdownItem';
  export default {
    name: 'DropdownMenu',
    props:
    {
      items: Array,
      title: String
    },
    components: {DropdownItem}
  };
</script>

<style scoped>
  #dropbtn
  {
    background-color: rgba(230,132,50);
    color: white;
    padding: 16px;
    font-size: 16px;
    border: 1px solid grey;
  }

  #dropcont
  {
    display: none;
    position: absolute;
    background-color: #f1f1f1;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
  }

  #dropdown
  {
    position: relative;
    display: inline-block;
  }

  #dropdown:hover #dropcont {display: block}

  #dropdown:hover #dropbtn {background-color: rgb(213,132,50)}
</style>
