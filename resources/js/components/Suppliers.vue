<template>
  <div class="col-12">
    <h3 class="page-header">Usuarios</h3>

    <div class="row justify-content-end mx-4">
      <router-link to="/create-edit-supplier" class="btn btn-primary"
        >Crear Proveedor</router-link
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
          <tr v-for="supplier in supplierListing.data" v-bind:key="supplier.id">
            <th scope="row"> {{supplier.code}} </th>
            <td>{{supplier.name}} </td>
            <td>{{supplier.document}} </td>
            <td>{{supplier.address}} </td>
            <td>{{supplier.phone}} </td>
            <td>{{supplier.email}} </td>
            <td>{{supplier.firstname_contact}} {{supplier.lastname_contact}}  </td>
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
        :data="supplierListing"
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
      supplierListing: {},
      edit: false,
    };
  },
  created() {
    this.listSuppliers(1);
  },
  methods: {
    listSuppliers(page = 1) {
      let me = this;
      axios.get("api/suppliers?page=" + page).then(function (response) {
        me.supplierListing = response.data.suppliers;
      });
    },
  },

  mounted() {
    console.log("Component mounted.");
  },
};
</script>
