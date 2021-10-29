<template>
  <div class="w-100">
    <header class="page-header justify-content-between row px-4">
      <h3>Facturacion</h3>
      <router-link
        class="btn btn-outline-primary"
        :to="{
          name: 'create-edit-billing',
          params: { billing_id: 0 },
        }"
      >
        Nueva facturación
      </router-link>
    </header>
    <section>
      <div class="card-body">
        <div class="form-row">
          <h6 class="w-100">Buscar...</h6>
          <div class="form-group col-3">
            <label for="nro_factura">Nro Factura</label>
            <input
              type="text"
              name="nro_factura"
              id="nro_factura"
              class="form-control"
              placeholder="Nro Factura"
              v-model="filter.no_invoice"
              @keypress="getBillings(1)"
            />
          </div>
          <div class="form-group col-3">
            <label for="name_supplier">Proveedor</label>
            <input
              type="text"
              name="name_supplier"
              id="name_supplier"
              class="form-control"
              placeholder="Proveedor"
              v-model="filter.supplier"
              @keypress="getBillings(1)"
            />
          </div>
        </div>
        <table class="table table-sm table-bordered table-responsive-sm">
          <thead>
            <tr>
              <th>#</th>
              <th>Total Pago</th>
              <th>Total Sin Iva</th>
              <th>Total Descuento</th>
              <th>Cliente</th>
              <th>Estado</th>
              <th>Ver</th>
              <th>Facturar</th>
              <th>Imprimir</th>
              <th>Editar</th>
              <th>Eliminar</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="o in OrderList.data" :key="o.id">
              <th scope="row">{{ o.id }} - {{ o.no_invoice }}</th>
              <td>{{ o.total_paid }}</td>
              <td>{{ o.total_iva_exc }}</td>
              <td>{{ o.total_discount }}</td>
              <td>{{ o.supplier.name }}</td>
              <td>
                <span v-if="o.state == 0">Desechada</span>
                <span v-if="o.state == 1">Abierta</span>
                <span v-if="o.state == 2">Registrada</span>
                <span v-if="o.state == 3">Cotización</span>
              </td>
              <td>
                <router-link
                  class="btn"
                  :to="{ name: 'details-order', params: { billing_id: o.id } }"
                >
                  <i class="bi bi-eye"></i>
                </router-link>
              </td>
              <td>
                <button class="btn" v-if="o.state != 0 && o.state != 2">
                  <i class="bi bi-receipt"></i>
                </button>
              </td>
              <td>
                <button class="btn" @click="generatePdf(o.id)">
                  <i class="bi bi-printer"></i>
                </button>
              </td>
              <td>
                <router-link
                  class="btn"
                  :to="{
                    name: 'create-edit-billing',
                    params: { billing_id: o.id },
                  }"
                >
                  <i class="bi bi-pencil-square"></i>
                </router-link>
              </td>
              <td>
                <button class="btn" @click="deleteOrder(o.id)">
                  <i class="bi bi-trash"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <pagination
        :align="'center'"
        :data="OrderList"
        :limit="8"
        @pagination-change-page="getBillings"
      >
        <span slot="prev-nav"><i class="bi bi-chevron-double-left"></i></span>
        <span slot="next-nav"><i class="bi bi-chevron-double-right"></i></span>
      </pagination>
    </section>
    <div class="footer"></div>
  </div>
</template>
<script>
export default {
  components: {},
  data() {
    return {
      OrderList: {},
      filter: {
        supplier: "",
        no_invoice: "",
      },
    };
  },
  created() {
    this.$root.validateToken();
    this.getBillings(1);
  },
  methods: {
    getBillings(page = 1) {
      let me = this;
      axios
        .get(
          `api/billings?page=${page}&supplier=${me.filter.supplier}&no_invoice=${me.filter.no_invoice}`,
          this.$root.config
        )
        .then(function (response) {
          me.OrderList = response.data.billings;
        });
    },
    deleteOrder(billing_id) {
      axios
        .delete(`api/billings/${billing_id}`, this.$root.config)
        .then(() => this.getBillings(1));
    },
    generatePdf(id) {
      axios
        .get("api/billings/generatePdf/" + id, this.$root.config)
        .then((response) => {
          console.log(response);

          const pdf = response.data.pdf;
          var a = document.createElement("a");
          a.href = "data:application/pdf;base64," + pdf;
          a.download = `Order-${id}.pdf`;
          a.click();
        });
    },
  },
};
</script>