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
            placeholder=""
            v-model="formCategory.name"
          />
        </div>

      </form>
    </div>
  </div>
</template>

<script>
export default {
  data (){
    return {
      formCategory : {
        name: ''
      }
    }
  },
  methods: {
    CreateCategory() {
      let me = this;
      axios.post("api/category", this.formCategory).then(function () {
        $("#categoryModal").modal("hide");
        me.formCategory = {};
      });
    },
    OpenEditCategory(producto) {
      let me = this;
      $("#categoryModal").modal("show");
      me.formCategory = producto;
    },

    EditCategory() {
      let me = this;
      axios
        .put("api/category/" + this.formCategory.id, this.formCategory)
        .then(function () {
          $("#categoryModal").modal("hide");
          me.formCategory = {};
        });
    },

    ResetData() {
      let me = this;
      $("#categoryModal").modal("hide");
      me.formCategory = {};
    },
  },
  mounted() {
    console.log("Component mounted.");
  },
};
</script>
