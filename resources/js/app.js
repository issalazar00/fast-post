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

import Login from './components/Login.vue'

import Clientes from './components/CrearCliente.vue'
import CrearCliente from './components/CrearCliente.vue'
import Productos from './components/Productos.vue'
import CrearEditarProducto from './components/CrearEditarProducto.vue'
import Impuestos from './components/Impuestos.vue'
import CrearEditarImpuesto from './components/CrearEditarImpuesto.vue'

import Categories from './components/Categories.vue'
import CreateEditCategory from './components/CreateEditCategory.vue'

import Proveedores from './components/Proveedores.vue'
import CrearProveedor from './components/CrearProveedor.vue'

import Tickets from './components/Tickets'
import DetailsTicket from './components/DetailsTicket'

//Services
import global from './services/global.js';

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
  
  { path: '', component: DetailsTicket },
  { path: '/clientes', component: Clientes },
  { path: '/crear-cliente', component: CrearCliente },
  { path: '/productos', component: Productos },
  { path: '/crear-editar-producto', component: CrearEditarProducto },
  { path: '/impuestos', component: Impuestos },
  { path: '/crear-editar-impuesto', component: CrearEditarImpuesto },
  { path: '/proveedores', component: Proveedores },
  { path: '/crear-proveedor', component: CrearProveedor },
  { path: '/categories', component: Categories },
  { path: '/create-edit-category', component: CreateEditCategory },
  { path: '/tickets', component: Tickets },//Home
  { path: '/details-ticket', component: DetailsTicket },
  { path: '/login', name:'Login', component: Login },
  { path: '**', component: Login },

]

const router = new VueRouter({
  routes // short for `routes: routes`
})



export default router;

router.beforeEach(async (to, from, next) => {
  // redirect to login if not authenticated in and trying to access a restricted route
  const publicRoutes = ["Login"];
  const authRequired = !publicRoutes.includes(to.name);
  let isAuthenticated = false;

  try {
    isAuthenticated =
        localStorage.getItem("token") &&
        localStorage.getItem("user") &&
        JSON.parse(localStorage.getItem("user"))
        ? true
        : false;

        user = null;
        token = 2323;
      
  } catch (e) {
    isAuthenticated 
  }
  if (authRequired && !isAuthenticated) {
    return next({ name: "Login", query: { redirect: to.fullPath } });
  }
  next();

});


const app = new Vue({
  el: '#app',
  data: {
    user: {},
    token: '',
  },
  watch: {
    $route(to, from){
      this.user = JSON.parse(localStorage.getItem("user")) ;
      this.token = localStorage.getItem("token");
    }
  },
  router,
  created(){

  },
  mounted(){
    this.user = JSON.parse(localStorage.getItem("user")) ;
    this.token = localStorage.getItem("token");
  },
  methods:{
    logout(){
      localStorage.clear();
      this.$router.push('/login');
    }
  }
});

