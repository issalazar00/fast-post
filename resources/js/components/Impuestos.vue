<template>
  <div class="col-12">
    <h3 class="page-header">Impuestos</h3>
    <div class="row justify-content-end mx-4">
      <router-link to="/crear-impuesto" class="btn btn-primary">Crear Impuesto</router-link>
    </div>

    <div class="card-body">
      <table class="table table-sm table-bordered table-responsive-sm">
        <thead class=" thead-primary">
          <tr>
            <th scope="col">#</th>
            <th scope="col">Impuesto</th>
            <th scope="col">Porcentaje</th>
            <th scope="col">Por defecto</th>
            <th>Opciones</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th scope="row">2</th>
            <td>IVA</td>
            <td>19%</td>
            <td>
              <span class="badge badge-success">No</span>
            </td>
            <td>
                <button class="btn btn-success">
                <i class="bi bi-check-circle-fill"></i>
              </button>
            </td>
          </tr>
         
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
export default {
  mounted() {
    console.log("Component mounted.");
  },
};
</script>
