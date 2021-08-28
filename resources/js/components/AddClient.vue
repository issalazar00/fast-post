<template>
  <div
    class="modal fade"
    id="addClientModal"
    tabindex="-1"
    aria-labelledby="addClientModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addClientModalLabel">Cliente</h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Cerrar"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="input-group">
            <input
              type="text"
              class="form-control"
              placeholder="Código de barras | Nombre de product"
              aria-label=" with two button addons"
              aria-describedby="button-addon4"
            />
            <div class="input-group-append" id="button-addon4">
              <button
                class="btn btn-outline-secondary"
                type="button"
                @click="searchClient()"
              >
                Buscar Cliente
              </button>
            </div>
          </div>
          <table class="table table-bordered table-sm table-responsive">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Nombres</th>
                <th>Documento</th>
                <th scope="col">Direccion</th>
                <th>Telefono</th>
                <th>Correo</th>
                <th>Contacto</th>
                <th>Estado</th>
                <th>Opciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="client in ClientList.data" v-bind:key="client.id">
                <th scope="row">{{ client.code }}</th>
                <td>{{ client.name }}</td>
                <td>{{ client.document }}</td>
                <td>{{ client.address }}</td>
                <td>{{ client.mobile }}</td>
                <td>{{ client.email }}</td>
                <td>
                  {{ client.contact }}
                </td>
                <td>
                  <span class="badge badge-success">Activo</span>
                </td>
                <td>
                  <button
                    class="btn btn-outline-secondary"
                    @click="addClient(client.id)"
                  >
                    <i class="bi bi-plus-circle"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            Cerrar
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "add-client",
  data() {
    return {
      ClientList: {},
    };
  },
  created() {
    this.listClients();
  },
  methods: {
    listClients() {
      let me = this;
      axios.get("api/clients", this.$root.config).then(function (response) {
        me.ClientList = response.data.clients;
      });
    },
  },
};
</script>