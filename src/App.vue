<template>
  <Navbar ref="nav"/>
  <router-view @title="changeTitle" style="margin-top: 8vh"/>
</template>

<script>
  import Navbar from '@/components/Navbar';
  export default {
    methods:
    {
      changeTitle(title)
      {
        document.title = title;
      }
    },
    name:'App',
    components:{
      Navbar
    }
  }
</script>

<style>
  html,body {
    margin: 0px !important;
    padding: 0px !important;
  }

#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 0.5rem;
}


</style>
