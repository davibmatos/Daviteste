import { createRouter, createWebHistory } from 'vue-router';
import SearchPage from '../components/SearchPage.vue';
import CreateRecord from '../components/CreateRecord.vue';
import AddAddressForm from '../components/AddAddressForm.vue';

const routes = [
  {
    path: '/',
    name: 'SearchPage',
    component: SearchPage
  },
  {
    path: '/create',
    name: 'CreateRecord',
    component: CreateRecord
  },
  {
    path: '/add-address/:registrationId',
    name: 'AddAddress',
    component: AddAddressForm,
    props: true,
  },
  {
    path: '/detail/:id',
    name: 'DetailPage',
    component: () => import('@/components/DetailPage.vue')
  }
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
});

export default router;
