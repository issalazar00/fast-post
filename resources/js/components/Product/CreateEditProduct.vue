<template>
  <div>
    <div
      class="modal fade"
      id="productModal"
      tabindex="-1"
      aria-labelledby="productModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg">
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
                  <label
                    class="form-check-label"
                    for="kit"
                    data-toggle="modal"
                    data-target="#addProductModal"
                    >Como paquete</label
                  >
                </div>
                <hr />
                <add-product-kit
                  v-if="formProduct.type == 3"
                  @add-product="addProduct($event)"
                />
                <table
                  class="table table-sm table-bordered table-secondary mt-2"
                  v-if="formProduct.type == 3"
                >
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Código de barras</th>
                      <th>Producto</th>
                      <th>Cantidad</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody v-if="listItemsKit.length > 0">
                    <tr v-for="(item, index) in listItemsKit" :key="item.id">
                      <td>{{ index }}</td>
                      <td>{{ item.barcode }}</td>
                      <td>{{ item.product }}</td>
                      <td>{{ item.quantity }}</td>
                      <td>
                        <button
                          class="btn btn-danger btn-sm"
                          @click="removeProduct(index, item.id)"
                        >
                          <i class="bi bi-trash"></i>
                        </button>
                      </td>
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr>
                      <td colspan="4">No se han añadido productos</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="form-row">
                <div class="form-group col-6">
                  <label for="category_id">Categoria</label>
                  <select
                    class="form-control"
                    id="category_id"
                    v-model="formProduct.category_id"
                  >
                    <option value="0">--Select--</option>
                    <option
                      v-for="category in categoryList.data"
                      :key="category.id"
                      :value="category.id"
                    >
                      {{ category.name }}
                    </option>
                  </select>
                </div>
                <div class="form-group col-6">
                  <label for="brand_id">Marca</label>
                  <select
                    class="form-control"
                    id="brand_id"
                    v-model="formProduct.brand_id"
                  >
                    <option value="0">--Select--</option>
                    <option
                      v-for="brand in brandList"
                      :key="brand.id"
                      :value="brand.id"
                    >
                      {{ brand.name }}
                    </option>
                  </select>
                </div>
              </div>
              <div class="form-group col-6">
                <label for="tax_id">Impuesto</label>
                <select
                  class="form-control"
                  id="tax_id"
                  v-model="formProduct.tax_id"
                  @click="uploadTax(formProduct.tax_id)"
                  required
                >
                  <option value="0">--Select--</option>
                  <option
                    v-for="t in taxList"
                    :key="t.percentage"
                    :value="t.id"
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
                  <div class="d-none">
                    {{ (formProduct.sale_price_tax_exc = sale_price_tax_exc) }}
                  </div>
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
                  <div class="d-none">
                    {{ (formProduct.gain = gain) }}
                  </div>
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
                  <div class="d-none">
                    {{
                      (formProduct.wholesale_price_tax_exc =
                        wholesale_price_tax_exc)
                    }}
                  </div>
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
import global from "../../services/global.js";
import AddProductKit from "./AddProductKit.vue";

export default {
  data() {
    return {
      //Variables de producto
      tax: {
        percentage: 19,
      },
      formProduct: {
        barcode: "",
        product: "",
        type: 0,
        tax_id: 0,
        cost_price: 0.0,
        gain: 0.0,
        sale_price_tax_exc: 0.0,
        sale_price_tax_inc: 0.0,
        wholesale_price_tax_exc: 0.0,
        wholesale_price_tax_inc: 0.0,
        category_id: 0,
        brand_id: 0,
        stock: 0,
        minimum: 0.0,
        quantity: 0.0,
        maximum: 0.0,
      },
      listItemsKit: [],
      taxList: [],
      categoryList: {},
      brandList: {},
    };
  },
  components: {
    AddProductKit,
  },
  computed: {
    gain: function () {
      var result = 0.0;
      if (
        this.formProduct.sale_price_tax_inc != 0 &&
        this.formProduct.tax_id != 0
      ) {
        result = parseFloat(
          this.formProduct.sale_price_tax_exc - this.formProduct.cost_price
        );
        return result;
      } else {
        result = parseFloat(
          this.formProduct.sale_price_tax_exc - this.formProduct.cost_price
        );
        return result;
      }
    },
    sale_price_tax_exc: function () {
      var result = 0.0;
      if (this.formProduct.tax_id != 0) {
        let percentage = this.tax.percentage / 100;
        result = Math.round(
          parseFloat(this.formProduct.sale_price_tax_inc) / (1 + percentage)
        ).toFixed(2);
        return result;
      } else {
        let percentage = this.tax.percentage / 100;
        result = Math.round(
          parseFloat(this.formProduct.sale_price_tax_inc) / (1 + percentage)
        ).toFixed(2);
        return result;
      }
    },
    wholesale_price_tax_exc() {
      var result = 0.0;
      if (this.formProduct.tax_id != 0) {
        let percentage = this.tax.percentage / 100;
        result = Math.round(
          parseFloat(this.formProduct.wholesale_price_tax_inc) /
            (1 + percentage)
        ).toFixed(2);

        return result;
      } else {
        let percentage = this.tax.percentage / 100;
        result = Math.round(
          parseFloat(this.formProduct.wholesale_price_tax_inc) /
            (1 + percentage)
        ).toFixed(2);
        return result;
      }
    },
  },
  methods: {
    listTaxes() {
      let me = this;
      axios.get("api/taxes", me.$root.config).then(function (response) {
        me.taxList = response.data.taxes.data;
      });
    },
    listBrands() {
      let me = this;
      axios.get("api/brands", me.$root.config).then(function (response) {
        me.brandList = response.data.brands.data;
      });
    },
    listCategories() {
      let me = this;
      axios
        .get("api/categories?page=1", me.$root.config)
        .then(function (response) {
          me.categoryList = response.data.categories;
        });
    },
    OpenEditProduct(product) {
      this.edit = true;
      let me = this;
      $("#productModal").modal("show");
      me.formProduct = product;
      me.uploadTax(product.tax_id);
    },
    CreateProduct() {
      let me = this;

      var data = { product: me.formProduct, itemListKit: me.listItemsKit };
      axios.post("api/products", data, me.$root.config).then(function () {
        $("#productModal").modal("hide");
        me.CloseModal();
      });
    },
    EditProduct() {
      let me = this;
      axios
        .put(
          "api/products/" + me.formProduct.id,
          [me.formProduct, me.listItemsKit],
          me.$root.config
        )
        .then(function () {
          $("#productModal").modal("hide");
        });
      me.CloseModal();
      me.edit = false;
    },
    ResetData() {
      let me = this;
      $("#productModal").modal("hide");
      Object.keys(this.formProduct).forEach(function (key, index) {
        me.formProduct[key] = "";
      });
    },

    uploadTax(tax_id) {
      let result = 0.0;
      if (tax_id > 0) {
        result = this.taxList.find((tax) => tax.id == tax_id);
        this.tax.percentage = result.percentage;
      }
    },
    CloseModal: function () {
      this.edit = false;
      this.ResetData();
      this.$emit("list-products");
    },
    addProduct(new_product) {
      let me = this;
      let result = false;
      // Verifica si el producto existe en la lista
      me.listItemsKit.filter((prod) => {
        if (new_product.barcode == prod.barcode) {
          result = true;
          if (result) {
            // Añade cantidad
            prod.quantity += 1;
          }
        }
      });

      if (!result) {
        // Sino, lo añade al array
        me.listItemsKit.push({
          product_id: new_product.id,
          barcode: new_product.barcode,
          quantity: 1,
          product: new_product.product,
        });
      }
    },
    removeProduct(index, detail_id = null) {
      this.listItemsKit.splice(index, 1);
      // if (detail_id != null) {
      //   axios.delete(`api/order-details/${detail_id}`, this.$root.config);
      // }
    },
    validatePermission(permission) {
      return global.validatePermission(this.$root.permissions, permission);
    },
  },
  created() {
    this.listTaxes();
    this.listCategories();
    this.listBrands();
  },

  mounted() {},
};
</script>
