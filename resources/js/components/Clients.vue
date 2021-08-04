<template>
  <div class="col-12">
    <h3 class="page-header">Usuarios</h3>

    <div class="row justify-content-end mx-4">
      <router-link to="/create-edit-client" class="btn btn-primary"
        >Crear Cliente</router-link
      >
    </div>

    <div class="card-body">
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
            <th scope="row"> {{client.code}} </th>
            <td>{{client.name}} </td>
            <td>{{client.document}} </td>
            <td>{{client.address}} </td>
            <td>{{client.phone}} </td>
            <td>{{client.email}} </td>
            <td>{{client.firstname_contact}} {{client.lastname_contact}}  </td>
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
        @pagination-change-page="listSuppliers"
      >
        <span slot="prev-nav">&lt; Previous</span>
        <span slot="next-nav">Next &gt;</span>
      </pagination>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      clientListing: {},
      edit: false,
    };
  },
  created() {
    this.listSuppliers(1);
  },
  methods: {
    listSuppliers(page = 1) {
      let me = this;
      axios.get("api/clients?page=" + page).then(function (response) {
        me.clientListing = response.data.clients;
      });
    },
  },

  mounted() {
    console.log("Component mounted.");
  },
};
</script>
