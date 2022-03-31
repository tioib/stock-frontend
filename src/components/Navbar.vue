<template>
<div id="nav">
  <div style="align">
  <DropdownMenu title="Equipos" :items="equipos"></DropdownMenu>
  <DropdownMenu title="Clasificaciones" :items="clasificaciones"></DropdownMenu>
  </div>
</div>

</template>

<script>
  import DropdownMenu from '@/components/DropdownMenu';
  export default {
    name: 'Navbar',
    components: {DropdownMenu},
    data()
    {
      return{
        title: String,
        equipos: [
          {title: "Listar", link:"/"},
          {title: "Agregar", link:"/a"}
        ],
        clasificaciones: [
          {title: "Categorías", link:"/c"},
          {title: "Modelos", link:"/m"},
          {title: "Estados", link:"/e"}
        ]
      }
    }
  };
</script>

<style scoped>
  #nav
  {
    margin-left: 0;
    background-color: rgb(213,132,50);
    position: fixed;
    top: 0;
    width: 100%;
    display: flex;
    justify-content: flex-start;
  }

</style>
