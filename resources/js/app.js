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
import Clientes from './components/Clientes.vue'
import CrearCliente from './components/CrearCliente.vue'
import Productos from './components/Productos.vue'
import CrearEditarProducto from './components/CrearEditarProducto.vue'
import Impuestos from './components/Impuestos.vue'
import CrearEditarImpuesto from './components/CrearEditarImpuesto.vue'
import Proveedores from './components/Proveedores.vue'
import CrearProveedor from './components/CrearProveedor.vue'

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


const routes = [
  { path: '', component: require('./components/ExampleComponent.vue').default },
  { path: '/clientes', component: Clientes },
  { path: '/crear-cliente', component: CrearCliente },
  { path: '/productos', component: Productos },
  { path: '/crear-editar-producto', component: CrearEditarProducto },
  { path: '/impuestos', component: Impuestos },
  { path: '/crear-editar-impuesto', component: CrearEditarImpuesto },
  { path: '/proveedores', component: Proveedores },
  { path: '/crear-proveedor', component: CrearProveedor },

]

const router = new VueRouter({
  routes // short for `routes: routes`
})

export default router;

const app = new Vue({
  el: '#app',
  router
});

