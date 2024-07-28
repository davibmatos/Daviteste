<template>
  <div>
    <button @click="testConnection">Test Backend Connection</button>
    <p v-if="message">{{ message }}</p>
  </div>
</template>

<script>
import http from '../http';

export default {
  data() {
    return {
      message: ''
    };
  },
  methods: {
    testConnection() {
      http.get('/test')
        .then(response => {
          this.message = response.data.message;
        })
        .catch(error => {
          console.error('Error:', error);
        });
    }
  }
}
</script>
