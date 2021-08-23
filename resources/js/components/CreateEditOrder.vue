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
            placeholder="Código de barras | Nombre de product"
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
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Descuento %</th>
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
                    name="price"
                    id="price"
                    step="any"
                    placeholder="Cantidad"
                    v-model="p.price"
                    readonly
                  />
                </td>
                <td>
                  <input
                    type="number"
                    name="discount"
                    id="discount"
                    step="any"
                    placeholder="Descuento"
                    v-model="p.discount"
                  />
                </td>
                <td>
                  {{
                    (
                      p.qty * p.price -
                      p.qty * p.price * (p.discount / 100)
                    ).toFixed(2)
                  }}
                </td>
                <td>
                  <button class="btn"><i class="bi bi-trash"></i></button>
                </td>
              </tr>
              <tr>
                <th colspan="6">Subtotal:</th>
                <th>$4000</th>
              </tr>
              <tr>
                <th colspan="5">Descuento:</th>
                <th>10%</th>
                <th>$400</th>
              </tr>
              <tr>
                <th colspan="5">Misc:</th>
                <th>10%</th>
                <th>$400</th>
              </tr>
              <tr>
                <th colspan="6">Total:</th>
                <th>$4000</th>
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
      // add product or client keyup
      filters: {
        product: "",
        client: "",
      },
      productsOrderList: [],

      order: {
        id_client: 0,
        productsOrder: [],
      },
    };
  },

  methods: {
    searchProduct() {
      let me = this;
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
      me.productsOrderList.filter((product) => {
        if (product.barcode === new_product.barcode) {
          result = true;
        }
        if (result) {
          product.qty += 1;
        }
      });

      if (!result) {
        me.productsOrderList.push({
          product_id: new_product.id,
          barcode: new_product.barcode,
          discount: 0,
          qty: 1,
          price: new_product.sale_price_tax_inc,
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