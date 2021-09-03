<template>
  <div class="col-12">
    <div class="w-100 text-center">
      <h3 class="page-header">Categorias</h3>

      <moon-loader
        class="m-auto"
        :loading="isLoading"
        :color="'#032F6C'"
        :size="100"
      />
    </div>

    <div class="card-body">
      <section v-if="!isLoading">
        <div class="row justify-content-end my-4">
          <button
            type="button"
            class="btn btn-primary"
            data-toggle="modal"
            data-target="#categoryModal"
            @click="($refs.CreateEditCategory.ResetData()),(edit = false)"
            v-if="$root.validatePermission('category.store')"
          >
            Crear Categoria
          </button>
        </div>
        <table class="table table-sm table-bordered table-responsive-sm">
          <thead class="thead-primary">
            <tr>
              <th scope="col">#</th>
              <th scope="col">Categoria</th>
              <th v-if="$root.validatePermission('category.active')">Estado</th>
              <th v-if="$root.validatePermission('category.update')">Opciones</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(category, index) in categoryList.data"
              :key="category.id"
            >
              <th scope="row">{{ index + 1 }}</th>
              <td>{{ category.name }}</td>
              <td v-if="$root.validatePermission('category.active')">
                <button
                  class="btn btn-success"
                  v-if="category.state == 1"
                  @click="DeactivateCategory(category.id)"
                >
                  <i class="bi bi-check-circle-fill"></i>
                </button>
                <button
                  class="btn btn-danger"
                  v-else
                  @click="ActivateCategory(category.id)"
                >
                  <i class="bi bi-x-circle"></i>
                </button>
              </td>
              <td v-if="$root.validatePermission('category.update')">
                <button
                  class="btn btn-success"
                  @click="ShowData(category), (edit = true)"
                >
                  Editar
                </button>
              </td>
            </tr>
          </tbody>
        </table>
        <pagination
          :align="'center'"
          :data="categoryList"
          @pagination-change-page="listCategories"
        >
          <span slot="prev-nav">&lt; Previous</span>
          <span slot="next-nav">Next &gt;</span></pagination
        >
      </section>
    </div>

    <!-- Modal para creacion y edicion de categorys -->
    <div
      class="modal fade"
      id="categoryModal"
      tabindex="-1"
      aria-labelledby="categoryModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="categoryModalLabel">Categoria</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <create-edit-category ref="CreateEditCategory" @list-categories ="listCategories(1)" />
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              @click="closeModal()"
            >
              Close
            </button>
            <button
              type="button"
              class="btn btn-primary"
              @click="SaveCategory()"
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
import CreateEditCategory from "./CreateEditCategory.vue";

export default {
  components: { CreateEditCategory },
  data() {
    return {
      isLoading: false,
      categoryList: {},
      edit: false,
    };
  },
  created() {
    this.listCategories(1);
  },
  methods: {
    listCategories(page = 1) {
      this.isLoading = true;
      let me = this;

      axios
        .get("api/categories?page=" + page, this.$root.config)
        .then(function (response) {
          me.categoryList = response.data.categories;
        })

        .finally(() => (this.isLoading = false));
    },
    SaveCategory: function () {
      let me = this;
      if (this.edit == false) {
        this.$refs.CreateEditCategory.CreateCategory();
      } else {
        this.$refs.CreateEditCategory.EditCategory();
      }
      me.listCategories(1);
    },

    ShowData: function (category) {
      this.$refs.CreateEditCategory.OpenEditCategory(category);
    },
    closeModal: function () {
      let me = this;
      this.$refs.CreateEditCategory.ResetData();
      me.listCategories(1);
    },
    ActivateCategory: function (id) {
      let me = this;
      axios
        .post("api/categories/" + id + "/activate", null, me.$root.config)
        .then(function () {
          me.listCategories(1);
        });
    },
    DeactivateCategory: function (id) {
      let me = this;
      axios.post("api/categories/" + id + "/deactivate", null, me.$root.config).then(function (res) {
        me.listCategories(1);
      });
    }
  },
  mounted() {
    console.log("Component mounted.");
  },
};
</script>
