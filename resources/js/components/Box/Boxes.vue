<template>
  <div class="page">
    <div class="page-header">
      <div class="row">
        <div class="col">
          <h3 class="page-title">Cajas</h3>
        </div>
        <div class="col text-right">
          <button
            type="button"
            class="btn btn-outline-primary"
            data-toggle="modal"
            data-target="#boxModal"
            v-if="$root.validatePermission('brand.store')"
            @click="$refs.CreateEditBox.ResetData()"
          >
            Crear Cajas
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
                <th scope="col">Prefijo</th>
                <td scope="col">Asignar usuarios</td>
                <th scope="col" v-if="$root.validatePermission('brand.active')">Estado</th>
                <th v-if="$root.validatePermission('brand.update')">Opciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="brand in boxList.data" :key="brand.id">
                <th scope="row">{{ brand.id }}</th>
                <td>{{ brand.name }}</td>
                <td>{{ brand.prefix }}</td>
                <th>
                  <button class="btn btn-outline-primary">
                    <i class="bi bi-person-plus-fill"></i>
                  </button>
                </th>
                <td v-if="$root.validatePermission('brand.active')">
                  <button
                    class="btn"
                    :class="
                      brand.active == 1
                        ? 'btn-outline-danger'
                        : 'btn-outline-success'
                    "
                    @click="changeState(brand.id)"
                  >
                    <i v-if="brand.active == 1" class="bi bi-x-circle"></i>
                    <i v-if="brand.active == 0" class="bi bi-check-circle"></i>
                  </button>
                </td>
                <td v-if="$root.validatePermission('brand.update')">
                  <button
                    class="btn btn-outline-success"
                    @click="ShowData(brand)"
                  >
                    <i class="bi bi-pen"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </section>
      </div>
    </div>
    <create-edit-box ref="CreateEditBox" @list-boxes="listBoxes(1)" />
    <assign-user ref="AssignUser"> </assign-user>
  </div>
</template>
<script>
import CreateEditBox from "./CreateEditBox.vue";
import CreateEditBox from "./AssignUser.vue";
export default {
  data() {
    return {
      isLoading: false,
      boxList: {},
    };
  },
  components: {
    CreateEditBox,
    AssignUser

  },
  created() {
    this.$root.validateToken();
    this.listBoxes(1);
  },
  methods: {
    listBoxes(page = 1) {
      this.isLoading = true;
      let me = this;
      axios
        .get("api/boxes?page=" + page, this.$root.config)
        .then(function (response) {
          me.boxList = response.data.boxes;
        })
        .finally(() => (this.isLoading = false));
    },
    ShowData: function (brand) {
      this.$refs.CreateEditBox.OpenEditBox(brand);
    },
    changeState: function (id) {
      let me = this;
      axios
        .post("api/boxes/" + id + "/activate", null, me.$root.config)
        .then(function () {
          me.listBoxes(1);
        });
    },
  },
};
</script>