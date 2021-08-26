<template>
  <div>
    <div
      class="modal fade"
      id="productImportModal"
      tabindex="-1"
      aria-labelledby="productImportModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="productImportModalLabel">
              Importación de productos
            </h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form enctype="multipart/form-data" @submit.prevent="uploadFile()">
            <div class="modal-body">
              <div class="alert alert-info text-center" role="alert">
                <span>Descargar plantilla para importación de productos</span>
                <a
                  href="import/download-example-import"
                  target="_blank"
                  class="btn btn-light border border-primary"
                  type="button"
                >
                  <i class="bi bi-cloud-download text-dark"></i> Descargar
                </a>
              </div>
              <div class="custom-file mt-4">
                <input
                  type="file"
                  class="custom-file-input"
                  name="filename"
                  id="filename"
                  v-on:change="onFileChange"
                />
                <label class="custom-file-label" for="filename"
                  >Subir archivo de importación</label
                >
              </div>
              {{ file != '' ? filename : '' }}

            </div>
            <div class="modal-footer">
              <button
                type="button"
                class="btn btn-secondary"
                data-dismiss="modal"
              >
                Cancelar
              </button>
              <button type="submit" class="btn btn-primary" value="upload">
                Importar
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      file: "",
      filename: "",
    };
  },

  methods: {
    onFileChange(e) {
      this.filename = "Archivo Seleccionado: " + e.target.files[0].name;
      this.file = e.target.files[0];
    },
    uploadFile() {
      console.log("hi");

      let me = this;
      const config = {
        headers: {
          "content-type": "multipart/form-data",
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
            .content,
        },
      };

      // form data
      let formData = new FormData();
      formData.append("file", this.file);

      // send upload request
      axios
        .post("import/upload-file-import", formData, config)
        .then(function (response) {
          me.filename = "";
        })
        .catch(function (error) {
          me.output = error;
        });
    },
  },
};
</script>