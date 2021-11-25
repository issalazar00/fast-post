<template>
  <div>
    <div
      class="modal fade"
      id="assignUserModal"
      tabindex="-1"
      aria-labelledby="assignUserModalLabel"
      aria-hidden="true"
      data-backdrop="static"
    >
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="assignUserModalLabel">Asignar Usuarios</h5>
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
            <form id="formAssignUser">
              <div class="form-group">
                <label for="formGroupExampleInput">Nombre o Numero</label>
                <input
                  type="text"
                  class="form-control"
                  id="name"
                  placeholder="Ingresar nombre o número"
                  v-model="formBox.name"
                />
                <small id="nameHelp" class="form-text text-danger">{{
                  formErrors.name
                }}</small>
              </div>
              <div class="form-group">
                <label for="formGroupExampleInput">Prefijo</label>
                <input
                  type="text"
                  class="form-control"
                  id="prefix"
                  placeholder="Ingresar prefijo"
                  v-model="formBox.prefix"
                  :disabled ="formBox.process"
                />
                <small id="nameHelp" class="form-text text-danger">{{
                  formErrors.prefix
                }}</small>
              </div>  
            </form>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >
              Cerrar
            </button>
            <button
              type="button"
              class="btn btn-primary"
              @click="formBox.id ? EditBox() : CreateBox()"
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
export default {
  name: "AssignUser",
  data() {
    return {
      formBox: {},
      consecutive_boxErrors: {},
      formErrors: {
        name: "",
        prefix: "",
      },
      
    };
  },
  created() {},
  methods: {
    getConsecutiveAllByBox(box){
      axios.
      get("api/boxes/"+box+"/consecutiveAll", this.$root.config)
      .then(response => {
        this.formBox.consecutive_load = response.data.consecutive;
        this.consecutive_box = response.data.consecutive;
      })
      .catch(response => {
        this.consecutive_box = [];
      });
    },
    AssignUsers() {
      let me = this;
      me.assignErrors(false);
      me.formBox.consecutive_box = this.consecutive_box;
      axios
        .post("api/boxes", this.formBox, this.$root.config)
        .then(function () {
          me.ResetData();
          me.$emit("list-boxes");
        })
        .catch((response) => {
          me.assignErrors(response);
        });
    },
    OpenAssignUser(box) {
      let me = this;
      me.ResetData();
      $("#assignUserModal").modal("show");
      me.formBox = box;
      me.getConsecutiveAllByBox(me.formBox.id);
      
    },

    ResetData() {
      let me = this;
      $("#assignUserModal").modal("hide");
      me.consecutive_box = [];
      me.formBox = {
        name: "",
        prefix: "",
        process: null,
        consecutive_box: [],
        consecutive_load: []
      }
      me.assignErrors(false);
    },
    assignErrors(response) {
      if (response) {
        var errors = response.response.data.errors;
        this.consecutive_boxErrors = errors;

        if (errors.name) {
          this.formErrors.name = errors.name[0];
        }

        if (errors.prefix) {
          this.formErrors.prefix = errors.prefix[0];
        }
      } else {
        this.formErrors.name = "";
        this.formErrors.prefix = "";
        this.consecutive_boxErrors = {};      
      }
    },
  },
  mounted() {},
};
</script>