<template>
  <div class="page">
    <div class="page-header">
      <div class="row">
        <div class="col">
          <h3 class="page-title">Marcas</h3>
        </div>
        <div class="col text-right">
          <button
            type="button"
            class="btn btn-outline-primary"
            data-toggle="modal"
            data-target="#brandModal"
          >
            Crear Marca
          </button>
        </div>
      </div>
    </div>
    <div class="page-content">
      <moon-loader
        class="m-auto"
        :loading="isLoading"
        :color="'#032F6C'"
        :size="100"
      />
      <div v-show="!isLoading">
        <section class="my-4">
          <table class="table table-sm table-bordered table-responsive-sm">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Nombre</th>
                <th scope="col">Estado</th>
                <th scope="col">Opciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="brand in BrandList.data" :key="brand.id">
                <th scope="row">{{ brand.id }}</th>
                <td>{{ brand.name }}</td>
                <td>Unactive</td>
                <td>@mdo</td>
              </tr>
            </tbody>
          </table>
        </section>
      </div>
    </div>
    <create-edit-brand />
  </div>
</template>
<script>
import CreateEditBrand from "./CreateEditBrand.vue";
export default {
  data() {
    return {
      isLoading: false,
      BrandList: {},
    };
  },
  components: {
    CreateEditBrand,
  },
  created() {
    this.listBrands(1);
  },
  methods: {
    listBrands(page = 1) {
      this.isLoading = true;
      let me = this;
      axios
        .get("api/brands?page=" + page)
        .then(function (response) {
          me.BrandList = response.data.brands;
        })
        .finally(() => (this.isLoading = false));
    },
  },
};
</script>