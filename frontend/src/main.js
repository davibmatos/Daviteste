import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import vuetify from './plugins/vuetify';
import VueTheMask from 'vue-the-mask';
import axios from 'axios';

// Configurações iniciais do Axios
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.post['Content-Type'] = 'application/json';
axios.defaults.baseURL = 'http://127.0.0.1:8000';
axios.defaults.withCredentials = true;  // Importante para o envio de cookies

// Obtendo o CSRF token do backend
axios.get('/csrf-cookie').then(response => {
    console.log('CSRF token has been set.');
    console.log('Received CSRF token:', response.data.csrf_token);  // Log do token CSRF recebido

    // Configurar o token CSRF para todas as requisições após obtê-lo
    axios.interceptors.request.use(config => {
        const cookieString = document.cookie;
        const tokenCookie = cookieString.split('; ').find(row => row.startsWith('XSRF-TOKEN='));
        if (tokenCookie) {
            const token = tokenCookie.split('=')[1];
            config.headers['X-CSRF-TOKEN'] = decodeURIComponent(token);
            console.log('CSRF token dynamically added to request:', token);
        } else {
            console.error('XSRF-TOKEN cookie not found');
        }
        return config;
    });

    // Criar e montar a aplicação Vue
    const app = createApp(App);
    app.use(router);
    app.use(vuetify);
    app.use(VueTheMask);
    app.mount('#app');
}).catch(error => {
    console.error('Error fetching CSRF token:', error);
});
