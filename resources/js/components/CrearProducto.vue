<template>
  <div class="container">
    <div class="row justify-content-center">
      <form>
        <div class="form-group">
          <label for="exampleFormControlInput1">Codigo de barras</label>
          <input
            type="number"
            class="form-control"
            id="exampleFormControlInput1"
            placeholder=""
          />
        </div>
        <div class="form-group">
          <label for="exampleFormControlInput1">Nombre Producto</label>
          <input
            type="text"
            class="form-control"
            id="exampleFormControlInput1"
            placeholder=""
          />
        </div>
        <div class="form-group">
          <label for="exampleFormControlInput1">Descripción</label>
          <input
            type="text"
            class="form-control"
            id="exampleFormControlInput1"
            placeholder=""
          />
        </div>
        <div class="form-group">
          <div class="form-check form-check-inline">
            <input
              class="form-check-input"
              type="radio"
              name="inlineRadioOptions"
              id="inlineRadio1"
              value="option1"
            />
            <label class="form-check-label" for="inlineRadio1"
              >Por Unidad / Pieza</label
            >
          </div>
          <div class="form-check form-check-inline">
            <input
              class="form-check-input"
              type="radio"
              name="inlineRadioOptions"
              id="inlineRadio2"
              value="option2"
            />
            <label class="form-check-label" for="inlineRadio2"
              >A granel (Usa decimales)</label
            >
          </div>
          <div class="form-check form-check-inline">
            <input
              class="form-check-input"
              type="radio"
              name="inlineRadioOptions"
              id="inlineRadio3"
              value="option3"
            />
            <label class="form-check-label" for="inlineRadio3"
              >Como paquete</label
            >
          </div>
        </div>
        <div class="form-group">
          <label for="exampleFormControlSelect1">Impuesto</label>
          <select class="form-control" id="exampleFormControlSelect1">
            <option>1</option>
            <option>2</option>
            <option>3</option>
            <option>4</option>
            <option>5</option>
          </select>
        </div>
        <div class="form-group">
          <label for="exampleFormControlInput1">Precio Costo</label>
          <input
            type="number"
            class="form-control"
            id="exampleFormControlInput1"
            placeholder=""
          />
        </div>
        <div class="form-group">
          <label for="exampleFormControlInput1">Ganancia</label>
          <input
            type="number"
            class="form-control"
            id="exampleFormControlInput1"
            placeholder=""
          />
        </div>
        <div class="form-group">
          <label for="exampleFormControlInput1">Precio Venta</label>
          <input
            type="number"
            class="form-control"
            id="exampleFormControlInput1"
            placeholder=""
          />
        </div>

        <div class="form-group">
          <label for="exampleFormControlInput1">Precio Mayoreo</label>
          <input
            type="number"
            class="form-control"
            id="exampleFormControlInput1"
            placeholder=""
          />
        </div>
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
        <hr />
        <div class="form-group">
          <div class="form-check">
            <input
              class="form-check-input"
              type="checkbox"
              value=""
              id="defaultCheck1"
            />
            <label class="form-check-label" for="defaultCheck1">
              ¿Usa Inventario?
            </label>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-9">
            <label for="inputEmail4">Hay</label>
            <input type="number" class="form-control" id="inputEmail4" />
          </div>
          <div class="form-group col-md-3">
            <small id="" class="form-text text-muted mt-4">
              En este momento
            </small>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-9">
            <label for="inputEmail4">Mínimo</label>
            <input type="number" class="form-control" id="inputEmail4" />
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-9">
            <label for="inputEmail4">Máximo</label>
            <input type="number" class="form-control" id="inputEmail4" />
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
