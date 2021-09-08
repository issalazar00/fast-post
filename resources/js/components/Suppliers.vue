<template>
  <div class="col-12">
    <h3 class="page-header">Usuarios</h3>

    <div class="row justify-content-end mx-4">
      <button
        type="reset"
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
          <tr v-for="supplier in supplierList.data" v-bind:key="supplier.id">
            <th scope="row">{{ supplier.code }}</th>
            <td>{{ supplier.name }}</td>
            <td>{{ supplier.document }}</td>
            <td>{{ supplier.address }}</td>
            <td>{{ supplier.mobile }}</td>
            <td>{{ supplier.email }}</td>
            <td>
              {{ supplier.contact }}
            </td>
            <td>
              <button
                class="btn"
                :class="supplier.active == 1 ? ' btn-success' : 'btn-danger'"
                @click="changeState(supplier.id)"
              >
                <i
                  class="bi bi-check-circle-fill"
                  v-if="supplier.active == 1"
                ></i>
                <i class="bi bi-x-circle" v-else></i>
              </button>
            </td>
            <td>
              <button
                class="btn btn-outline-success"
                @click="ShowData(supplier)"
              >
                <i class="bi bi-pen"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
      <pagination
        :align="'center'"
        :data="supplierList"
        :limit="10"
        @pagination-change-page="listSuppliers"
      >
        <span slot="prev-nav">&lt; Previous</span>
        <span slot="next-nav">Next &gt;</span>
      </pagination>
    </section>

    <create-edit-supplier
      ref="CreateEditSupplier"
      @list-suppliers="listSuppliers(1)"
    />
  </div>
</template>

<script>
import CreateEditSupplier from "./CreateEditSupplier.vue";
export default {
  components: { CreateEditSupplier },
  data() {
    return {
      supplierList: {},
      edit: false,
    };
  },
  created() {
    this.listSuppliers(1);
  },
  methods: {
    listSuppliers(page = 1) {
      let me = this;
      axios
        .get("api/suppliers?page=" + page, this.$root.config)
        .then(function (response) {
          me.supplierList = response.data.suppliers;
        });
    },
    ShowData: function (supplier) {
      this.$refs.CreateEditSupplier.OpenEditSupplier(supplier);
    },
    changeState: function (id) {
      let me = this;
      axios
        .post("api/suppliers/" + id + "/activate", null, me.$root.config)
        .then(function () {
          me.listSuppliers(1);
        });
    },
  },

  mounted() {},
};
</script>
