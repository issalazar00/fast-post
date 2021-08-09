<template>
  <div class="col-12">
    <h3 class="page-header">Usuarios</h3>

    <div class="row justify-content-end mx-4">
      <button
        type="button"
        class="btn btn-primary"
        data-toggle="modal"
        data-target="#supplierModal"
        @click="edit = false"
      >
        Crear Proveedor
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
          <tr v-for="supplier in supplierListing.data" v-bind:key="supplier.id">
            <th scope="row">{{ supplier.code }}</th>
            <td>{{ supplier.name }}</td>
            <td>{{ supplier.document }}</td>
            <td>{{ supplier.address }}</td>
            <td>{{ supplier.phone }}</td>
            <td>{{ supplier.email }}</td>
            <td>
              {{ supplier.firstname_contact }} {{ supplier.lastname_contact }}
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
        :data="supplierListing"
        @pagination-change-page="listSuppliers"
      >
        <span slot="prev-nav">&lt; Previous</span>
        <span slot="next-nav">Next &gt;</span>
      </pagination>
    </section>

    <div
      class="modal fade"
      id="supplierModal"
      tabindex="-1"
      aria-labelledby="supplierModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="supplierModalLabel">Supplier</h5>
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
            <create-edit-supplier ref="CreateEditSupplier" />
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
              @click="SaveSupplier()"
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
import CreateEditSupplier from "./CreateEditSupplier.vue";
export default {
  components: { CreateEditSupplier },
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
    SaveSupplier: function () {
      let me = this;
      if (this.edit == false) {
        this.$refs.CreateEditSupplier.CreateSupplier();
      } else {
        this.$refs.CreateEditSupplier.EditSupplier();
      }
      me.listSuppliers(1);
    },

    ShowData: function (supplier) {
      this.$refs.CreateEditSupplier.OpenEditSupplier(supplier);
    },
    closeModal: function () {
      let me = this;
      this.$refs.CreateEditSupplier.ResetData();
      me.listSuppliers(1);
    },
    ActivateSupplier: function (id) {
      let me = this;
      axios.post("api/suppliers/" + id + "/activate").then(function () {
        me.listSuppliers(1);
      });
    },
    DeactivateSupplier: function (id) {
      let me = this;
      axios.post("api/suppliers/" + id + "/deactivate").then(function () {
        me.listSuppliers(1);
      });
    },
  },

  mounted() {
    console.log("Component mounted.");
  },
};
</script>
