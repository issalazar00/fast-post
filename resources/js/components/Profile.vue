<template>
  <div class="col-12">
    <h3 class="text-center page-header">Configuración</h3>
    <div class="d-flex justify-content-center">

    </div>
  </div>
</template>
<script>


export default {
  data() {
    return {

    };
  },
  created() {
    this.$root.validateToken();
    //this.getCofiguration();
  },
  methods: {
    getCofiguration() {
      axios.get("api/configurations", this.$root.config).then((response) => {
        if (response.data.configuration) {
          this.formConfiguration = response.data.configuration;
          this.formConfiguration.condition_quotation = this.formConfiguration.condition_quotation ?? "";
        }
      });
    },
    saveConfiguration() {
      this.assignErrors(false);
      var form = new FormData($("#form_configuration")[0]);
      form.append("id", this.formConfiguration.id);
      form.set(
        "condition_quotation",
        this.formConfiguration.condition_quotation
      );

      axios
        .post("api/configurations", form, this.$root.config)
        .then((response) => {
          this.formConfiguration = response.data.configuration;
        })
        .catch((response) => {
          this.assignErrors(response);
        });
    },
    assignErrors(response) {
      const fillable = [
        "name",
        "legal_representative",
        "nit",
        "address",
        "email",
        "tax_regime",
        "telephone",
        "mobile",
        "file0",
        "printer",
        "condition_order",
        "condition_quotation",
      ];

      if (response) {
        var errors = response.response.data.errors;
        console.log(errors);

        fillable.forEach((index) => {
          if (errors[index] != undefined) {
            this.formErrors[index] = errors[index][0];
          }
        });
      } else {
        fillable.forEach((index) => {
          this.formErrors[index] = "";
        });
      }
    },

  }
};
</script>
