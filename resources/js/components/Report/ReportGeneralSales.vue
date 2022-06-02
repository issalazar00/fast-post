<template>
  <div class="page w-100">
    <div class="page-header">
      <h3>Reporte general de venta</h3>
    </div>
    <div class="page-search mx-2 my-2 border p-2">
      <div class="form-row">
        <div class="form-group col-md-3">
          <label for="box">Caja</label>
          <v-select :options="boxList" label="name" :reduce="box => box.id" v-model="filter.box" />
        </div>
        <div class="form-group col-md-3">
          <label for="from_date">Desde</label>
          <input type="date" class="form-control" id="from_date" v-model="filter.from" />
        </div>
        <div class="form-group col-md-3">
          <label for="to_date">Hasta</label>
          <input type="date" class="form-control" id="to_date" v-model="filter.to" />
        </div>
        <div class="col my-4">
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
              <th>Número total de facturas</th>
              <th>Nro. facturas registradas</th>
              <th>Nro. facturas suspendidas</th>
              <th>Nro. facturas cotizadas</th>
              <th>Nro. Creditos</th>
              <th>Total precio de costo</th>
              <th>Total IVA excluido</th>
              <th>Total IVA incluido</th>
              <th>Total Descuento</th>
              <th>Total facturado</th>
              <th>Ganancia del día</th>
            </tr>
          </thead>
          <tbody v-if="List.length > 0">
            <tr v-for="(l, index) in List" :key="index">
              <td>
                {{ l.number_of_orders }}
              </td>
              <td>
                {{ l.registered }}
              </td>
              <td>
                {{ l.suspended }}
              </td>
              <td>
                {{ l.quoted }}
              </td>
              <td>
                {{ l.credit }}
              </td>
              <td>
                {{ l.total_cost_price_tax_inc | currency }}
              </td>
              <td>
                {{ l.total_iva_exc | currency }}
              </td>
              <td>
                {{ l.total_iva_inc | currency }}
              </td>
              <td>
                {{ l.total_discount | currency }}
              </td>
              <td>
                {{ l.total_paid | currency }}
              </td>
              <td>
                {{ (l.total_iva_exc - l.total_cost_price_tax_inc) | currency }}
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
        box: 0
      },
      boxList: []
    };
  },
  methods: {
    getOrders(page = 1) {
      let me = this;
      axios
        .get(
          `api/reports/general-sales-report?page=${page}&from=${me.filter.from}&to=${me.filter.to}&box=${me.filter.box}`,
          this.$root.config
        )
        .then(function (response) {
          me.List = response.data;
        }).
        catch(
          me.List = {}
        );
    },
    listBoxes() {
      let me = this;
      axios
        .get(`api/boxes/box-list`, this.$root.config)
        .then(function (response) {
          me.boxList = response.data.boxes;
        });
    },
  },
  mounted() {
    this.getOrders(1);
    this.listBoxes()
  }
};
</script>
