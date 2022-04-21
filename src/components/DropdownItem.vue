<template>
    <router-link :to="link"><a>{{title}}</a></router-link>
</template>

<script>
  export default {
    name: 'DropdownItem', //nombre del item dentro del menu de la barra de navegacion
    props: ['title','link'] //el titulo del item y la ruta a la que lleva
  };
</script>

<style scoped>
  a
  {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
  }

  a:hover
  {
    background-color: #ddd;
  }
</style>
