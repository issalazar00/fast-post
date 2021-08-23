<template>
  <div class="container">
    <div class="row justify-content-center">
      <form>
        <div class="form-group">
          <label for="name">Categoria</label>
          <input
            type="text"
            class="form-control"
            id="name"
            placeholder="Ingresar nombre"
            v-model="formCategory.name"
          />
          <small id="nameHelp" class="form-text text-danger">{{
            formErrors.name
          }}</small>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      formCategory: {
        name: "",
      },
      formErrors: {
        name: "",
      },
    };
  },
  methods: {
    CreateCategory() {
      let me = this;
      this.formErrors.name = "";

      axios
        .post("api/categories", this.formCategory, this.$root.config)
        .then(function () {
          $("#categoryModal").modal("hide");
          me.formCategory = {};
        })
        .catch((response) => {
          var errors = response.response.data.errors;
          if (errors.name != "undefined") {
            this.formErrors.name = errors.name[0];
          }
        });
    },
    OpenEditCategory(product) {
      let me = this;
      me.ResetData();
      $("#categoryModal").modal("show");
      me.formCategory = product;
    },

    EditCategory() {
      let me = this;
      axios
        .put(
          "api/categories/" + this.formCategory.id,
          this.formCategory,
          this.$root.config
        )
        .then(function () {
          $("#categoryModal").modal("hide");
          me.formCategory = {};
        })
        .catch((response) => {
          var errors = response.response.data.errors;
          if (errors.name != "undefined") {
            this.formErrors.name = errors.name[0];
          }
        });
    },
    ResetData() {
      let me = this;
      $("#categoryModal").modal("hide");
      me.formCategory = {};
      me.formErrors.name = "";
    },
  },
  mounted() {
    console.log("Component mounted.");
  },
};
</script>
