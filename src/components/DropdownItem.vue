<template>
    <router-link :to="link"><a>{{title}}</a></router-link>
</template>

<script>
  export default {
    name: 'DropdownItem',
    props: ['title','link']
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
