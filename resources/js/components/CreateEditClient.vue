<template>
  <div class="container">
    <div class="row justify-content-center">
      <form>
        <div class="form-row">
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <select class="custom-select" id="inputGroupSelect01">
                <option selected disabled value="">Documento...</option>
                <option value="1">Cédula de ciudadania</option>
                <option value="2">Cédula de extranjería</option>
                <option value="3">NIT</option>
              </select>
            </div>
            <input
              type="text"
              class="form-control"
              aria-label="Text input with dropdown button"
            />
            <input
              type="hidden"
              class="form-control"
              id="code"
              placeholder=""
              name="code"
              v-model="formClient.code"
            />
          </div>

          <div class="form-group col-6">
            <label for="name">Nombre / Razon social</label>
            <input
              type="text"
              class="form-control"
              id="name"
              placeholder=""
              name="name"
              v-model="formClient.name"
            />
          </div>
        </div>
        <div class="form-row">
          <div class="col-md-7 border-right border-gray">
            <div class="form-group">
              <label for="address">Direccion</label>
              <input
                type="text"
                class="form-control"
                id="address"
                placeholder=""
                name="address"
                v-model="formClient.address"
              />
            </div>

            <div class="form-group">
              <label for="mobile">Celular</label>
              <input
                type="text"
                class="form-control"
                id="mobile"
                placeholder=""
                name="mobile"
                v-model="formClient.mobile"
              />
            </div>
            <div class="form-row">
              <span class="col-12">Contacto</span>
              <div class="form-group">
                <input
                  type="text"
                  class="form-control"
                  id="contact"
                  placeholder="Nombres"
                  name="contact"
                  v-model="formClient.contact"
                />
              </div>
            </div>
            <div class="form-group">
              <label for="email">Correo electronico</label>
              <input
                type="enail"
                class="form-control"
                id="email"
                placeholder=""
                name="email"
                v-model="formClient.email"
              />
            </div>

            <div class="form-row">
              <div class="form-group">
                <label for="type_person">Tipo</label>
                <select
                  class="form-control"
                  id="type_person"
                  name="type_person"
                  v-model="formClient.type_person"
                >
                  <option>Juridica</option>
                  <option>Natural</option>
                </select>
              </div>
            </div>
          </div>
          <div class="col-md-5">
            <div class="form-group">
              <label for="departament">Departamento</label>
              <select
                class="form-control"
                id="departament"
                name="departament"
                v-model="formClient.departament"
              >
                <option>1</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
                <option>5</option>
              </select>
            </div>
            <div class="form-group">
              <label for="city">Municipio</label>
              <select
                class="form-control"
                id="city"
                name="city"
                v-model="formClient.city"
              >
                <option>1</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
                <option>5</option>
              </select>
            </div>

            <div class="form-group">
              <div class="form-check">
                <input
                  class="form-check-input"
                  type="checkbox"
                  value=""
                  id="defaultCheck1"
                />
                <label class="form-check-label" for="defaultCheck1">
                  Cliente está activo?
                </label>
              </div>
              <div class="form-check">
                <input
                  class="form-check-input"
                  type="checkbox"
                  value=""
                  id="impuesto_incluido"
                />
                <label class="form-check-label" for="impuesto_incluido">
                  Impuesto Incluido
                </label>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      formClient: {
        code: "",
        name: "",
        address: "",
        mobile: "",
        fax: "",
        contact: "",
        email: "",
        type_person: "",
        departament: "",
        city: "",
        type_document: "",
        document: "",
        active: "",
        tax: "",
      },
    };
  },
  mounted() {
    console.log("Component mounted.");
  },
};
</script>
