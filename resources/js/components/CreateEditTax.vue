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
          <small class="form-text text-danger">{{
            formErrors.percentage
          }}</small>
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
      formErrors: {
        percentage: "",
      },
    };
  },
  methods: {
    CreateTax() {
      let me = this;
      this.assignErrors(false);
      
      axios
        .post("api/tax", this.formTax, this.$root.config)
        .then(function () {
          $("#taxModal").modal("hide");
          me.formTax = {};
        })
        .catch((response) => {
          this.assignErrors(response);
        });
    },
    OpenEditTax(tax) {
      let me = this;
      $("#taxModal").modal("show");
      me.formTax = tax;
    },

    EditTax() {
      let me = this;
      this.assignErrors(false);

      axios
        .put("api/tax/" + this.formTax.id, this.formTax, this.$root.config)
        .then(function () {
          $("#taxModal").modal("hide");
          me.formTax = {};
        })
        .catch((response) => {
          this.assignErrors(response);
        });
    },
    ResetData() {
      let me = this;
      $("#taxModal").modal("hide");
      me.formTax = {};
    },
    assignErrors(response) {
      if (response) {
        var errors = response.response.data.errors;
        if (errors.percentage != "undefined") {
          this.formErrors.percentage = errors.percentage[0];
        }
      }else{
        this.formErrors.percentage = "";
      }
    },
  },
  mounted() {
    console.log("Component mounted.");
  },
};
</script>
