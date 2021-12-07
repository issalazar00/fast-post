<template>


  <div>
    <div
    class="modal fade"
      id="staticBackdrop"
      data-backdrop="static"
      data-keyboard="false"
      tabindex="-1"
      aria-labelledby="staticBackdropLabel"
      aria-hidden="true"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Seleccionar Caja</h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close"
            @click="resetBox"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <select id="" class="form-control" v-model="$root.box">
            <option value="" disabled>Seleccione una caja</option>
            <option v-for="item in $root.listBoxes" :value="item.id" :key="item.id">
              {{ item.name + " - " + item.prefix }}
            </option>
          </select>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary"
            data-dismiss="modal"
            @click="resetBox"
          >
            Cancelar
          </button>
          <button type="button" class="btn btn-primary" @click="saveBox">
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
  name: "ModalBox",
  data() {
    return {};
  },
  created() {
    //this.$root.validateToken();

  },
  methods: {
    selectedBox() {
      axios
        .get("api/boxes/byUser", this.$root.config)
        .then((response) => {
          this.$root.listBoxes = response.data.boxes;
        })
        .catch((response) => {
          this.$root.listBoxes = [];
        });

      let box = localStorage.getItem("box_worker");
      if (box > 0) {
        this.$root.box = box;
      } else {
        $("#staticBackdrop").modal("show");
      }
    },
    saveBox() {
      localStorage.setItem("box_worker", this.$root.box);
      $("#staticBackdrop").modal("hide");
    },
    resetBox() {
      this.$root.box = "";
      localStorage.removeItem("box_worker");
    },
  },
};
</script>

<style>
</style>