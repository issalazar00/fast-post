<template>
  <div>
    <div
      class="modal fade"
      id="productModal"
      tabindex="-1"
      aria-labelledby="productModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="productModalLabel">Producto</h5>
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
            <form>
              <div class="form-row">
                <div class="form-group col-5">
                  <label for="barcode">Codigo de barras</label>
                  <input
                    type="text"
                    step="any"
                    class="form-control"
                    id="barcode"
                    v-model="formProduct.barcode"
                    placeholder="Código de barras"
                  />
                </div>
                <div class="form-group col-7">
                  <label for="product">Descripción Producto</label>
                  <input
                    type="text"
                    class="form-control"
                    id="product"
                    v-model="formProduct.product"
                    placeholder="Nombre o descripción de product"
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
                    required
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
                  required
                >
                  <option value="0">--Select--</option>
                  <option
                    v-for="t in taxListing"
                    :key="t.id"
                    :value="t.id"
                    @click="tax.percentage = t.percentage"
                  >
                    {{ t.percentage }}
                  </option>
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
                  <label for="sale_price_tax_exc">Precio venta sin iva</label>
                  <input
                    type="number"
                    step="any"
                    class="form-control"
                    id="sale_price_tax_exc"
                    readonly
                    :value="formProduct.sale_price_tax_exc"
                    placeholder=""
                  />
                </div>
                <div class="form-group col-6">
                  <label for="gain">Ganancia</label>
                  <input
                    type="number"
                    step="any"
                    class="form-control"
                    id="gain"
                    placeholder=""
                    readonly="readonly"
                    v-model="formProduct.gain"
                  />
                </div>
                <div class="form-group col-6">
                  <label for="sale_price_tax_inc">Precio venta con iva</label>
                  <input
                    type="number"
                    step="any"
                    class="form-control"
                    id="sale_price_tax_inc"
                    v-model="formProduct.sale_price_tax_inc"
                    placeholder=""
                  />
                </div>
                <div class="form-group col-6">
                  <label for="wholesale_price_tax_exc"
                    >Precio Mayoreo sin iva</label
                  >
                  <input
                    type="number"
                    step="any"
                    class="form-control"
                    id="wholesale_price_tax_exc"
                    v-model="formProduct.wholesale_price_tax_exc"
                    placeholder=""
                    readonly
                  />
                </div>
                <div class="form-group col-6">
                  <label for="wholesale_price_tax_inc"
                    >Precio Mayoreo con iva</label
                  >
                  <input
                    type="number"
                    step="any"
                    class="form-control"
                    id="wholesale_price_tax_inc"
                    v-model="formProduct.wholesale_price_tax_inc"
                  />
                </div>
              </div>
              <hr />
              <div class="form-group">
                <label for="category_id">Categoria</label>
                <select
                  class="form-control"
                  id="category_id"
                  v-model="formProduct.category_id"
                >
                  <option value="0">--Select--</option>
                  <option
                    v-for="category in categoriesListing"
                    :key="category.id"
                    :value="category.id"
                  >
                    {{ category.name }}
                  </option>
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
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              @click="CloseModal()"
            >
              Close
            </button>
            <button
              type="button"
              class="btn btn-primary"
              @click="formProduct.id ? EditProduct() : CreateProduct()"
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
  data() {
    return {
      //Variables de product
      edit: false,
      tax: {
        percentage: 19,
      },
      formProduct: {
        barcode: "",
        product: "",
        type: 0,
        tax_id: 1,
        cost_price: 0.0,
        gain: 0.0,
        sale_price_tax_exc: 0.0,
        sale_price_tax_inc: 0.0,
        wholesale_price_tax_exc: 0.0,
        wholesale_price_tax_inc: 0.0,
        category_id: 0,
        stock: 0,
        minimum: 0.0,
        quantity: 0.0,
        maximum: 0.0,
      },
      taxListing: {},
      categoriesListing: {},
    };
  },
  components: {},
  computed: {
    gain: function () {
      return parseFloat(
        (this.formProduct.gain =
          this.formProduct.sale_price_tax_exc - this.formProduct.cost_price)
      );
    },
    sale_price_tax_exc: function () {
      let percentage = this.tax.percentage / 100;
      return (this.formProduct.sale_price_tax_exc =
        parseFloat(this.formProduct.sale_price_tax_inc) / (1 + percentage));
    },
    wholesale_price_tax_exc() {
      let percentage = this.tax.percentage / 100;
      return (this.formProduct.wholesale_price_tax_exc = Math.round(
        parseFloat(this.formProduct.wholesale_price_tax_inc) / (1 + percentage)
      ));
    },
  },
  methods: {
    listTaxes() {
      let me = this;
      axios.get("api/tax").then(function (response) {
        me.taxListing = response.data.taxes.data;
      });
    },
    listCategories() {
      let me = this;
      axios.get("api/category").then(function (response) {
        me.categoriesListing = response.data.categories.data;
      });
    },
    OpenEditProduct(product) {
      this.edit = true;
      let me = this;
      $("#productModal").modal("show");
      me.formProduct = product;
    },
    CreateProduct() {
      let me = this;
      axios.post("api/products", this.formProduct).then(function () {
        $("#productModal").modal("hide");
        me.formProduct = {};
      });
    },
    EditProduct() {
      let me = this;
      axios
        .put("api/products/" + this.formProduct.id, this.formProduct)
        .then(function () {
          $("#productModal").modal("hide");
          me.formProduct = {};
        });
      this.edit = false;
    },
    ResetData() {
      let me = this;
      $("#productModal").modal("hide");
      Object.keys(this.formProduct).forEach(function (key, index) {
        me.formProduct[key] = "";
      });
    },

    uploadTax(tax) {
      console.log(tax);
      let me = this;
      // me.formProduct.tax_id = tax.id;
    },
    CloseModal: function () {
      this.edit = false;
      this.ResetData();
      this.$emit("list-products");
    },
  },
  created() {
    this.listTaxes();
    this.listCategories();
  },

  mounted() {},
};
</script>
