<template>
  <div class="container">
    <div class="row justify-content-center">
      <form>
        <div class="form-row">
          <div class="form-group col-5">
            <label for="barcode">Codigo de barras</label>
            <input
              type="number"
              step="any"
              class="form-control"
              id="barcode"
              v-model="formProduct.barcode"
              placeholder="Código de barras"
            />
          </div>
          <div class="form-group col-7">
            <label for="product">Descripcion Producto</label>
            <input
              type="text"
              class="form-control"
              id="product"
              v-model="formProduct.product"
              placeholder="Nombre o descripción de producto"
            />
          </div>
        </div>
        <div class="form-group">
          <div class="form-check form-check-inline">
            <input
              class="form-check-input"
              type="radio"
              name="type"
              id="unidad"
              v-model="formProduct.type"
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
              name="type"
              id="granel"
              v-model="formProduct.type"
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
              name="type"
              id="kit"
              v-model="formProduct.type"
              value="3"
            />
            <label class="form-check-label" for="kit">Como paquete</label>
          </div>
        </div>
        <div class="form-group">
          <label for="tax_id">Impuesto</label>
          <select
            class="form-control"
            id="tax_id"
            v-model="formProduct.tax_id"
          >
            <option>1</option>
            <option>2</option>
          </select>
        </div>
        <hr />
        <div class="form-row">
          <div class="form-group col-6">
            <label for="cost_price">Precio Costo</label>
            <input
              type="number"
              step="any"
              class="form-control"
              id="cost_price"
              v-model="formProduct.cost_price"
              placeholder="Precio de costo"
            />
          </div>
          <div class="form-group col-6">
            <label for="gain">Ganancia</label>
            <input
              type="number"
              step="any"
              class="form-control"
              id="gain"
              v-model="formProduct.gain"
              placeholder=""
            />
          </div>
          <div class="form-group col-6">
            <label for="sale_price">Precio Venta</label>
            <input
              type="number"
              step="any"
              class="form-control"
              id="sale_price"
              v-model="formProduct.sale_price"
              placeholder=""
            />
          </div>

          <div class="form-group col-6">
            <label for="wholesale">Precio Mayoreo</label>
            <input
              type="number"
              step="any"
              class="form-control"
              id="wholesale"
              v-model="formProduct.wholesale"
              placeholder=""
            />
          </div>
        </div>
        <hr />
        <div class="form-group">
          <label for="category">Categoria</label>
          <select
            class="form-control"
            id="category"
            v-model="formProduct.category_id"
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
              id="stock"
              v-model="formProduct.stock"
            />
            <label class="form-check-label" for="stock">
              ¿Usa Inventario?
            </label>
          </div>
        </div>
        <div class="form-row" v-if="formProduct.stock == 1">
          <div class="form-group col-md-9">
            <label for="quantity">Hay</label>
            <input
              type="number"
              step="any"
              class="form-control"
              id="quantity"
              v-model="formProduct.quantity"
            />
          </div>
          <div class="form-group col-md-3">
            <small id="" class="form-text text-muted mt-4">
              En este momento
            </small>
          </div>
        </div>
        <div class="form-row" v-if="formProduct.stock == 1">
          <div class="form-group col-md-9">
            <label for="minimum">Mínimo</label>
            <input
              type="number"
              step="any"
              class="form-control"
              id="minimum"
              v-model="formProduct.minimum"
            />
          </div>
        </div>
        <div class="form-row" v-if="formProduct.stock == 1">
          <div class="form-group col-md-9">
            <label for="maximum">Máximo</label>
            <input
              type="number"
              step="any"
              class="form-control"
              id="maximum"
              v-model="formProduct.maximum"
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
        barcode: "",
        producto: "",
        type: 0,
        tax_id: 0,
        cost_price: 0.0,
        gain: 0.0,
        sale_price: 0.0,
        wholesale: 0.0,
        category_id: 0,
        stock: 0,
        minimum: 0.0,
        quantity: 0.0,
        maximum: 0.0,
      },
    };
  },
  methods: {
   
  },
  mounted() {},
};
</script>
