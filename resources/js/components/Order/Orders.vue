<template>
  <div>
    <div class="header">
      <h3 class="h3 w-100">Tickets</h3>
      <div class="row justify-content-end mx-4">
        <router-link class="btn btn-outline-primary" to="create-edit-order">
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
              <th scope="row">{{ o.id }}</th>
              <td>{{ o.total_paid }}</td>
              <td>{{ o.total_iva_exc }}</td>
              <td>{{ o.total_discount }}</td>
              <td>{{ o.client_id }}</td>
              <td>{{ o.state }}</td>
              <td>
                <router-link
                  class="btn"
                  :to="{ name: 'details-order', params: { order_id: o.id } }"
                >
                  <i class="bi bi-eye"></i>
                </router-link>
              </td>
              <td>
                <button class="btn">
                  <i class="bi bi-receipt"></i>
                </button>
              </td>
              <td>
                <button class="btn">
                  <i class="bi bi-printer"></i>
                </button>
              </td>
              <td>
                <router-link class="btn" to="/details-order">
                  <i class="bi bi-pencil-square"></i>
                </router-link>
              </td>
              <td>
                <button class="btn">
                  <i class="bi bi-file-earmark-x"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
          <li class="page-item disabled">
            <a class="page-link" href="#" tabindex="-1" aria-disabled="true"
              >Previous</a
            >
          </li>
          <li class="page-item"><a class="page-link" href="#">1</a></li>
          <li class="page-item"><a class="page-link" href="#">2</a></li>
          <li class="page-item"><a class="page-link" href="#">3</a></li>
          <li class="page-item">
            <a class="page-link" href="#">Next</a>
          </li>
        </ul>
      </nav>
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
    };
  },
  created() {
    this.getOrders(1);
  },
  methods: {
    getOrders(page = 1) {
      let me = this;
      axios
        .get(`api/orders?page=${page}`, this.$root.config)
        .then(function (response) {
          me.OrderList = response.data.orders;
        });
    },
  },
};
</script>