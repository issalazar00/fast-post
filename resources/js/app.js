/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
Vue.component('pagination', require('laravel-vue-pagination'));

import Vue from 'vue'
import VueRouter from 'vue-router'
import { VueSpinners } from '@saeris/vue-spinners'

import Login from './components/Login.vue'
import NoFound from './components/NoFound.vue';
import Clients from './components/Client/Clients.vue'
import CreateEditClient from './components/Client/CreateEditClient.vue'

import Products from './components/Products.vue'
import CreateEditProduct from './components/CreateEditProduct.vue'

import Taxes from './components/Taxes.vue'
import CreateEditTax from './components/CreateEditTax.vue'

import Categories from './components/Categories.vue'
import CreateEditCategory from './components/CreateEditCategory.vue'

import Brands from './components/Brands.vue'
import CreateEditBrand from './components/CreateEditBrand.vue'

import Suppliers from './components/Suppliers.vue'
import CreateEditSupplier from './components/CreateEditSupplier.vue'

import Orders from './components/Order/Orders.vue'
import DetailsOrder from './components/Order/DetailsOrder.vue'
import CreateEditOrder from './components/Order/CreateEditOrder.vue'
import AddClient from './components/Order/AddClient.vue'
import AddProduct from './components/Order/AddProduct'
import ImportProducts from './components/ImportProducts'

import Roles from './components/Roles.vue';
import Users from './components/Users.vue';

//Services
import global from './services/global.js';

Vue.use(VueRouter)
Vue.use(VueSpinners)

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
// Vue.component('productos', require('./components/Products.vue').default);

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
  { path: '/products', component: Products },
  { path: '/create-edit-product', component: CreateEditProduct },
  { path: '/taxes', component: Taxes, alias: "tax.index"},
  { path: '/create-edit-tax', component: CreateEditTax },
  { path: '/suppliers', component: Suppliers},
  { path: '/create-edit-supplier', component: CreateEditSupplier },
  { path: '/categories', component: Categories, alias: "category.index" },
  { path: '/create-edit-category', component: CreateEditCategory },
  { path: '/brands', component: Brands },
  { path: '/create-edit-brand', component: CreateEditBrand },
  { path: '/orders', component: Orders },
  { path: '/orders/:order_id/details-order', component: DetailsOrder, props: true, name: 'details-order' },
  { path: '/create-edit-order/:order_id', component: CreateEditOrder, props: true, name : 'create-edit-order' },
  { path: '/login', name: 'Login', component: Login },
  { path: '/roles', name: 'Roles', component: Roles, alias: "rol.index"},
  { path: '/users', name: 'Users', component: Users, alias: "user.index"},
  { path: '**', name: 'NoFound', component: NoFound },

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
  } catch (e) {
    isAuthenticated
  }
  if (authRequired && !isAuthenticated) {
    return next({ name: "Login", query: { redirect: to.fullPath } });
  }

  if (isAuthenticated) {

    let alias = to.matched[0].alias;
    if (alias != "") {
      if (!global.validatePermission(undefined, alias)) {
        return next({ name: "NoFound" });
      }
    }
  }
  next();

});


const app = new Vue({
  el: '#app',
  data: {
    user: Object,
    token: String,
    permissions: [],
    config: Object({
      headers: {
        Authorization: "",
      },
    })
  },
  watch: {
    $route(to, from) {
      this.assignDataRequired();
    }
  },
  router,
  created() {
    this.assignDataRequired();
  },
  methods: {
    assignDataRequired() {
      this.user = JSON.parse(localStorage.getItem("user"));
      this.token = localStorage.getItem("token");

      if (this.user) {
        this.permissions = this.user.permissions;
      }

      this.config.headers.Authorization = "Bearer " + this.token;
    },
    logout() {
      this.user = {};
      this.token = "";
      this.permissions = [];
      this.config.headers.Authorization = "";
      localStorage.clear();
      this.$router.push('/login');
    },
    validatePermission(permission) {
      return global.validatePermission(this.permissions, permission);
    }
  }
});

