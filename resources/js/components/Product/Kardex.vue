<template>
  <div class="page w-100">
    <div class="page-header">
      <h3>Kardex</h3>
    </div>
    <div class="page-search mx-2 my-2 border p-2">
      <div class="form-row">
        <div class="form-group col-md-3">
          <label for="filter_product">Producto</label>
          <input type="text" class="form-control" id="filter_product" v-model="filter.product" />
        </div>
        <!-- <div class="form-group col-md-3">
            <label for="filter_category">Categoria</label>
            <select id="filter_category" v-model="filter.category" class="form-control">
              <option value="">Selecciona una categoria</option>
              <option v-for=" (item, index) in categoriesList" :key="index" :value="item.id">{{ item.name }}</option>
            </select>
          </div> -->
        <div class="form-group col-md-3">
          <label for="from_date">Desde</label>
          <input type="datetime-local" class="form-control" id="from_date" v-model="filter.from" />
        </div>
        <div class="form-group col-md-3">
          <label for="to_date">Hasta</label>
          <input type="datetime-local" class="form-control" id="to_date" v-model="filter.to" />
        </div>
        <div class="form-group col-3">
          <label for="nro_results">Mostrar {{ filter.no_rresults }} resultados por página:</label>
          <input type="number" step="any" class="form-control" id="nro_results" placeholder="Resultados"
            v-model="filter.nro_results" />
        </div>
        <div class="col-md-3  my-4">
          <button class="btn btn-success btn-block" @click="getKardexes(1)">
            Buscar <i class="bi bi-search"></i>
          </button>
        </div>
        <div class="col my-4 col-3">
          <download-excel class="btn btn-outline-success mr-2 btn-block" :fields="json_fields" :data="List.data"
            name="product-list.xls" type="xls">
            <i class="bi bi-file-earmark-arrow-down-fill"></i> Exportar selección
          </download-excel>
        </div>
      </div>
    </div>
    <div class="page-content">
      <section class="p-3">
        <table class="table table-sm table-bordered table-hover" v-if="productData">
          <thead class=" thead-dark">
            <tr>
              <th>ID</th>
              <th>Código</th>
              <th>Producto</th>
              <th>Cantidad actual</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td> {{ productData.id }} </td>
              <td> {{ productData.barcode }} </td>
              <td> {{ productData.product }}</td>
              <td> {{ productData.quantity }}</td>
            </tr>
          </tbody>
        </table>
        <table class="table table-sm table-bordered table-hover">
          <thead class=" thead-dark">
            <tr>
              <th>ID producto</th>
              <th>Cantidad</th>
              <th>Afectación</th>
              <th>Fecha</th>
            </tr>
          </thead>
          <tbody v-if="List.data">
            <tr v-for="(l, index) in List.data" :key="index">
              <td>
                {{ l.product_id }}
              </td>
              <td>
                {{ l.quantity }}
              </td>
              <td>
                <span>{{ l.type }}:  {{ l.description }}</span>
              </td>
              <td>
                {{ l.date }}
              </td>
            </tr>
          </tbody>
          <tbody v-else>
            <tr>
              <td colspan="3">
                No hay resultados
              </td>
            </tr>
          </tbody>
        </table>
        <pagination :align="'center'" :data="List" :limit="8" @pagination-change-page="getKardexes">
          <span slot="prev-nav"><i class="bi bi-chevron-double-left"></i></span>
          <span slot="next-nav"><i class="bi bi-chevron-double-right"></i></span>
        </pagination>
      </section>
    </div>
  </div>
</template>
  
<script>
export default {
  data() {
    return {
      List: {},
      productData: {},
      filter: {
        from: "",
        to: "",
        product: "",
        category: "",
        nro_results: 15
      },
      categoriesList: [],
      json_fields: {
        'Producto': {
          field: 'product',
          callback: (value) => {
            return value;
          }
        },
        'Código de barras': {
          field: 'barcode',
          callback: (value) => {
            return value;
          }
        },
        'Cantidad vendida': {
          field: 'quantity_of_products',
          callback: (value) => {
            return value;
          }
        },
      }
    };
  },
  computed: {
    total_products: function () {
      return this.List.data.map(item => item.quantity_of_products).reduce((value1, value2) => {
        return value1 + value2;
      });
    }
  },
  methods: {
    getKardexes(page = 1) {
      let me = this;
      let params = new URLSearchParams(me.filter);
      axios
        .get(
          `api/kardexes?page=${page}&${params.toString()}`,
          this.$root.config
        )
        .then(function (response) {
          me.List = response.data.kardexes;
          me.productData = response.data.product_data
        });
    },
    getCategories() {
      let me = this;
      axios.get('api/categories?paginate=0', this.$root.config).then(response => {
        me.categoriesList = response.data.categories;
      });
    }
  },
  mounted() {
    this.getKardexes(1);
    // this.getCategories();
  }
}
</script>
  