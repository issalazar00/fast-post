<template>
  <div class="container">
    <div class="page-header text-center mb-4">
      <img :src="companyLogo" alt="company-image" style="max-height: 100px" />
    </div>
    <div class="row justify-content-center">
      <div class="position-fixed top-0 right-0 p-3" style="z-index: 5">
        <div
          class="toast fade hide border border-danger"
          role="alert"
          id="no-results"
          aria-live="assertive"
          aria-atomic="true"
          data-delay="3000"
        >
          <div class="toast-header">
            <strong class="mr-auto h5">Advertencia</strong>
            <button
              type="button"
              class="ml-2 mb-1 close"
              data-dismiss="toast"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="toast-body">No se ha encontrado coincidencias</div>
        </div>
      </div>
      <div class="row w-100 position-sticky sticky-top mb-2 bg-light py-1" style="top:0.5rem">
        <div class="input-group col-6">
          <input
            type="text"
            class="form-control"
            placeholder="Código de barras"
            aria-label=" with two button addons"
            aria-describedby="button-add-product"
            v-model="filters.product"
            autofocus
            @keypress.enter="searchProduct()"
          />
          <div class="input-group-append" id="button-add-product">
            <button
              class="btn btn-outline-secondary"
              type="button"
              @click="searchProduct()"
            >
              Añadir Producto
            </button>
            <button
              class="btn btn-outline-secondary"
              type="button"
              data-toggle="modal"
              data-target="#addProductModal"
            >
              <i class="bi bi-card-checklist"></i>
            </button>
          </div>
        </div>
        <div class="input-group col-6">
          <input
            type="text"
            class="form-control"
            :placeholder="order.client"
            aria-label=" with two button addons"
            aria-describedby="button-addon4"
            v-model="filters.client"
            @keypress.enter="searchClient()"
          />
          <div class="input-group-append" id="button-addon4">
            <button
              class="btn btn-outline-secondary"
              type="button"
              @click="searchClient()"
            >
              Añadir Cliente
            </button>
            <button
              class="btn btn-outline-secondary"
              type="button"
              data-toggle="modal"
              data-target="#addClientModal"
            >
              <i class="bi bi-person-lines-fill"></i>
            </button>
          </div>
        </div>
      </div>

      <section class="w-100">
        <div>
          <table
            class="
              table table-sm table-responsive-sm table-bordered table-hover
            "
          >
            <thead class="bg-secondary text-white position-sticky sticky-top" style="top:4rem">
              <tr>
                <th>#</th>
                <th>Código</th>
                <th>Producto</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Descuento %</th>
                <th>Descuento $</th>
                <th>Total</th>
                <th></th>
              </tr>
            </thead>
            <tbody v-if="productsOrderList.length > 0">
              <tr v-for="(p, index) in productsOrderList" :key="p.id">
                <th scope="row">{{ p.product_id }}</th>
                <td>{{ p.barcode }}</td>
                <td>{{ p.product }}</td>

                <td>
                  <input
                    type="number"
                    name="price"
                    id="price"
                    step="any"
                    placeholder="Cantidad"
                    v-model="p.price_tax_inc"
                    readonly
                    class="form-control form-control-sm"
                  />
                </td>
                <td>
                  <input
                    type="number"
                    name="quantity"
                    id="quantity"
                    step="2"
                    placeholder="Cantidad"
                    class="form-control form-control-sm"
                    v-model="p.quantity"
                  />
                </td>
                <td>
                  <input
                    type="number"
                    name="discount_percentage"
                    id="discount_percentage"
                    step="any"
                    placeholder="Descuento"
                    class="form-control form-control-sm"
                    v-model="p.discount_percentage"
                  />
                </td>
                <td>
                  <input
                    type="number"
                    class="form-control form-control-sm"
                    name="discount_price"
                    id="discount_price"
                    step="2"
                    placeholder="Descuento"
                    disabled
                    :value="
                      (p.discount_price = (
                        p.quantity *
                        p.price_tax_inc *
                        (p.discount_percentage / 100)
                      ).toFixed(2))
                    "
                    readonly
                  />
                </td>
                <td>
                  $
                  {{
                    (p.price_tax_inc_total = (
                      p.quantity * p.price_tax_inc -
                      p.quantity *
                        p.price_tax_inc *
                        (p.discount_percentage / 100)
                    ).toFixed(2))
                  }}
                </td>
                <td>
                  <button
                    class="btn text-danger"
                    @click="removeProduct(index, p.id)"
                  >
                    <i class="bi bi-trash"></i>
                  </button>
                </td>
              </tr>
            </tbody>
            <tbody v-else>
              <tr>
                <td colspan="8">No se han añadido productos</td>
              </tr>
            </tbody>
          </table>
          <div class="col-6 offset-md-6">
            <section class="card">
              <div>
                <table class="table table-sm table-primary text-right">
                  <tr>
                    <th colspan="7">Subtotal:</th>
                    <th>
                      $ {{ (order.total_tax_exc = total_tax_exc).toFixed(2) }}
                    </th>
                  </tr>
                  <tr>
                    <th colspan="7">Descuento:</th>
                    <th>
                      $ {{ (order.total_discount = total_discount).toFixed(2) }}
                    </th>
                  </tr>
                  <tr class="bg-primary h5 text-white">
                    <th colspan="7">Total:</th>
                    <th>
                      $ {{ (order.total_tax_inc = total_tax_inc).toFixed(2) }}
                    </th>
                  </tr>
                  <tr>
                    <th colspan="7">Efectivo:</th>
                    <th>
                      <input
                        type="number"
                        value="0"
                        step="any"
                        v-model="order.payment"
                      />
                    </th>
                  </tr>
                  <tr class="bg-secondary text-white">
                    <th colspan="7">Cambio:</th>
                    <th>
                      <input
                        type="text"
                        :value="payment_return"
                        readonly
                        disabled
                      />
                    </th>
                  </tr>
                </table>
              </div>
            </section>
            <div class="text-right">
              <button
                type="button"
                class="btn btn-outline-primary btn-block"
                @click="createOrUpdateOrder(2)"
              >
                <i class="bi bi-receipt"></i> Facturar
              </button>
              <button
                type="button"
                class="btn btn-outline-primary btn-block"
                @click="createOrUpdateOrder(1)"
              >
                <i class="bi bi-receipt"></i> Suspender
              </button>

              <button
                type="button"
                class="btn btn-outline-primary btn-block"
                @click="createOrUpdateOrder(3)"
              >
                <i class="bi bi-receipt"></i> Cotizar
              </button>
              <router-link
                to="/orders"
                type="button"
                class="btn btn-outline-secondary btn-block"
                v-if="order_id != 0"
              >
                <i class="bi bi-receipt"></i> Cancelar
              </router-link>
            </div>
          </div>
        </div>
      </section>
    </div>
    <div class="fixed-bottom fixed-price-right p-2 text-uppercase">
      <table class="table table-borderless">
        <tr class="h1 text-white bg-primary">
          <td class="text-right">Total</td>
          <td>$ {{ (order.total_tax_inc = total_tax_inc).toFixed(2) }}</td>
        </tr>
        <tr class="table-primary h3">
          <td>Efectivo</td>
          <td>
            <input
              type="number"
              class="form-control form-control-lg"
              :value="order.payment"
              readonly
            />
          </td>
        </tr>
        <tr class="table-primary h3">
          <td>Cambio:</td>
          <td>
            {{ payment_return }}
          </td>
        </tr>
      </table>
    
    </div>
    <add-product @add-product="addProduct($event)" />
    <add-client @add-client="addClient($event)" />
  </div>
</template>

<script>
import AddProduct from "./AddProduct.vue";
import AddClient from "./AddClient.vue";
export default {
  components: { AddProduct, AddClient },
  props: ["order_id"],
  data() {
    return {
      // add product or client with keyup
      filters: {
        product: "",
        client: "",
      },
      productsOrderList: [],

      order: {
        id_client: 1,
        client: "Sin Cliente",
        state: 1,
        total_tax_inc: 0.0,
        total_tax_exc: 0.0,
        total_discount: 0.0,
        productsOrder: [],
        payment: 0,
      },
      companyLogo: "",
    };
  },
  computed: {
    total_tax_exc: function () {
      var total = 0.0;
      this.productsOrderList.forEach(
        (product) =>
          (total += parseFloat(product.price_tax_exc * product.quantity))
      );
      return total;
    },
    total_discount: function () {
      var total = 0.0;
      this.productsOrderList.forEach((product) => {
        (total += parseFloat(product.discount_price)), product.quantity;
      });
      return total;
    },
    total_tax_inc: function () {
      var total = 0.0;
      this.productsOrderList.forEach((product) => {
        (total += parseFloat(product.price_tax_inc_total)), product.quantity;
      });
      return total;
    },
    payment_return: function () {
      var value = 0.0;
      if (this.order.payment > 0) {
        value = (this.order.payment - this.total_tax_inc).toFixed(2);
      }
      return value;
    },
  },
  created() {
    axios
      .get(`api/company-logo`, this.$root.config)
      .then((response) => (this.companyLogo = response.data.logo))
      .catch(function (error) {
        console.log(error);
      });
  },
  methods: {
    listItemsOrder() {
      if (this.order_id == 0) {
        return false;
      }

      let me = this;

      axios
        .get(`api/orders/${me.order_id}`, this.$root.config)
        .then(function (response) {
          me.order.id_client = response.data.order_information.client_id;
          me.order.client = response.data.order_information.client.name;

          me.productsOrderList = response.data.order_details;
        });
    },
    searchProduct() {
      let me = this;
      if (me.filters.product == "") {
        return false;
      }
      var url = "api/products/search-product?product=" + me.filters.product;
      axios
        .post(url, null, this.$root.config)
        .then(function (response) {
          var new_product = response.data.products;
          if (!new_product) {
            $("#no-results").toast("show");
          } else {
            me.addProduct(new_product);
            me.filters.product == "";
          }
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    addProduct(new_product) {
      let me = this;
      let result = false;
      // Verifica si el producto existe en la lista
      me.productsOrderList.filter((prod) => {
        if (new_product.barcode == prod.barcode) {
          result = true;
          if (result) {
            // Añade cantidad
            prod.quantity += 1;
          }
        }
      });

      if (!result) {
        // Sino, lo añade al array
        me.productsOrderList.push({
          product_id: new_product.id,
          barcode: new_product.barcode,
          discount_percentage: 0,
          discount_price: 0,
          quantity: 1,
          price_tax_inc: new_product.sale_price_tax_inc,
          price_tax_exc: new_product.sale_price_tax_exc,
          product: new_product.product,
        });
      }
    },
    removeProduct(index, detail_id = null) {
      this.productsOrderList.splice(index, 1);
      if (detail_id != null || detail_id != 0) {
        axios.delete(`api/order-details/${detail_id}`, this.$root.config);
      }
    },
    searchClient() {
      let me = this;
      if (me.filters.client == "") {
        return false;
      }
      var url = "api/clients/search-client?client=" + me.filters.client;
      axios
        .post(url, null, me.$root.config)
        .then(function (response) {
          var new_client = response.data;
          if (!new_client) {
            $("#no-results").toast("show");
          } else {
            me.addClient(new_client);
          }
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    addClient(client) {
      let me = this;
      me.order.id_client = client.id;
      me.order.client = client.name;
      me.filters.client = client.name;
    },

    createOrUpdateOrder(state_order) {
      this.order.state = state_order;
      if (this.productsOrderList.length > 0) {
        this.order.productsOrder = this.productsOrderList;
        if (this.order_id != 0) {
          axios
            .put(`api/orders/${this.order_id}`, this.order, this.$root.config)
            .then(() => this.$router.replace("/orders"));
        } else {
          axios
            .post(`api/orders`, this.order, this.$root.config)
            .then(() => this.$router.replace("/orders"));
        }
      } else {
        alert("No hay productos en la orden");
      }
    },
  },
  mounted() {
    $("#no-results").toast("hide");
    if (this.order_id != null || this.order_id != 0) {
      this.listItemsOrder();
    }
  },
};
</script>