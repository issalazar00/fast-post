/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
Vue.component('pagination', require('laravel-vue-pagination'));

window.Vue = require('vue').default;

import Vue from 'vue'
import VueRouter from 'vue-router'
import Clients from './components/Clients.vue'
import CreateEditClient from './components/CreateEditClient.vue'
import Productos from './components/Productos.vue'
import CreateEditProduct from './components/CreateEditProduct.vue'
import Impuestos from './components/Impuestos.vue'
import CrearEditarImpuesto from './components/CrearEditarImpuesto.vue'

import Categories from './components/Categories.vue'
import CreateEditCategory from './components/CreateEditCategory.vue'

import Suppliers from './components/Suppliers.vue'
import CreateEditSupplier from './components/CreateEditSupplier.vue'

import Orders from './components/Orders'
import DetailsOrder from './components/DetailsOrder'
import CreateEditOrder from './components/CreateEditOrder'

Vue.use(VueRouter)


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
// Vue.component('productos', require('./components/Productos.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// { path: '', component: require('./components/ExampleComponent.vue').default },
const routes = [
  
  { path: '', component: CreateEditOrder },
  { path: '/clients', component: Clients },
  { path: '/create-edit-client', component: CreateEditClient },
  { path: '/productos', component: Productos },
  { path: '/create-edit-product', component: CreateEditProduct },
  { path: '/impuestos', component: Impuestos },
  { path: '/crear-editar-impuesto', component: CrearEditarImpuesto },
  { path: '/suppliers', component: Suppliers },
  { path: '/create-edit-supplier', component: CreateEditSupplier },
  { path: '/categories', component: Categories },
  { path: '/create-edit-category', component: CreateEditCategory },
  { path: '/orders', component: Orders },
  { path: '/details-order', component: DetailsOrder },
  { path: '/create-edit-order', component: CreateEditOrder },

]

const router = new VueRouter({
  routes // short for `routes: routes`
})

export default router;

const app = new Vue({
  el: '#app',
  router
});

