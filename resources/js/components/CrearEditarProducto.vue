<template>
  <div class="container">
    <div class="row justify-content-center">
      <form>
        <div class="form-row">
          <div class="form-group col-5">
            <label for="codigo_barras">Codigo de barras</label>
            <input
              type="number"
              step="any"
              class="form-control"
              id="codigo_barras"
              v-model="formProduct.codigo_barras"
              placeholder="Código de barras"
            />
          </div>
          <div class="form-group col-7">
            <label for="´producto">Descripcion Producto</label>
            <input
              type="text"
              class="form-control"
              id="´producto"
              v-model="formProduct.producto"
              placeholder="Nombre o descripción de producto"
            />
          </div>
        </div>
        <div class="form-group">
          <div class="form-check form-check-inline">
            <input
              class="form-check-input"
              type="radio"
              name="tipo_producto"
              id="unidad"
              v-model="formProduct.tipo_producto"
              value="1"
            />
            <label class="form-check-label" for="unidad"
              >Por Unidad / Pieza</label
            >
          </div>
          <div class="form-check form-check-inline">
            <input
              class="form-check-input"
              type="radio"
              name="tipo_producto"
              id="granel"
              v-model="formProduct.tipo_producto"
              value="2"
            />
            <label class="form-check-label" for="granel"
              >A granel (Usa decimales)</label
            >
          </div>
          <div class="form-check form-check-inline">
            <input
              class="form-check-input"
              type="radio"
              name="tipo_producto"
              id="kit"
              v-model="formProduct.tipo_producto"
              value="3"
            />
            <label class="form-check-label" for="kit">Como paquete</label>
          </div>
        </div>
        <div class="form-group">
          <label for="id_impuesto">Impuesto</label>
          <select
            class="form-control"
            id="id_impuesto"
            v-model="formProduct.id_impuesto"
          >
            <option>1</option>
            <option>2</option>
          </select>
        </div>
        <hr />
        <div class="form-row">
          <div class="form-group col-6">
            <label for="precio_costo">Precio Costo</label>
            <input
              type="number"
              step="any"
              class="form-control"
              id="precio_costo"
              v-model="formProduct.precio_costo"
              placeholder="Precio de costo"
            />
          </div>
          <div class="form-group col-6">
            <label for="valor_ganancia">Ganancia</label>
            <input
              type="number"
              step="any"
              class="form-control"
              id="valor_ganancia"
              v-model="formProduct.valor_ganancia"
              placeholder=""
            />
          </div>
          <div class="form-group col-6">
            <label for="precio_venta">Precio Venta</label>
            <input
              type="number"
              step="any"
              class="form-control"
              id="precio_venta"
              v-model="formProduct.precio_venta"
              placeholder=""
            />
          </div>

          <div class="form-group col-6">
            <label for="precio_mayoreo">Precio Mayoreo</label>
            <input
              type="number"
              step="any"
              class="form-control"
              id="precio_mayoreo"
              v-model="formProduct.precio_mayoreo"
              placeholder=""
            />
          </div>
        </div>
        <hr />
        <div class="form-group">
          <label for="categoria">Categoria</label>
          <select
            class="form-control"
            id="categoria"
            v-model="formProduct.id_categoria"
          >
            <option>1</option>
            <option>2</option>
          </select>
        </div>

        <div class="form-group">
          <div class="form-check">
            <input
              class="form-check-input"
              type="checkbox"
              value="1"
              id="inventario"
              v-model="formProduct.inventario"
            />
            <label class="form-check-label" for="inventario">
              ¿Usa Inventario?
            </label>
          </div>
        </div>
        <div class="form-row" v-if="formProduct.inventario == 1">
          <div class="form-group col-md-9">
            <label for="cantidad_actual">Hay</label>
            <input
              type="number"
              step="any"
              class="form-control"
              id="cantidad_actual"
              v-model="formProduct.cantidad_actual"
            />
          </div>
          <div class="form-group col-md-3">
            <small id="" class="form-text text-muted mt-4">
              En este momento
            </small>
          </div>
        </div>
        <div class="form-row" v-if="formProduct.inventario == 1">
          <div class="form-group col-md-9">
            <label for="cantidad_minima">Mínimo</label>
            <input
              type="number"
              step="any"
              class="form-control"
              id="cantidad_minima"
              v-model="formProduct.cantidad_minima"
            />
          </div>
        </div>
        <div class="form-row" v-if="formProduct.inventario == 1">
          <div class="form-group col-md-9">
            <label for="cantidad_maxima">Máximo</label>
            <input
              type="number"
              step="any"
              class="form-control"
              id="cantidad_maxima"
              v-model="formProduct.cantidad_maxima"
            />
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
      //Variables de producto
      formProduct: {
        codigo_barras: "",
        producto: "",
        tipo_producto: 0,
        id_impuesto: 0,
        precio_costo: 0.0,
        valor_ganancia: 0.0,
        precio_venta: 0.0,
        precio_mayoreo: 0.0,
        id_categoria: 0,
        inventario: 0,
        cantidad_minima: 0.0,
        cantidad_actual: 0.0,
        cantidad_maxima: 0.0,
      },
    };
  },
  methods: {
   
  },
  mounted() {},
};
</script>
