<template>
  <v-app>
    <v-main>
      <router-view></router-view> <!-- Isto irá renderizar o componente da rota ativa -->
    </v-main>
  </v-app>
</template>

<script>
export default {
  name: 'App'
}
</script>
