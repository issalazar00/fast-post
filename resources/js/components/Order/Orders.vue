<template>
  <div class="w-100">
    <header class="page-header">
      <h3>Tickets</h3>
    </header>
    <div class="header">
      <div class="row justify-content-end mx-4">
        <router-link
          class="btn btn-outline-primary"
          :to="{
            name: 'create-edit-order',
            params: { order_id: 0 },
          }"
        >
          Nueva orden
        </router-link>
      </div>
    </div>
    <section>
      <div class="card-body">
        <div class="form-row">
          <h6 class="w-100">Buscar...</h6>
          <div class="form-group">
            <label for="nro_factura">Nro Factura</label>
            <input
              type="text"
              name="nro_factura"
              id="nro_factura"
              class="form-control"
              placeholder="Nro Factura"
              v-model="filter.no_invoice"
              @keypress="getOrders(1)"
            />
          </div>
          <div class="form-group">
            <label for="name_client">Cliente</label>
            <input
              type="text"
              name="name_client"
              id="name_client"
              class="form-control"
              placeholder="Cliente"
              v-model="filter.client"
              @keypress="getOrders(1)"
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
              <td>{{ o.client.name }}</td>
              <td>
                <span v-if="o.state == 0">Desechada</span>
                <span v-if="o.state == 1">Abierta</span>
                <span v-if="o.state == 2">Registrada</span>
                <span v-if="o.state == 3">Cotización</span>
              </td>
              <td>
                <router-link
                  class="btn"
                  :to="{ name: 'details-order', params: { order_id: o.id } }"
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
                    name: 'create-edit-order',
                    params: { order_id: o.id },
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
        @pagination-change-page="getOrders"
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
        client: "",
        no_invoice: "",
      },
    };
  },
  created() {
    this.getOrders(1);
  },
  methods: {
    getOrders(page = 1) {
      let me = this;
      axios
        .get(
          `api/orders?page=${page}&client=${me.filter.client}&no_invoice=${me.filter.no_invoice}`,
          this.$root.config
        )
        .then(function (response) {
          me.OrderList = response.data.orders;
        });
    },
    deleteOrder(order_id) {
      axios
        .delete(`api/orders/${order_id}`, this.$root.config)
        .then(() => this.getOrders(1));
    },
    generatePdf(id) {
      axios
        .get("api/orders/generatePdf/" + id, this.$root.config)
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