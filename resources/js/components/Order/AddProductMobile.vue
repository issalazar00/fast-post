<template>
  <div
    class="modal fade"
    id="addProductModal"
    tabindex="-1"
    aria-labelledby="addProductModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addProductModalLabel">Productos</h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Cerrar"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="input-group">
            <input
              type="text"
              class="form-control"
              placeholder="Código de barras | Nombre de product"
              aria-label=" with two button addons"
              aria-describedby="button-addon4"
              v-model="filters.product"
              @keyup="searchProduct()"
            />
            <div class="input-group-append" id="button-addon4">
              <button
                class="btn btn-outline-secondary"
                type="button"
                @click="searchProduct()"
              >
                Buscar Producto
              </button>
            </div>
          </div>

          <div class="row  card-group">
            <div
              class="col-6 col-sm-6 col-md-4 col-lg-3"
              v-for="product in ProductList.data"
              v-bind:key="product.id"
            >
              <div class="card">
                <div class="card-header bg-transparent border-success">
                  <h5><b>{{ product.product }}</b></h5>
                </div>
                <div class="card-body text-dark p-0">
                  <!-- <h5 class="card-title">Success card title</h5> -->
                  <div class="card-text">
                    <ul class="list-group">
                      <li class="list-group-item">
                        <b><i>Categoría</i></b
                        >:
                        {{ product.category.name ? product.category.name : "" }}
                      </li>
                      <li class="list-group-item">
                        <b><i>Código de barras</i></b
                        >: {{ product.barcode }}
                      </li>
                      <li class="list-group-item">
                        <b><i>Precio</i></b
                        >: {{ product.sale_price_tax_inc | currency }}
                      </li>
                      <li class="list-group-item">
                        <b><i>Inventario</i></b
                        >: {{ product.quantity }}
                      </li>
                      <!-- <li class="list-group-item">
                        <b><i></i></b>:
                      </li> -->
                    </ul>
                  </div>
                </div>
                <div class="card-footer bg-transparent border-success d-flex justify-content-around">
                  <button
                  class="btn btn-danger add_product"
                  @click="$emit('add-product', product)"
                >
                  <i class="bi bi-dash-circle"></i>
                </button>

                  <button
                    class="btn btn-success add_product"
                    @click="$emit('add-product', product)"
                  >
                    <i class="bi bi-plus-circle"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            Cerrar
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "add-product",
  props: { is_order: 0 },
  data() {
    return {
      // Filter modal
      filters: {
        product: "",
      },
      ProductList: {},
    };
  },

  created() {
    this.listProducts();
  },
  methods: {
    listProducts() {
      let me = this;
      axios
        .post(
          `api/products/filter-product-list?product=${me.filters.product}&is_order=${this.is_order}`,
          null,
          this.$root.config
        )
        .then(function (response) {
          me.ProductList = response;
        });
    },
    searchProduct() {
      let me = this;
      if (me.filters.product == "") {
        return false;
      }
      var url =
        "api/products/filter-product-list?product=" + me.filters.product;
      if (me.filters.product.length >= 3) {
        axios
          .post(url, null, me.$root.config)
          .then(function (response) {
            me.ProductList = response;
          })
          .catch(function (error) {
            $("#no-results").toast("show");

            console.log(error);
          });
      }
    },
  },
};
</script>

<style scoped>
.add_product{
  font-size: 1.5rem;
}
.modal-dialog{
  min-width: 90%;
}
</style>