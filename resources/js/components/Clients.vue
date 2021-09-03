<template>
  <div class="col-12">
    <h3 class="page-header">Usuarios</h3>

    <div class="row justify-content-end mx-4">
      <button
        type="button"
        class="btn btn-primary"
        data-toggle="modal"
        data-target="#clientModal"
        @click="edit = false"
      >
        Crear Cliente
      </button>
    </div>

    <section class="card-body">
      <table class="table table-bordered table-sm">
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
          <tr v-for="client in clientListing.data" v-bind:key="client.id">
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
              <button class="btn btn-success">
                <i class="bi bi-check-circle-fill"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
      <pagination
        :align="'center'"
        :data="clientListing"
        @pagination-change-page="listClients"
      >
        <span slot="prev-nav">&lt; Previous</span>
        <span slot="next-nav">Next &gt;</span>
      </pagination>
    </section>

    <div
      class="modal fade"
      id="clientModal"
      tabindex="-1"
      aria-labelledby="clientModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="clientModalLabel">Client</h5>
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
            <create-edit-client ref="CreateEditClient" />
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              @click="closeModal()"
            >
              Cerrar
            </button>
            <button
              type="button"
              class="btn btn-primary"
              @click="SaveClient()"
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
import CreateEditClient from "./CreateEditClient.vue";
export default {
  components: { CreateEditClient },
  data() {
    return {
      clientListing: {},
      edit: false,
    };
  },
  created() {
    this.listClients(1);
  },
  methods: {
    listClients(page = 1) {
      let me = this;
      axios.get("api/clients?page=" + page, this.$root.config).then(function (response) {
        me.clientListing = response.data.clients;
      });
    },
    SaveClient: function () {
      let me = this;
      if (this.edit == false) {
        this.$refs.CreateEditClient.CreateClient();
      } else {
        this.$refs.CreateEditClient.EditClient();
      }
      me.listClients(1);
    },

    ShowData: function (client) {
      this.$refs.CreateEditClient.OpenEditClient(client);
    },
    closeModal: function () {
      let me = this;
      this.$refs.CreateEditClient.ResetData();
      me.listClients(1);
    },
    ActivateClient: function (id) {
      let me = this;
      axios.post("api/clients/" + id + "/activate").then(function () {
        me.listClients(1);
      });
    },
    DeactivateClient: function (id) {
      let me = this;
      axios.post("api/clients/" + id + "/deactivate").then(function () {
        me.listClients(1);
      });
    },
  },

  mounted() {
    console.log("Component mounted.");
  },
};
</script>
