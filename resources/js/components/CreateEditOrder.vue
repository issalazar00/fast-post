<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="header col-12">
        <h3>Crear Ticket</h3>
      </div>
      <hr class="hr" />
      <div class="position-fixed top-0 right-0 p-3" style="z-index: 5">
        <div
          class="toast fade hide border border-danger"
          role="alert"
          id="no-products"
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
      <div class="row">
        <div class="input-group">
          <input
            type="text"
            class="form-control"
            placeholder="Código de barras"
            aria-label=" with two button addons"
            aria-describedby="button-add-product"
            v-model="filters.product"
            @keypress.enter="searchProduct()"
          />
          <div class="input-group-append" id="button-add-product">
            <button class="btn btn-outline-secondary" type="button">
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
        <div class="input-group">
          <input
            type="text"
            class="form-control"
            :placeholder="order.id_client != 0 ? 'Isabella' : 'No aplica'"
            aria-label=" with two button addons"
            aria-describedby="button-addon4"
          />
          <div class="input-group-append" id="button-addon4">
            <button class="btn btn-outline-secondary" type="button">
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

      <section>
        <div>
          <table
            class="
              table table-sm table-responsive-sm table-bordered table-hover
            "
          >
            <thead>
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
              <tr v-for="p in productsOrderList" :key="p.id">
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
                  />
                </td>
                <td>
                  <input
                    type="number"
                    name="quantity"
                    id="quantity"
                    step="any"
                    placeholder="Cantidad"
                    v-model="p.qty"
                  />
                </td>
                <td>
                  <input
                    type="number"
                    name="discount_percentage"
                    id="discount_percentage"
                    step="any"
                    placeholder="Descuento"
                    v-model="p.discount_percentage"
                  />
                </td>
                <td>
                  <input
                    type="number"
                    name="discount_price"
                    id="discount_price"
                    step="2"
                    placeholder="Descuento"
                    disabled
                    :value="
                      (p.discount_price = (
                        p.qty *
                        p.price_tax_inc *
                        (p.discount_percentage / 100)
                      ).toFixed(2))
                    "
                    readonly
                  />
                </td>
                <td>
                 $ {{
                    (p.price_tax_inc_total = (
                      p.qty * p.price_tax_inc -
                      p.qty * p.price_tax_inc * (p.discount_percentage / 100)
                    ).toFixed(2))
                  }}
                </td>
                <td>
                  <button class="btn"><i class="bi bi-trash"></i></button>
                </td>
              </tr>
              <tr>
                <th colspan="7">Subtotal:</th>
                <th>{{ (order.total_tax_exc = total_tax_exc) }}</th>
              </tr>
              <tr>
                <th colspan="7">Descuento:</th>
                <th>{{ (order.total_discount = total_discount) }}</th>
              </tr>

              <tr>
                <th colspan="7">Total:</th>
                <th>{{ (order.total_tax_inc = total_tax_inc) }}</th>
              </tr>
            </tbody>
            <tbody v-else>
              <tr>
                <td>No se han añadido productos</td>
              </tr>
            </tbody>
          </table>
          <div class="text-right">
            <button type="button" class="btn btn-outline-secondary btn-block">
              <i class="bi bi-receipt"></i> Cancelar
            </button>
            <button type="button" class="btn btn-outline-primary btn-block">
              <i class="bi bi-receipt"></i> Crear Orden
            </button>
          </div>
        </div>
      </section>
    </div>
    <add-product />
    <add-client />
  </div>
</template>

<script>
import AddProduct from "./AddProduct.vue";
import AddClient from "./AddClient.vue";
export default {
  components: { AddProduct, AddClient },
  data() {
    return {
      // add product or client with keyup
      filters: {
        product: "",
        client: "",
      },
      productsOrderList: [],

      order: {
        id_client: 0,
        total_tax_inc: 0.0,
        total_tax_exc: 0.0,
        total_discount: 0.0, 
        productsOrder: [],
      },
    };
  },
  computed: {
    total_tax_inc: function () {
      var result = 0.0;
      this.productsOrderList.forEach(
        (product) => (result += parseFloat(product.price_tax_inc_total))
      );
      return result;
    },
    total_tax_exc: function () {
      var result = 0.0;
      this.productsOrderList.forEach(
        (product) => (result += parseFloat(product.price_tax_exc * product.qty))
      );
      return result;
    },
    total_discount: function () {
      var result = 0.0;
      this.productsOrderList.forEach(
        (product) => (result += parseFloat(product.discount_price))
      );
      return result;
    },
  },
  methods: {
    searchProduct() {
      let me = this;
      if (me.filters.product == "") {
        return false;
      }
      var url = "api/products/searchProduct?barcode=" + me.filters.product;
      axios
        .post(url)
        .then(function (response) {
          var new_product = response.data.products;
          if (!new_product) {
            $("#no-products").toast("show");
          } else {
            me.addProduct(new_product);
          }
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    addProduct(new_product) {
      let me = this;
      var result = false;
      // Verifica si el producto existe en la lista
      me.productsOrderList.filter((product) => {
        if (product.barcode === new_product.barcode) {
          result = true;
        }
        if (result) {
          // Añade cantidad
          product.qty += 1;
        }
      });

      if (!result) {
        // Sino, lo añade al array
        me.productsOrderList.push({
          product_id: new_product.id,
          barcode: new_product.barcode,
          discount_percentage: 0,
          qty: 1,
          price_tax_inc: new_product.sale_price_tax_inc,
          price_tax_exc: new_product.sale_price_tax_exc,
          product: new_product.product,
        });
      }
    },
    searchClient() {},
  },
  mounted() {
    $("#no-products").toast("hide");
  },
};
</script>