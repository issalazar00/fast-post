<template>
  <div class="col-12">
    <h3 class="page-header">Usuarios</h3>
    
    <div class="row justify-content-end mx-4">
      <router-link to="/crear-proveedor" class="btn btn-primary">Crear Proveedor</router-link>

    </div>

    <div class="card-body">
      <table class="table table-bordered table-sm">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Nombres</th>
            <th scope="col">Apellidos</th>
            <th scope="col">Direccion</th>
            <th>Telefono</th>
            <th>Correo</th>
            <th>Estado</th>
            <th>Opciones</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th scope="row">1</th>
            <td>Mark</td>
            <td>Otto</td>
            <td>B/ Los Cristales</td>
            <td>320 012 3456</td>
            <td>mail@mail.com</td>
            <td>
              <span class="badge badge-success">Activo</span>
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
