<template>
  <div class="col-12">
    <h3 class="page-header">Impuestos</h3>
    <div class="row justify-content-end mx-4">
      <router-link to="/crear-editar-impuesto" class="btn btn-primary"
        >Crear Impuesto</router-link
      >
    </div>

    <section>
      <div class="card-body">
        <table class="table table-sm table-bordered table-responsive-sm">
          <thead class="thead-primary">
            <tr>
              <th scope="col">#</th>
              <th scope="col">Porcentaje</th>
              <th scope="col">Por defecto</th>
              <th>Opciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(impuesto, index) in listadoImpuestos.data" :key="impuesto.id">
              <th scope="row">{{index+1}}</th>
              <td>{{impuesto.percentage}}</td>              
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
        <pagination
          :align="'center'"
          :data="listadoImpuestos"
          @pagination-change-page="listarImpuestos"
        >
          <span slot="prev-nav">&lt; Previous</span>
          <span slot="next-nav">Next &gt;</span></pagination
        >
      </div>
    </section>
  </div>
</template>

<script>
export default {
  data() {
    return {
      listadoImpuestos: {},
      editar: false,
    };
  },
  created() {
    this.listarImpuestos(1);
  },
  methods: {
    listarImpuestos(page = 1) {
      let me = this;
      axios.get("api/tax?page=" + page).then(function (response) {
        me.listadoImpuestos = response.data.taxes;
      });
    },
  },
  mounted() {
    console.log("Component mounted.");
  },
};
</script>
