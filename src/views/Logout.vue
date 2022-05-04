<template>

</template>

<script>
  import { getAuth } from "firebase/auth";
    export default {
        name: "Logout",
        mounted()
        {
          getAuth().signOut();
          this.$router.replace({path: "/login"})
        }
    }
</script>

<style scoped>

</style>
