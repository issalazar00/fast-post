<template>
  <div class="container">
    <div class="row justify-content-center">
      <form>
        <div class="form-row">
          <div class="form-group col-6">
            <label for="exampleFormControlInput1">Cédula</label>
            <input
              type="number"
              class="form-control"
              id="exampleFormControlInput1"
              placeholder=""
            />
          </div>
          <div class="form-group col-6">
            <label for="exampleFormControlInput1">Nombre Proveedor</label>
            <input
              type="text"
              class="form-control"
              id="exampleFormControlInput1"
              placeholder=""
            />
          </div>
        </div>
        <div class="form-row">
          <div class="col-md-7 border-right border-gray">
            <div class="form-group">
              <label for="exampleFormControlInput1">Direccion</label>
              <input
                type="text"
                class="form-control"
                id="exampleFormControlInput1"
                placeholder=""
              />
            </div>

            <div class="form-group">
              <label for="exampleFormControlInput1">Télefono</label>
              <input
                type="text"
                class="form-control"
                id="exampleFormControlInput1"
                placeholder=""
              />
            </div>
            <div class="form-group">
              <label for="exampleFormControlInput1">Télefono móvil</label>
              <input
                type="text"
                class="form-control"
                id="exampleFormControlInput1"
                placeholder=""
              />
            </div>
            <div class="form-group">
              <label for="exampleFormControlInput1">Fax</label>
              <input
                type="text"
                class="form-control"
                id="exampleFormControlInput1"
                placeholder=""
              />
            </div>
            <div class="form-row">
              <span class="col-12">Contacto</span>
              <div class="form-group col-md-6 col-sm-6">
                <input
                  type="text"
                  class="form-control"
                  id="exampleFormControlInput1"
                  placeholder="Nombres"
                />
              </div>
              
              <div class="form-group col-md-3 col-sm-6">
                <input
                  type="text"
                  class="form-control"
                  id="exampleFormControlInput1"
                  placeholder="Apellidos"
                />
              </div>
              
            </div>
            <div class="form-group">
              <label for="exampleFormControlInput1">Correo electronico</label>
              <input
                type="enail"
                class="form-control"
                id="exampleFormControlInput1"
                placeholder=""
              />
            </div>
            <div class="form-group">
              <label for="exampleFormControlInput1">Otro correo</label>
              <input
                type="email"
                class="form-control"
                id="exampleFormControlInput1"
                placeholder=""
              />
            </div>

            <div class="form-row">
              <div class="form-group">
                <label for="exampleFormControlSelect1">Giro Neg.</label>
                <select class="form-control" id="exampleFormControlSelect1">
                  <option>Comercial</option>
                  <option>2</option>
                  <option>3</option>
                  <option>4</option>
                  <option>5</option>
                </select>
              </div>
              <div class="form-group">
                <label for="exampleFormControlSelect1">Tipo</label>
                <select class="form-control" id="exampleFormControlSelect1">
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
              <label for="exampleFormControlSelect1">Departamento</label>
              <select class="form-control" id="exampleFormControlSelect1">
                <option>1</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
                <option>5</option>
              </select>
            </div>
            <div class="form-group">
              <label for="exampleFormControlSelect1">Municipio</label>
              <select class="form-control" id="exampleFormControlSelect1">
                <option>1</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
                <option>5</option>
              </select>
            </div>
            <div class="form-group">
              <label for="inputEmail4">Código Postal</label>
              <input type="number" class="form-control" id="inputEmail4" />
            </div>
            
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <button
                  class="btn btn-outline-secondary dropdown-toggle"
                  type="button"
                  data-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false"
                >
                  Documento
                </button>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="#">Cédula</a>
                  <a class="dropdown-item" href="#">Pasaporte</a>                 
                </div>
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
  mounted() {
    console.log("Component mounted.");
  },
};
</script>
