<template>
  <v-container>
    <v-form ref="addressForm" v-model="valid" lazy-validation>
      <v-row>
        <v-col cols="12" md="6">
          <v-select
            v-model="address.type"
            :items="['residential', 'commercial']"
            label="Tipo de Endereço"
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
            v-model="address.city"
            label="Cidade"
            :rules="[rules.required]"
            outlined
            dense
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6">
          <v-text-field
            v-model="address.state"
            label="Estado"
            :rules="[rules.required]"
            outlined
            dense
          ></v-text-field>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12" class="d-flex align-end justify-end">
          <v-btn :disabled="!valid" color="success" @click="submitAddress">Salvar Endereço</v-btn>
        </v-col>
      </v-row>
      <v-alert v-if="errorMessage" type="error">{{ errorMessage }}</v-alert>
    </v-form>
  </v-container>
</template>

<script>
import axios from 'axios';
import { useVuelidate } from '@vuelidate/core';
import { required } from '@vuelidate/validators';

export default {
  props: {
    registrationId: Number
  },
  setup() {
    const v$ = useVuelidate();
    return { v$ };
  },
  data() {
    return {
      address: {
        type: '',
        cep: '',
        street: '',
        number: '',
        complement: '',
        neighborhood: '',
        city: '',
        state: '',
      },
      errorMessage: '',
      rules: {
        required,
      }
    };
  },
  methods: {
    fetchAddress() {
      // Implementação da busca de endereço por CEP
    },
    submitAddress() {
      axios.post(`/addresses/${this.registrationId}`, this.address)
        .then(response => {
          this.$emit('address-added', response.data);
        })
        .catch(error => {
          this.errorMessage = error.response.data.message;
        });
    }
  }
};
</script>
