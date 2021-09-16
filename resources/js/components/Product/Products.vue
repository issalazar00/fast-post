<template>
  <div>
    <div class="col-12">
      <h3 class="page-header">Productos</h3>
      <moon-loader :loading="isLoading" :color="'#032F6C'" :size="100" />

      <div class="card-body" v-if="!isLoading">
        <div class="row justify-content-end">
          <button
            type="button"
            class="btn btn-outline-primary mr-2"
            data-toggle="modal"
            data-target="#productImportModal"
            v-if="validatePermission('product.store')"
          >
            <i class="bi bi-cloud-arrow-up-fill"></i>
            Importar Productos
          </button>
          <button
            type="button"
            class="btn btn-outline-primary"
            data-toggle="modal"
            data-target="#productModal"
            v-if="validatePermission('product.store')"
          >
            <i class="bi bi-plus-circle-dotted"></i>
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
                <th scope="col">Precio Venta con IVA</th>
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
                <td>{{ product.category.name }}</td>
                <td class="text-right">$ {{ product.sale_price_tax_inc }}</td>
                <td>{{ product.quantity }}</td>
                <td>
                  <button
                    class="btn"
                    :class="product.state == 1 ? ' btn-success' : ' btn-danger'"
                    @click="changeState(product.id)"
                  >
                    <i
                      class="bi bi-check-circle-fill"
                      v-if="product.state == 1"
                    ></i>
                    <i class="bi bi-x-circle" v-else></i>
                  </button>
                </td>
                <td>
                  <button
                    class="btn btn-outline-success"
                    @click="ShowData(product)"
                  >
                    <i class="bi bi-pen"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
          <pagination
            :align="'center'"
            :data="ProductList"
            :limit="8"
            @pagination-change-page="listProducts"
          >
            <span slot="prev-nav"
              ><i class="bi bi-chevron-double-left"></i
            ></span>
            <span slot="next-nav"
              ><i class="bi bi-chevron-double-right"></i
            ></span>
          </pagination>
        </section>
      </div>
    </div>
    <!-- Modal para creacion y edicion de products -->
    <create-edit-product
      ref="CreateEditProduct"
      @list-products="listProducts(1)"
    />
    <import-products />
  </div>
</template>

<script>
import global from "../../services/global.js";
import CreateEditProduct from "./CreateEditProduct.vue";
import ImportProducts from "./ImportProducts.vue";
export default {
  components: { CreateEditProduct, ImportProducts },
  data() {
    return {
      isLoading: false,
      ProductList: {},
    };
  },
  created() {
    this.isLoading = true;
    let me = this;
    axios
      .get("api/products?page=1", this.$root.config)
      .then(function (response) {
        me.ProductList = response.data.products;
      })
      .finally(() => (this.isLoading = false));
  },
  methods: {
    listProducts(page = 1) {
      // this.isLoading = true;
      let me = this;
      axios
        .get("api/products?page=" + page, this.$root.config)
        .then(function (response) {
          me.ProductList = response.data.products;
        });
        // .finally(() => (this.isLoading = false));
    },
    ShowData: function (product) {
      this.$refs.CreateEditProduct.OpenEditProduct(product);
    },
    changeState: function (id) {
      let me = this;
      axios
        .post("api/products/" + id + "/activate", null, me.$root.config)
        .then(function () {
          me.listProducts(1);
        });
    },

    validatePermission(permission) {
      return global.validatePermission(this.$root.permissions, permission);
    },
  },

  mounted() {},
};
</script>
