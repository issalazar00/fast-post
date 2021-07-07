<template>
  <div class="col-12">
    <h3 class="page-header">Productos</h3>
    <div class="col-12">
      <router-link to="/crear-producto" class="btn btn-primary">Crear Producto</router-link>
    </div>

    <div class="card-body">
      <table class="table table-sm table-bordered table-responsive-sm">
        <thead class=" thead-primary">
          <tr>
            <th scope="col">#</th>
            <th scope="col">Producto</th>
            <th scope="col">Precio</th>
            <th scope="col">Cantidad</th>
            <th>Opciones</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th scope="row">2</th>
            <td>Jacob</td>
            <td>$ 20000</td>
            <td>@fat</td>
            <td></td>
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
