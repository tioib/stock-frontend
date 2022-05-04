<template>
    <div>
      <FormItem @agregar="assign" ref="usr" title="Usuario:" type="text" />
      <FormItem @agregar="assign" ref="pwd" title="Contraseña:" type="text" />
    </div>
</template>

<script>
    import FormItem from '@/components/FormItem';
    export default {
        name: "LoginView",
        components: {FormItem},
        data()
        {
          return {usuario: null, contraseña: null}
        },
        methods:
        {
          assign(item,component) //ocurre cuando cambia un valor en el formulario
          {
            switch(component) //agrega el valor a enviar a la API según qué componente emitió el evento
            {
              case this.$refs.usr:
                this.add.serial = item !== '' ? "&serial="+item : null;
                break;

              case this.$refs.pwd:
                this.add.categoria =  item !== '' ? "&categoria="+item : null;
                break;
            }
          },
        }

    }
</script>

<style scoped>

</style>
