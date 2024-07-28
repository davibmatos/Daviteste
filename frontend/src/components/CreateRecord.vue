<template>
  <v-container>
    <v-form ref="form" v-model="valid" lazy-validation>
      <v-row>
        <v-col cols="12" md="6">
          <v-text-field
            v-model="record.name"
            label="Nome"
            :rules="[rules.required]"
            outlined
            dense
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6">
          <v-text-field
            v-model="record.social_name"
            label="Nome Social"
            outlined
            dense
          ></v-text-field>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12" md="6">
          <v-text-field
            v-model="record.cpf"
            label="CPF"
            :rules="[rules.required, rules.cpf]"
            outlined
            dense
            v-mask="'###.###.###-##'"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6">
          <v-text-field
            v-model="record.phone"
            label="Telefone"
            :rules="[rules.required, rules.phone]"
            outlined
            dense
            v-mask="'(##) #####-####'"
          ></v-text-field>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12" md="6">
          <v-text-field
            v-model="record.father_name"
            label="Nome do Pai"
            outlined
            dense
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6">
          <v-text-field
            v-model="record.mother_name"
            label="Nome da Mãe"
            outlined
            dense
          ></v-text-field>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12" md="6">
          <v-text-field
            v-model="record.email"
            label="Email"
            :rules="[rules.required, rules.email]"
            outlined
            dense
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6" class="d-flex align-end justify-end">
          <v-btn :disabled="!valid" color="success" @click="submitData">Salvar</v-btn>
        </v-col>
      </v-row>
      <v-alert v-if="errorMessage" type="error">{{ errorMessage }}</v-alert>
    </v-form>

    <v-form ref="addressForm" v-if="showAddressForm" v-model="addressValid" lazy-validation>
      <v-divider class="my-4"></v-divider>
      <h3>Adicionar Endereço</h3>
      <v-row>
        <v-col cols="12" md="6">
          <v-select
            v-model="address.type"
            :items="['residential', 'commercial']"
            label="Tipo"
            :rules="[rules.required]"
            outlined
            dense
          ></v-select>
        </v-col>
        <v-col cols="12" md="6">
          <v-text-field
            v-model="address.cep"
            label="CEP"
            :rules="[rules.required]"
            outlined
            dense
            @blur="fetchAddress"
          ></v-text-field>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12" md="6">
          <v-text-field
            v-model="address.street"
            label="Rua"
            :rules="[rules.required]"
            outlined
            dense
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6">
          <v-text-field
            v-model="address.number"
            label="Número"
            :rules="[rules.required]"
            outlined
            dense
          ></v-text-field>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12" md="6">
          <v-text-field
            v-model="address.complement"
            label="Complemento"
            outlined
            dense
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6">
          <v-text-field
            v-model="address.neighborhood"
            label="Bairro"
            :rules="[rules.required]"
            outlined
            dense
          ></v-text-field>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12" md="6">
          <v-text-field
            v-model="address.state"
            label="Estado"
            :rules="[rules.required]"
            outlined
            dense
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6">
          <v-text-field
            v-model="address.city"
            label="Cidade"
            :rules="[rules.required]"
            outlined
            dense
          ></v-text-field>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12" class="d-flex align-end justify-end">
          <v-btn :disabled="!addressValid" color="success" @click="submitAddress">Salvar Endereço</v-btn>
        </v-col>
      </v-row>
      <v-alert v-if="addressErrorMessage" type="error">{{ addressErrorMessage }}</v-alert>
    </v-form>
  </v-container>
</template>

<script>
import { useVuelidate } from '@vuelidate/core';
import { VueTheMask } from 'vue-the-mask';
import axios from 'axios';

export default {
  name: 'CreateRecord',
  directives: {
    mask: VueTheMask
  },
  data() {
    return {
      valid: true,
      addressValid: true,
      showAddressForm: false,
      record: {
        name: '',
        social_name: '',
        cpf: '',
        father_name: '',
        mother_name: '',
        phone: '',
        email: ''
      },
      address: {
        registration_id: null,
        type: '',
        cep: '',
        street: '',
        number: '',
        complement: '',
        neighborhood: '',
        state: '',
        city: ''
      },
      errorMessage: '',
      addressErrorMessage: '',
      rules: {
        required: value => !!value || 'Campo obrigatório.',
        email: value => /.+@.+\..+/.test(value) || 'E-mail deve ser válido.',
        cpf: value => /^\d{3}\.\d{3}\.\d{3}-\d{2}$/.test(value) || 'CPF deve ser válido.',
        phone: value => /^\(\d{2}\) \d{4,5}-\d{4}$/.test(value) || 'Telefone deve ser válido.',
      }
    };
  },
  setup() {
    return { v$: useVuelidate() };
  },
  methods: {
    submitData() {
      axios.post('/registrations', this.record)
        .then(response => {
          this.showAddressForm = true;
          this.address.registration_id = response.data.data.id;
        })
        .catch(error => {
          if (error.response) {
            if (error.response.status === 400) {
              this.errorMessage = error.response.data.message || 'Erro ao submeter os dados.';
            } else if (error.response.status === 422) {
              this.errorMessage = Object.values(error.response.data.errors).join(' ');
            } else {
              this.errorMessage = 'Erro desconhecido. Tente novamente mais tarde.';
            }
          } else if (error.request) {
            this.errorMessage = 'Nenhuma resposta recebida do servidor.';
          } else {
            this.errorMessage = 'Erro ao configurar a requisição: ' + error.message;
          }
        });
    },
    submitAddress() {
      axios.post('/addresses', this.address)
        .then(() => {
          this.$router.push({ name: 'SearchPage' });
        })
        .catch(error => {
          if (error.response) {
            if (error.response.status === 400) {
              this.addressErrorMessage = error.response.data.message || 'Erro ao submeter os dados.';
            } else if (error.response.status === 422) {
              this.addressErrorMessage = Object.values(error.response.data.errors).join(' ');
            } else {
              this.addressErrorMessage = 'Erro desconhecido. Tente novamente mais tarde.';
            }
          } else if (error.request) {
            this.addressErrorMessage = 'Nenhuma resposta recebida do servidor.';
          } else {
            this.addressErrorMessage = 'Erro ao configurar a requisição: ' + error.message;
          }
        });
    },
    fetchAddress() {
      if (this.address.cep.length === 8) {
        axios.get(`/proxy/viacep/${this.address.cep}`)
          .then(response => {
            if (response.data.erro) {
              this.addressErrorMessage = 'CEP não encontrado.';
            } else {
              this.address.street = response.data.logradouro;
              this.address.neighborhood = response.data.bairro;
              this.address.city = response.data.localidade;
              this.address.state = response.data.uf;
              this.addressErrorMessage = '';
            }
          })
          .catch(() => {
            this.addressErrorMessage = 'Erro ao buscar o endereço. Verifique o CEP e tente novamente.';
          });
      } else {
        this.addressErrorMessage = 'CEP deve conter 8 dígitos.';
      }
    }
  }
};
</script>
