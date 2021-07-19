<template>
  <div class="col-12">
    <h3 class="page-header">Productos</h3>
    <div class="row justify-content-end mx-4">
      <router-link to="/crear-producto" class="btn btn-primary"
        >Crear Producto</router-link
      >
    </div>

    <div class="card-body">
      <table class="table table-sm table-bordered table-responsive-sm">
        <thead class="thead-primary">
          <tr>
            <th scope="col">#</th>
            <th>Código de barras</th>
            <th scope="col">Producto</th>
            <th scope="col">Precio Venta</th>
            <th scope="col">Cantidad</th>
            <th>Opciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="producto in listadoProductos" v-bind:key="producto.id">
            <td>{{ producto.id_producto }}</td>
            <td>{{ producto.codigo_barras }}</td>
            <td>{{ producto.producto }}</td>
            <td>{{ producto.precio_venta }}</td>
            <td>{{ producto.cantidad_actual }}</td>
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
  data() {
    return {
      listadoProductos: [],
    };
  },
  created() {
    let me = this;
    axios.get("productos").then(function (response) {
      me.listadoProductos = response.data;
    });
  },

  mounted() {
    console.log("Component mounted.");
  },
};
</script>
