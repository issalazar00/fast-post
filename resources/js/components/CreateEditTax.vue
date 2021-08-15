<template>
  <div class="container">
    <div class="row justify-content-center">
      <form>
        <div class="form-group">
          <label for="percentage">Porcentaje</label>
          <input
            type="number"
            class="form-control"
            id="percentage"
            placeholder=""
            v-model="formTax.percentage"
          />
        </div>

        <div class="form-group">
          <div class="form-check">
            <input
              class="form-check-input"
              type="checkbox"
              value=""
              id="defaultCheck1"
              v-model="formTax.default"
            />
            <label class="form-check-label" for="defaultCheck1">
              Por defecto
            </label>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      formTax: {
        percentage: 0,
        default: 0,
      },
    };
  },
  methods: {
    CreateTax() {
      let me = this;
      axios.post("api/tax", this.formTax).then(function () {
        $("#taxModal").modal("hide");
        me.formTax = {};
      });
    },
    OpenEditTax(producto) {
      let me = this;
      $("#taxModal").modal("show");
      me.formTax = producto;
    },

    EditTax() {
      let me = this;
      axios.put("api/tax/" + this.formTax.id, this.formTax).then(function () {
        $("#taxModal").modal("hide");
        me.formTax = {};
      });
    },

    ResetData() {
      let me = this;
      $("#taxModal").modal("hide");
      me.formTax = {};
    },
  },
  mounted() {
    console.log("Component mounted.");
  },
};
</script>
