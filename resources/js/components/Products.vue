<template>
  <div>
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
              <tr
                v-for="product in listingProducts.data"
                v-bind:key="product.id"
              >
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
                    @click="DesactivarProducto(product.id)"
                  >
                    <i class="bi bi-check-circle-fill"></i>
                  </button>
                  <button
                    class="btn btn-danger"
                    v-else
                    @click="ActivarProducto(product.id)"
                  >
                    <i class="bi bi-x-circle"></i>
                  </button>
                </td>
                <td>
                  <button
                    class="btn btn-success"
                    @click="MostrarDatos(product), (edit = true)"
                  >
                    Editar
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
          <pagination
            :align="'center'"
            :data="listingProducts"
            @pagination-change-page="listProducts"
          >
            <span slot="prev-nav">&lt; Previous</span>
            <span slot="next-nav">Next &gt;</span>
          </pagination>
        </section>
      </div>
    </div>
    <!-- Modal para creacion y edicion de products -->
    <div
      class="modal fade"
      id="productModal"
      tabindex="-1"
      aria-labelledby="productModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="productModalLabel">Producto</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <create-edit-product ref="CreateEditProduct" />
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              @click="CerrarModal()"
            >
              Close
            </button>
            <button
              type="button"
              class="btn btn-primary"
              @click="GuardarProducto()"
            >
              Guardar
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import CreateEditProduct from "./CreateEditProduct.vue";
export default {
  components: { CreateEditProduct },
  data() {
    return {
      isLoading: false,
      listingProducts: {},
      edit: false,
    };
  },
  created() {
    //
    this.listProducts(1);
    // axios
    //   .get("api/products?page=1")
    //   .then(function (response) {
    //     this.listingProducts = response.data.products;
    //   })
  },
  methods: {
    listProducts(page = 1) {
      this.isLoading = true;
      let me = this;
      axios
        .get("api/products?page=" + page)
        .then(function (response) {
          me.listingProducts = response.data.products;
        })
        .finally(() => (this.isLoading = false));
    },

    GuardarProducto: function () {
      let me = this;
      if (this.edit == false) {
        this.$refs.CreateEditProduct.CreateProduct();
      } else {
        this.$refs.CreateEditProduct.EditProduct();
      }
      this.listProducts(1);
    },

    MostrarDatos: function (product) {
      this.$refs.CreateEditProduct.OpenEditProduct(product);
    },
    CerrarModal: function () {
      let me = this;
      this.$refs.CreateEditProduct.ResetData();
      this.listProducts(1);
    },
    ActivarProducto: function (id) {
      let me = this;
      axios.post("api/products/" + id + "/activate").then(function () {
        me.listProducts(1);
      });
    },
    DesactivarProducto: function (id) {
      let me = this;
      axios.post("api/products/" + id + "/deactivate").then(function () {
        me.listProducts(1);
      });
    },
  },

  mounted() {},
};
</script>
