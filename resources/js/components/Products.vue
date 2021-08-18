<template>
  <div class="page">
    <div class="col-12">
      <h3 class="page-header">Productos</h3>
      <ring-loader :loading="isLoading" :color="'#032F6C'" :size="100" />

      <div class="card-body" v-if="!isLoading">
        <div class="row justify-content-end mx-4">
          <button
            type="button"
            class="btn btn-primary"
            data-toggle="modal"
            data-target="#productModal"
            @click="edit = false"
          >
            Crear Producto
          </button>
        </div>

        <section class="mt-4">
          <table class="table table-sm table-bordered table-responsive-sm">
            <thead class="thead-primary">
              <tr>
                <th scope="col">#</th>
                <th>Código de barras</th>
                <th scope="col">Producto</th>
                <th>Categoria</th>
                <th scope="col">Precio Venta</th>
                <th scope="col">Cantidad</th>
                <th>Estado</th>
                <th>Opciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="product in ProductList.data" v-bind:key="product.id">
                <td>{{ product.id }}</td>
                <td>{{ product.barcode }}</td>
                <td>{{ product.product }}</td>
                <td>{{ product.category }}</td>
                <td class="text-right">$ {{ product.sale_price_tax_exc }}</td>
                <td>{{ product.quantity }}</td>
                <td>
                  <button
                    class="btn btn-success"
                    v-if="product.state == 1"
                    @click="DeactivateProduct(product.id)"
                  >
                    <i class="bi bi-check-circle-fill"></i>
                  </button>
                  <button
                    class="btn btn-danger"
                    v-else
                    @click="ActivateProduct(product.id)"
                  >
                    <i class="bi bi-x-circle"></i>
                  </button>
                </td>
                <td>
                  <button
                    class="btn btn-success"
                    data-target="#productModal"
                    data-toggle="modal"
                    @click="ShowData(product), (edit = true)"
                  >
                    Editar
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
          <pagination
            :align="'center'"
            :data="ProductList"
            @pagination-change-page="listProducts"
          >
            <span slot="prev-nav">&lt; Previous</span>
            <span slot="next-nav">Next &gt;</span>
          </pagination>
        </section>
      </div>
    </div>
    <!-- Modal para creacion y edicion de products -->
    <create-edit-product
      ref="CreateEditProduct"
      @list-products="listProducts(1)"
    />
  </div>
</template>

<script>
import CreateEditProduct from "./CreateEditProduct.vue";
export default {
  components: { CreateEditProduct },
  data() {
    return {
      isLoading: false,
      ProductList: {},
      edit: false,
    };
  },
  created() {
    this.listProducts(1);
  },
  methods: {
    listProducts(page = 1) {
      this.isLoading = true;
      let me = this;
      axios
        .get("api/products?page=" + page)
        .then(function (response) {
          me.ProductList = response.data.products;
        })
        .finally(() => (this.isLoading = false));
    },

    ShowData: function (product) {
      this.$refs.CreateEditProduct.OpenEditProduct(product);
    },

    ActivateProduct: function (id) {
      let me = this;
      axios.post("api/products/" + id + "/activate").then(function () {
        me.listProducts(1);
      });
    },
    DeactivateProduct: function (id) {
      let me = this;
      axios.post("api/products/" + id + "/deactivate").then(function () {
        me.listProducts(1);
      });
    },
  },

  mounted() {},
};
</script>
