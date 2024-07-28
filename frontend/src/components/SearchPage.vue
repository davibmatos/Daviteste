<template>
  <v-container>
    <v-row>
      <v-col cols="12" md="3">
        <v-text-field v-model="search.cpf" label="CPF" outlined dense></v-text-field>
      </v-col>
      <v-col cols="12" md="3">
        <v-text-field v-model="search.name" label="Nome" outlined dense></v-text-field>
      </v-col>
      <v-col cols="12" md="3">
        <v-text-field v-model="search.phone" label="Telefone" outlined dense></v-text-field>
      </v-col>
      <v-col cols="12" md="3" class="d-flex align-center justify-space-between">
        <v-btn color="primary" class="mr-2" @click="fetchResults">Buscar</v-btn>
        <v-btn color="secondary" @click="$router.push('/create')">+ Adicionar Registro</v-btn>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12">
        <v-data-table
          :headers="headers"
          :items="records"
          item-key="id"
          class="elevation-1"
          :footer-props="{
            'items-per-page-options': [5, 10, 15, -1],
            'items-per-page-text': 'Itens por página',
          }"
        >
          <template v-slot:item="{ item }">
            <tr @click="openDetails(item)">
              <td>{{ item.cpf }}</td>
              <td>{{ item.name }}</td>
              <td>{{ item.phone }}</td>
              <td>{{ formatAddress(item.addresses, 'residential') }}</td>
              <td>{{ formatAddress(item.addresses, 'commercial') }}</td>
              <td>
                <v-btn color="info" @click.stop="openDetails(item)">Detalhes</v-btn>
                <v-btn color="success" @click.stop="navigateToAddAddress(item)">Adicionar Endereço</v-btn>
              </td>
            </tr>
          </template>
        </v-data-table>
      </v-col>
    </v-row>
    <v-alert v-if="errorMessage" type="error">{{ errorMessage }}</v-alert>
  </v-container>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      search: {
        cpf: '',
        name: '',
        phone: ''
      },
      records: [],
      errorMessage: '',
      headers: [
        { text: 'CPF', value: 'cpf' },
        { text: 'Nome', value: 'name' },
        { text: 'Telefone', value: 'phone' },
        { text: 'Endereço Residencial', value: 'residential_address', sortable: false },
        { text: 'Endereço Comercial', value: 'commercial_address', sortable: false },
        { text: 'Ações', value: 'actions', sortable: false },
      ],
    };
  },
  methods: {
    fetchResults() {
      axios.get('/registrations', { params: this.search })
        .then(response => {
          this.records = response.data.data;
          this.errorMessage = '';
        })
        .catch(error => {
          this.errorMessage = 'Erro ao buscar registros: ' + (error.response ? error.response.data.message : error.message);
        });
    },
    formatAddress(addresses, type) {
      const address = addresses.find(addr => addr.type === type);
      return address ? `${address.street}, ${address.number}${address.complement ? ', ' + address.complement : ''} - ${address.neighborhood}, ${address.city} - ${address.state}` : 'N/A';
    },
    openDetails(item) {
      this.$router.push({ name: 'DetailPage', params: { id: item.id } });
    },
    navigateToAddAddress(item) {
  this.$router.push({ name: 'AddAddress', params: { registrationId: item.id } });
}
  },
  mounted() {
    this.fetchResults();
  }
}
</script>
