<template>
  <div class="page">
    <div class="page-header">
      <div class="row">
        <div class="col">
          <h3 class="page-title">Marcas</h3>
        </div>
        <div class="col">
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
      <div>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">First</th>
              <th scope="col">Last</th>
              <th scope="col">Handle</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th scope="row">1</th>
              <td>Mark</td>
              <td>Otto</td>
              <td>@mdo</td>
            </tr>
            <tr>
              <th scope="row">2</th>
              <td>Jacob</td>
              <td>Thornton</td>
              <td>@fat</td>
            </tr>
            <tr>
              <th scope="row">3</th>
              <td>Larry</td>
              <td>the Bird</td>
              <td>@twitter</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <create-edit-brand />
  </div>
</template>
<script>
import CreateEditBrand from "./CreateEditBrand.vue";
export default {
  data() {
    return {};
  },
  components: {
    CreateEditBrand,
  },
  created() {},
  methods: {},
};
</script>