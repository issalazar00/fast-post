<template>
  <div class="container">
    <div class="row justify-content-center">
      <form>
        <div class="form-row">
          <div class="form-group col-6">
            <label for="code">Cédula</label>
            <input
              type="number"
              class="form-control"
              id="code"
              placeholder=""
              name="code"
              v-model="formSupplier.code"
            />
          </div>
          <div class="form-group col-6">
            <label for="name">Nombre Proveedor</label>
            <input
              type="text"
              class="form-control"
              id="name"
              placeholder=""
              name="name"
              v-model="formSupplier.name"
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
                v-model="formSupplier.address"
              />
            </div>

            <div class="form-group">
              <label for="phone">Télefono</label>
              <input
                type="text"
                class="form-control"
                id="phone"
                placeholder=""
                name="phone"
                v-model="formSupplier.phone"
              />
            </div>
            <div class="form-group">
              <label for="mobile">Télefono móvil</label>
              <input
                type="text"
                class="form-control"
                id="mobile"
                placeholder=""
                name="mobile"
                v-model="formSupplier.mobile"
              />
            </div>
            <div class="form-group">
              <label for="fax">Fax</label>
              <input
                type="text"
                class="form-control"
                id="fax"
                placeholder=""
                name="fax"
                v-model="formSupplier.fax"
              />
            </div>
            <div class="form-row">
              <span class="col-12">Contacto</span>
              <div class="form-group col-md-6 col-sm-6">
                <input
                  type="text"
                  class="form-control"
                  id="firstname_contact"
                  placeholder="Nombres"
                  name="firstname_contact"
                  v-model="formSupplier.firstname_contact"
                />
              </div>

              <div class="form-group col-md-3 col-sm-6">
                <input
                  type="text"
                  class="form-control"
                  id="lastname_contact"
                  placeholder="Apellidos"
                  name="lastname_contact"
                  v-model="formSupplier.lastname_contact"
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
                v-model="formSupplier.email"
              />
            </div>

            <div class="form-row">
              <div class="form-group">
                <label for="giro_neg">Giro Neg.</label>
                <select
                  class="form-control"
                  id="giro_neg"
                  name="giro_neg"
                  v-model="formSupplier.giro_neg"
                >
                  <option>Comercial</option>
                  <option>2</option>
                  <option>3</option>
                  <option>4</option>
                  <option>5</option>
                </select>
              </div>
              <div class="form-group">
                <label for="type_person">Tipo</label>
                <select
                  class="form-control"
                  id="type_person"
                  name="type_person"
                  v-model="formSupplier.type_person"
                >
                  <option>1</option>
                  <option>2</option>
                  <option>3</option>
                  <option>4</option>
                  <option>5</option>
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
                v-model="formSupplier.departament"
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
                v-model="formSupplier.city"
              >
                <option>1</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
                <option>5</option>
              </select>
            </div>
            <div class="form-group">
              <label for="zipcode">Código Postal</label>
              <input
                type="number"
                class="form-control"
                id="zipcode"
                name="zipcode"
                v-model="formSupplier.zipcode"
              />
            </div>

            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <select class="custom-select" id="inputGroupSelect01">
                  <option selected disabled value="">Documento...</option>
                  <option value="1">Cédula de ciudadania</option>
                  <option value="2">Cédula de extranjería</option>
                  <option value="3">Pasaporte</option>
                </select>
              </div>
              <input
                type="text"
                class="form-control"
                aria-label="Text input with dropdown button"
              />
            </div>
            <div class="form-group">
              <label for="inputEmail4">Reg. Empresarial</label>
              <input
                type="text"
                class="form-control"
                id="inputEmail4"
                autocomplete="country"
              />
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
                  Proveedor está activo?
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
      formSupplier: {
        code: "",
        name: "",
        address: "",
        phone: "",
        mobile: "",
        fax: "",
        fisrtname_contact: "",
        lastname_contact: "",
        email: "",
        giro_neg: "",
        type_person: "",
        departament: "",
        city: "",
        zipcode: "",
        type_document: "",
        document: "",
        business_registration: "",
        state: "",
        tax: "",
      },
    };
  },
  mounted() {
    console.log("Component mounted.");
  },
};
</script>
