<template>
  <div class="col-12">
    <h3 class="page-header">Taxes</h3>
    <div class="row justify-content-end mx-4">
      <button
        type="button"
        class="btn btn-outline-primary"
        data-toggle="modal"
        data-target="#taxModal"
        @click="($refs.CreateEditTax.ResetData()), (edit = false)"
        v-if="$root.validatePermission('tax.store')"
      >
        Crear Impuesto
      </button>
    </div>
    <section>
      <div class="card-body">
        <table class="table table-sm table-bordered table-responsive-sm">
          <thead class="thead-primary">
            <tr>
              <th scope="col">#</th>
              <th>Impuesto</th>
              <th scope="col">Porcentaje</th>
              <th>Estado</th>
              <th v-if="$root.validatePermission('tax.active')">Opciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(tax, index) in taxListing.data" :key="tax.id">
              <th scope="row">{{ index + 1 }}</th>
              <th>{{ tax.name }}</th>
              <td>{{ tax.percentage }}</td>

              <td v-if="$root.validatePermission('tax.active')">
                <button
                  class="btn btn-outline-success"
                  v-if="tax.active == 1"
                  @click="DeactivateTax(tax.id)"
                >
                  <i class="bi bi-check-circle-fill"></i>
                </button>
                <button
                  class="btn btn-outline-danger"
                  v-else
                  @click="ActivateTax(tax.id)"
                >
                  <i class="bi bi-x-circle"></i>
                </button>
              </td>
              <td v-if="$root.validatePermission('tax.update')">
                <button
                  class="btn btn-outline-success"
                  @click="ShowData(tax), (edit = true)"
                >
                  <i class="bi bi-pen"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
        <pagination
          :align="'center'"
          :data="taxListing"
          @pagination-change-page="listTaxes"
        >
          <span slot="prev-nav">&lt; Previous</span>
          <span slot="next-nav">Next &gt;</span></pagination
        >
      </div>
    </section>
    <!-- Modal para creacion y edicion de impuestos -->
    <div
      class="modal fade"
      id="taxModal"
      tabindex="-1"
      aria-labelledby="taxModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="taxModalLabel">Tax</h5>
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
            <create-edit-tax ref="CreateEditTax" 
              @list-taxes="listTaxes(1)"
            />
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-outline-secondary"
              @click="closeModal()"
            >
              Cerrar
            </button>
            <button
              type="button"
              class="btn btn-outline-primary"
              @click="SaveTax()"
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
import CreateEditTax from "./CreateEditTax.vue";
export default {
  components: { CreateEditTax },
  data() {
    return {
      taxListing: {},
      edit: false,
    };
  },
  created() {
    this.listTaxes(1);
  },
  methods: {
    listTaxes(page = 1) {
      let me = this;
      axios
        .get("api/taxes?page=" + page, this.$root.config)
        .then(function (response) {
          me.taxListing = response.data.taxes;
        });
    },
    SaveTax: function () {
      let me = this;
      if (this.edit == false) {
        this.$refs.CreateEditTax.CreateTax();
      } else {
        this.$refs.CreateEditTax.EditTax();
      }
    },

    ShowData: function (tax) {
      this.$refs.CreateEditTax.OpenEditTax(tax);
    },
    closeModal: function () {
      let me = this;
      this.$refs.CreateEditTax.ResetData();
      me.listTaxes(1);
    },
    ActivateTax: function (id) {
      let me = this;
      axios
        .post("api/taxes/" + id + "/activate", null, this.$root.config)
        .then(function () {
          me.listTaxes(1);
        });
    },
    DeactivateTax: function (id) {
      let me = this;
      axios
        .post("api/taxes/" + id + "/deactivate", null, this.$root.config)
        .then(function () {
          me.listTaxes(1);
        });
    },
  },
  mounted() {
    console.log("Component mounted.");
  },
};
</script>
