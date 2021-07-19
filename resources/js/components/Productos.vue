<template>
  <div>
    <div class="col-12">
      <h3 class="page-header">Productos</h3>
      <div class="row justify-content-end mx-4">
        <button
          type="button"
          class="btn btn-primary"
          data-toggle="modal"
          data-target="#exampleModal"
        >
          Crear Producto
        </button>
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
              <th>Estado</th>
              <th>Opciones</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="producto in listadoProductos.data"
              v-bind:key="producto.id"
            >
              <td>{{ producto.id_producto }}</td>
              <td>{{ producto.codigo_barras }}</td>
              <td>{{ producto.producto }}</td>
              <td class="text-right">$ {{ producto.precio_venta }}</td>
              <td>{{ producto.cantidad_actual }}</td>
              <td>
                <span class="badge badge-success" v-if="producto.estado == 1"
                  >Activo</span
                >
                <span class="badge badge-danger" v-else>Desactivado</span>
              </td>
              <td>
                <button class="btn btn-success">
                  <i class="bi bi-check-circle-fill"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
        <pagination
          :data="listadoProductos"
          @pagination-change-page="listarProductos"
        >
          <span slot="prev-nav">&lt; Previous</span>
          <span slot="next-nav">Next &gt;</span></pagination
        >
      </div>
    </div>
    <!-- Modal para creacion y edicion de productos -->
    <div
      class="modal fade"
      id="exampleModal"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Producto</h5>
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
            <crear-producto />
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >
              Close
            </button>
            <button type="button" class="btn btn-primary">Guardar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import CrearProducto from "./CrearProducto.vue";
export default {
  components: { CrearProducto },
  data() {
    return {
      listadoProductos: {},
    };
  },
  created() {
    this.listarProductos(1);
  },
  methods: {
    listarProductos(page = 1) {
      let me = this;
      axios.get("productos?page=" + page).then(function (response) {
        me.listadoProductos = response.data;
      });
    },
  },

  mounted() {
    // this.listarProductos();
  },
};
</script>
