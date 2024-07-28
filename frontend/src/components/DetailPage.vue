<template>
    <v-container>
      <v-card v-if="person">
        <v-card-title>{{ person.name }}</v-card-title>
        <v-card-subtitle>{{ person.cpf }}</v-card-subtitle>
        <v-card-text>
          <v-list>
            <v-list-item>
              <v-list-item-content>
                <v-list-item-title>Email</v-list-item-title>
                <v-list-item-subtitle>{{ person.email }}</v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
            <v-list-item>
              <v-list-item-content>
                <v-list-item-title>Telefone</v-list-item-title>
                <v-list-item-subtitle>{{ person.phone }}</v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
            <v-list-item v-for="address in person.addresses" :key="address.id">
              <v-list-item-content>
                <v-list-item-title>{{ capitalize(address.type) }}</v-list-item-title>
                <v-list-item-subtitle>{{ formatAddress(address) }}</v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
          </v-list>
        </v-card-text>
      </v-card>
    </v-container>
  </template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      person: null,
    };
  },
  methods: {
    fetchPersonDetails() {
  axios.get(`/registrations/${this.$route.params.id}`)
    .then(response => {
      this.person = response.data;
      console.log("Detalhes da pessoa carregados:", this.person);
    })
    .catch(error => {
      console.error('Erro ao buscar detalhes da pessoa:', error);
      this.person = null; // Garante que person é nulo em caso de erro
    });
},
    formatAddress(address) {
      return `${address.street}, ${address.number}${address.complement ? ', ' + address.complement : ''} - ${address.neighborhood}, ${address.city} - ${address.state}`;
    },
    capitalize(value) {
      if (!value) return '';
      value = value.toString();
      return value.charAt(0).toUpperCase() + value.slice(1);
    }
  },
  mounted() {
    this.fetchPersonDetails();
  }
}
</script>
