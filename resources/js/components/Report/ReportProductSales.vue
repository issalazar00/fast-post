<template>
  <div class="page w-100">
    <div class="page-header">
      <h3>Reporte general de productos vendidos</h3>
    </div>
    <div class="page-search mx-2 my-2 border p-2">
      <div class="form-row">
        <div class="form-group col-md-3">
          <label for="filter_product">Producto</label>
          <input type="text" class="form-control" id="filter_product" v-model="filter.product" />
        </div>
        <div class="form-group col-md-3">
          <label for="from_date">Desde</label>
          <input type="date" class="form-control" id="from_date" v-model="filter.from" />
        </div>
        <div class="form-group col-md-3">
          <label for="to_date">Hasta</label>
          <input type="date" class="form-control" id="to_date" v-model="filter.to" />
        </div>
        <div class="col-md-3  my-4">
          <button class="btn btn-success btn-block" @click="getOrders(1)">
            Buscar <i class="bi bi-search"></i>
          </button>
        </div>
      </div>
    </div>
    <div class="page-content">
      <section class="p-3">
        <table class="table table-sm table-bordered table-hover">
          <thead class=" thead-dark">
            <tr>
              <th>Producto</th>
              <th>Código de barras</th>
              <th>Cantidad productos vendidos</th>
            </tr>
          </thead>
          <tbody v-if="List.length > 0">
            <tr v-for="(l, index) in List" :key="index">
              <td>
                {{ l.product }}
              </td>
              <td>
                <span class="barcode">{{ l.barcode }}</span>
              </td>
              <td>
                {{ l.quantity_of_products }}
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
      </section>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      List: {},
      filter: {
        from: "",
        to: "",
        product: ''
      }
    };
  },
  methods: {
    getOrders(page = 1) {
      let me = this;
      axios
        .get(
          `api/reports/product-sales-report?page=${page}&from=${me.filter.from}&to=${me.filter.to}&product=${me.filter.product}`,
          this.$root.config
        )
        .then(function (response) {
          me.List = response.data;
        });
    }
  },
  mounted() {
    this.getOrders(1);
  }
}
</script>
