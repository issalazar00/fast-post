<template>
  <div class="col-12">
    <h3 class="page-header">Clientes</h3>

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
          <tr v-for="client in clientList.data" v-bind:key="client.id">
            <th scope="row">{{ client.id }}</th>
            <td>{{ client.name }}</td>
            <td>{{ client.document }}</td>
            <td>{{ client.address }}</td>
            <td>{{ client.mobile }}</td>
            <td>{{ client.email }}</td>
            <td>
              {{ client.contact }}
            </td>
            <td>
              <button
                class="btn"
                :class="client.active == 1 ? ' btn-outline-success' : 'btn-outline-danger'"
                @click="changeState(client.id)"
              >
                <i
                  class="bi bi-check-circle-fill"
                  v-if="client.active == 1"
                ></i>
                <i class="bi bi-x-circle" v-else></i>
              </button>
            </td>
            <td>
              <button class="btn btn-outline-success" @click="ShowData(client)">
                <i class="bi bi-pen"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
      <pagination
        :align="'center'"
        :data="clientList"
        @pagination-change-page="listClients"
      >
        <span slot="prev-nav">&lt; Previous</span>
        <span slot="next-nav">Next &gt;</span>
      </pagination>
    </section>

    <create-edit-client ref="CreateEditClient" @list-clients="listClients(1)" />
  </div>
</template>

<script>
import CreateEditClient from "../Client/CreateEditClient.vue";
export default {
  components: { CreateEditClient },
  data() {
    return {
      clientList: {},
      edit: false,
    };
  },
  created() {
    this.listClients(1);
  },
  methods: {
    listClients(page = 1) {
      let me = this;
      axios
        .get("api/clients?page=" + page, this.$root.config)
        .then(function (response) {
          me.clientList = response.data.clients;
        });
    },
    ShowData: function (client) {
      this.$refs.CreateEditClient.OpenEditClient(client);
    },
    changeState: function (id) {
      let me = this;
      axios
        .post("api/clients/" + id + "/activate", null, me.$root.config)
        .then(function () {
          me.listClients(1);
        });
    },
  },

  mounted() {
    console.log("Component mounted.");
  },
};
</script>
