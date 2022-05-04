<template>
    <div>
      <FormItem @agregar="assign" ref="usr" title="Usuario:" type="text" />
      <FormItem @agregar="assign" ref="pwd" title="Contraseña:" type="password" />
      <button @click="login">Login</button>
    </div>
  <h4 style="color: red">{{error}}</h4>
</template>

<script>
    import FormItem from '@/components/FormItem';
    import { getAuth, signInWithEmailAndPassword } from "firebase/auth";

    export default {
        name: "LoginView",
        components: {FormItem},
        data()
        {
          return {usuario: null, contraseña: null, error: null}
        },
        methods:
        {
          login() //ocurre cuando cambia un valor en el formulario
          {
            signInWithEmailAndPassword(getAuth(), this.usuario, this.contraseña)
            .then((data) => this.$router.replace({path: "/"}))
            .catch(error =>
            {
              switch (error.code)
              {
                case "auth/user-not-found": this.error = "No se encontró el usuario"; break;
                case 'auth/user-not-found': this.error = 'Contraseña incorrecta'; break;
                default: this.error = "Email o contraseña incorrecta";
              }
              console.log(error)
            })
          },
          assign(item,component) //ocurre cuando cambia un valor en el formulario
          {
            switch(component) //agrega el valor a enviar a la API según qué componente emitió el evento
            {
              case this.$refs.usr:
                this.usuario = item !== '' ? item : null;
                break;

              case this.$refs.pwd:
                this.contraseña =  item !== '' ? item : null;
                break;
            }
          },
        }

    }
</script>

<style scoped>
div
{
  width: 50%;
  text-align: left;
  margin: 10vh;
  padding: 1vh;
  border-radius: 1vh;
  border: 5px solid black
}
</style>
