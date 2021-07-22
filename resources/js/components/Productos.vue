<template>
  <div>
    <div class="col-12">
      <h3 class="page-header">Productos</h3>
      <div class="row justify-content-end mx-4">
        <button
          type="button"
          class="btn btn-primary"
          data-toggle="modal"
          data-target="#productModal"
          @click="edit = false"
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
              <th>Categoria</th>
              <th scope="col">Precio Venta</th>
              <th scope="col">Cantidad</th>
              <th>Estado</th>
              <th>Opciones</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="product in listadoProductos.data"
              v-bind:key="product.id"
            >
              <td>{{ product.id }}</td>
              <td>{{ product.barcode }}</td>
              <td>{{ product.product }}</td>
              <td>{{ product.category }}</td>
              <td class="text-right">$ {{ product.sale_price }}</td>
              <td>{{ product.quantity }}</td>
              <td>
                <button
                  class="btn btn-success"
                  v-if="product.state == 1"
                  @click="DesactivarProducto(product.id)"
                >
                  <i class="bi bi-check-circle-fill"></i>
                </button>
                <button
                  class="btn btn-danger"
                  v-else
                  @click="ActivarProducto(product.id)"
                >
                  <i class="bi bi-x-circle"></i>
                </button>
              </td>
              <td>

                <button
                  class="btn btn-success"
                  @click="MostrarDatos(product), (edit = true)"
                >
                  Editar
                </button>
              </td>
            </tr>
          </tbody>
        </table>
        <pagination
          :align="'center'"
          :data="listadoProductos"
          @pagination-change-page="listarProductos"
        >
          <span slot="prev-nav">&lt; Previous</span>
          <span slot="next-nav">Next &gt;</span></pagination
        >
      </div>
    </div>
    <!-- Modal para creacion y edicion de products -->
    <div
      class="modal fade"
      id="productModal"
      tabindex="-1"
      aria-labelledby="productModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="productModalLabel">Producto</h5>
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
            <crear-editar-producto ref="CrearEditarProducto" />
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              @click="CerrarModal()"
            >
              Close
            </button>
            <button
              type="button"
              class="btn btn-primary"
              @click="GuardarProducto()"
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
import CrearEditarProducto from "./CrearEditarProducto.vue";
export default {
  components: { CrearEditarProducto },
  data() {
    return {
      listadoProductos: {},
      edit: false,
    };
  },
  created() {
    this.listarProductos(1);
  },
  methods: {
    listarProductos(page = 1) {
      let me = this;
      axios.get("api/products?page=" + page).then(function (response) {
        me.listadoProductos = response.data.products;
      });
    },

    GuardarProducto: function () {
      let me = this;
      if (this.edit == false) {
        this.$refs.CrearEditarProducto.CrearProducto();
      } else {
        this.$refs.CrearEditarProducto.EditarProducto();
      }
      this.listarProductos(1);
    },

    MostrarDatos: function (product) {
      this.$refs.CrearEditarProducto.AbrirEdicionProducto(product);
    },
    CerrarModal: function () {
      let me = this;
      this.$refs.CrearEditarProducto.ResetarDatos();
      this.listarProductos(1);
    },
    ActivarProducto: function (id) {
      let me = this;
      axios.post("api/products/" + id + "/activate").then(function () {
        me.listarProductos(1);
      });
    },
    DesactivarProducto : function (id) {
      let me = this;
      axios.post("api/products/" + id + "/deactivate").then(function () {
        me.listarProductos(1);
      });
    },
  },

  mounted() {
  },
};
</script>
