<template>
<div id="nav">
  <div>
  <DropdownMenu title="Equipos" :items="equipos"></DropdownMenu>
  <DropdownMenu title="Clasificaciones" :items="clasificaciones"></DropdownMenu>
  <DropdownMenu title="Cerrar sesión" @click="logout"></DropdownMenu>
  </div>
</div>

</template>

<script>
  import DropdownMenu from '@/components/DropdownMenu';
  import { getAuth } from "firebase/auth";
  export default {
    name: 'Navbar',
    components: {DropdownMenu},
    data()
    {
      return{
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
    },
    methods:
    {
      logout()
      {
        getAuth().signOut();
        this.$router.replace({path: "/login"})
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
