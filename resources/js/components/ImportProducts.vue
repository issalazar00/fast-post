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
              <div>
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
      success: "",
    };
  },

  methods: {
    onFileChange(e) {
      //console.log(e.target.files[0]);
      this.filename = "Selected File: " + e.target.files[0].name;
      this.file = e.target.files[0];
    },
    uploadFile() {
      // e.preventDefault();
      console.log("hi");

      let currentObj = this;
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
          currentObj.success = response.data.success;
          currentObj.filename = "";
        })
        .catch(function (error) {
          currentObj.output = error;
        });
    },
  },
};
</script>