<template>
  <div>
    <div class="col-12">
      <div class="page-header">
        <div>
          <h3>Productos</h3>
        </div>
        <div class="row">
          <div class="col-md-3">
            <div class="card border border-primary p-2">
              <h6 class="text-uppercase text-secondary">Nro. de Productos</h6>
              <h2>{{ TotalProductsList.number_of_products }}</h2>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card border border-primary p-2">
              <h6 class="text-uppercase text-secondary">Cant. total de Productos</h6>
              <h2>{{ TotalProductsList.quantity_of_products }}</h2>
            </div>

          </div>
          <div class="col-md-3">
            <div class="card border border-primary p-2">
              <h6 class="text-uppercase text-secondary">Valor de stock</h6>
              <h2>{{ TotalProductsList.cost_stock | currency }}</h2>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card border border-primary p-2">
              <h6 class="text-uppercase text-secondary">Valor de venta</h6>
              <h2>{{ TotalProductsList.cost_sale | currency }}</h2>
            </div>
          </div>
        </div>
      </div>
      <moon-loader :loading="isLoading" class="m-auto" :color="'#032F6C'" :size="100" />

      <div class="card-body" v-if="!isLoading">
        <div class="form-row">
          <div class="form-group col-3">
            <label for="product">Código o nombre de producto</label>
            <input type="text" class="form-control" id="search_product" placeholder="Nombre | Código de barras"
              v-model="search_product" autofocus @keyup="listProducts(1)" />
          </div>
          <div class="form-group col-3">
            <label for="category">Categoria</label>
            <v-select :options="categoryList" label="name" :reduce="(category) => category.id"
              v-model="search_category" />
          </div>
          <div class="form-group col-3">
            <label for="brand">Marca</label>
            <v-select :options="brandList" label="name" :reduce="(brand) => brand.id" v-model="search_brand" />
          </div>
          <div class="form-group col-3">
            <label for="search_quantity_sign">Cantidad:</label>
            <select name="search_quantity_sign" class="custom-select" v-model="search_quantity_sign"
              id="search_quantity_sign">
              <option value=">"> Mayor que</option>
              <option value="<">Menor que</option>
              <option value="=">Igual a</option>
            </select>
          </div>
          <div class="form-group col-3">
            <label for="quantity">Valor de Cantidad:</label>
            <input type="quantity" step="any" class="form-control" id="search_quantity" placeholder="Cantidad"
              v-model="search_quantity" />
          </div>
          <div class="form-group col-3 offset-6">
            <button class="btn btn-success btn-block" @click="listProducts(1)">
              Buscar <i class="bi bi-search"></i>
            </button>
          </div>
        </div>
        <div class="row justify-content-end" v-if="$root.validatePermission('product.store')">
          <button type="button" class="btn btn-outline-primary mr-2" data-toggle="modal"
            data-target="#productImportModal" v-if="$root.validatePermission('product.store')">
            <i class="bi bi-cloud-arrow-up-fill"></i>
            Importar Productos
          </button>
          <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#productModal"
            v-if="$root.validatePermission('product.store')">
            <i class="bi bi-plus-circle-dotted"></i>
            Crear Producto
          </button>
        </div>

        <section class="mt-4">
          <table class="table table-sm table-bordered table-responsive-sm">
            <thead class="thead-primary">
              <tr>
                <th>Código de barras</th>
                <th scope="col">Producto</th>
                <th>Categoria</th>
                <th scope="col">Precio Venta con IVA</th>
                <th scope="col">Cantidad</th>
                <th scope="col">Cantidad Mínima</th>

                <th v-if="$root.validatePermission('product.active')">
                  Estado
                </th>
                <th v-if="$root.validatePermission('product.update')">
                  Opciones
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="product in ProductList.data" v-bind:key="product.id">
                <td>{{ product.barcode }}</td>
                <td>{{ product.product }}</td>
                <td>{{ product.category.name }}</td>
                <td class="text-right">
                  {{ product.sale_price_tax_inc | currency }}
                </td>
                <td>{{ product.quantity }}</td>
                <td :class="product.quantity < product.minimum ? 'text-danger' : ''">
                  {{ product.minimum }}
                </td>
                <td v-if="$root.validatePermission('product.active')">
                  <button class="btn" :class="product.state == 1 ? ' btn-success' : ' btn-danger'"
                    @click="changeState(product.id)">
                    <i class="bi bi-check-circle-fill" v-if="product.state == 1"></i>
                    <i class="bi bi-x-circle" v-else></i>
                  </button>
                </td>
                <td v-if="$root.validatePermission('product.update')">
                  <button class="btn btn-outline-success" @click="ShowData(product)">
                    <i class="bi bi-pen"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
          <pagination :align="'center'" :data="ProductList" :limit="8" @pagination-change-page="listProducts">
            <span slot="prev-nav"><i class="bi bi-chevron-double-left"></i></span>
            <span slot="next-nav"><i class="bi bi-chevron-double-right"></i></span>
          </pagination>
        </section>
      </div>
    </div>
    <!-- Modal para creacion y edicion de products -->
    <create-edit-product ref="CreateEditProduct" @list-products="listProducts(1)" />
    <import-products @list-products="listProducts(1)" />
  </div>
</template>

<script>
import CreateEditProduct from "./CreateEditProduct.vue";
import ImportProducts from "./ImportProducts.vue";
export default {
  components: { CreateEditProduct, ImportProducts },
  data() {
    return {
      search_product: "",
      search_category: 0,
      search_brand: 0,
      search_quantity_sign: '>',
      search_quantity: 0,
      isLoading: false,
      ProductList: {},
      TotalProductsList: {},
      categoryList: [],
      brandList: [],
    };
  },
  created() {
    this.$root.validateToken();
    this.isLoading = true;
    let me = this;
    axios
      .get(`api/products?page=1`, this.$root.config)
      .then(function (response) {
        me.ProductList = response.data.products;
        me.TotalProductsList = response.data.total_products;
      })
      .finally(() => (this.isLoading = false));
  },
  methods: {
    listProducts(page = 1) {
      let me = this;
      axios
        .get(
          `api/products?page=${page}&product=${me.search_product}&category_id=${me.search_category}&brand_id=${me.search_brand}&quantity_sign=${me.search_quantity_sign}&quantity=${me.search_quantity}`,
          this.$root.config
        )
        .then(function (response) {
          me.ProductList = response.data.products;
          me.TotalProductsList = response.data.total_products;
        });
    },
    listCategories() {
      let me = this;
      axios
        .get(`api/categories/category-list`, this.$root.config)
        .then(function (response) {
          me.categoryList = response.data.categories;
        });
    },
    listBrands() {
      let me = this;
      axios
        .get(`api/brands/brand-list`, this.$root.config)
        .then(function (response) {
          me.brandList = response.data.brands;
        });
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
  },

  mounted() {
    this.listCategories();
    this.listBrands();
  },
};
</script>
