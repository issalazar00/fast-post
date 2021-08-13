<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="header col-12">
        <h3>Crear Ticket</h3>
      </div>
      <hr class="hr" />

      <div class="row">
        <div class="input-group">
          <input
            type="text"
            class="form-control"
            placeholder="Código de barras | Nombre de producto"
            aria-label=" with two button addons"
            aria-describedby="button-addon4"
          />
          <div class="input-group-append" id="button-addon4">
            <button class="btn btn-outline-secondary" type="button">
              Añadir Producto
            </button>
            <button
              class="btn btn-outline-secondary"
              type="button"
              data-toggle="modal"
              data-target="#addProductModal"
              @click="listProducts()"
            >
              <i class="bi bi-card-checklist"></i>
            </button>
          </div>
        </div>
        <div class="input-group">
          <input
            type="text"
            class="form-control"
            :placeholder="order.id_client != 0 ? 'Isabella' : 'No aplica'"
            aria-label=" with two button addons"
            aria-describedby="button-addon4"
          />
          <div class="input-group-append" id="button-addon4">
            <button class="btn btn-outline-secondary" type="button">
              Añadir Cliente
            </button>
            <button
              class="btn btn-outline-secondary"
              type="button"
              data-toggle="modal"
              data-target="#addClientModal"
              @click="listClients()"
            >
              <i class="bi bi-person-lines-fill"></i>
            </button>
          </div>
        </div>
      </div>

      <section>
        <div>
          <table
            class="
              table table-sm table-responsive-sm table-bordered table-hover
            "
          >
            <thead>
              <tr>
                <th>#</th>
                <th>Código</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Descuento %</th>
                <th>Total</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <th scope="row">1</th>
                <td>444222</td>
                <td>Producto 1</td>
                <td>
                  <input
                    type="number"
                    name="quantity"
                    id="quantity"
                    step="any"
                    placeholder="Cantidad"
                  />
                </td>
                <td>$2000</td>
                <td>
                  <input
                    type="number"
                    name="discount"
                    id="discount"
                    step="any"
                    placeholder="Descuento"
                  />
                </td>
                <td>$4000</td>
                <td>
                  <button class="btn"><i class="bi bi-trash"></i></button>
                </td>
              </tr>
              <tr>
                <th colspan="6">Subtotal:</th>
                <th>$4000</th>
              </tr>
              <tr>
                <th colspan="5">Descuento:</th>
                <th>10%</th>
                <th>$400</th>
              </tr>
              <tr>
                <th colspan="5">Misc:</th>
                <th>10%</th>
                <th>$400</th>
              </tr>
              <tr>
                <th colspan="6">Total:</th>
                <th>$4000</th>
              </tr>
            </tbody>
          </table>
          <div class="text-right">
            <button type="button" class="btn btn-outline-primary btn-block">
              <i class="bi bi-receipt"></i> Crear Orden
            </button>
          </div>
        </div>
      </section>
    </div>

    <div
      class="modal fade"
      id="addProductModal"
      tabindex="-1"
      aria-labelledby="addProductModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addProductModalLabel">Client</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Cerrar"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="input-group">
              <input
                type="text"
                class="form-control"
                placeholder="Código de barras | Nombre de producto"
                aria-label=" with two button addons"
                aria-describedby="button-addon4"
              />
              <div class="input-group-append" id="button-addon4">
                <button
                  class="btn btn-outline-secondary"
                  type="button"
                  @click="searchClient()"
                >
                  Buscar Cliente
                </button>
              </div>
            </div>
            <table class="table table-sm table-bordered table-responsive-sm">
              <thead class="thead-primary">
                <tr>
                  <th scope="col">#</th>
                  <th>Código de barras</th>
                  <th scope="col">Producto</th>
                  <th>Categoria</th>
                  <th scope="col">Precio Venta</th>
                  <th scope="col">Cantidad</th>
                  <th>Estado</th>
                  <th>Opciones</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="product in listingProducts.data"
                  v-bind:key="product.id"
                >
                  <td>{{ product.id }}</td>
                  <td>{{ product.barcode }}</td>
                  <td>{{ product.product }}</td>
                  <td>{{ product.category }}</td>
                  <td class="text-right">$ {{ product.sale_price }}</td>
                  <td>{{ product.quantity }}</td>

                  <td>
                    <button
                      class="btn btn-success"
                      @click="addProduct(product.id)"
                    >
                      <i class="bi bi-plus-circle"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >
              Cerrar
            </button>
          </div>
        </div>
      </div>
    </div>

    <div
      class="modal fade"
      id="addClientModal"
      tabindex="-1"
      aria-labelledby="addClientModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addClientModalLabel">Client</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Cerrar"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="input-group">
              <input
                type="text"
                class="form-control"
                placeholder="Código de barras | Nombre de producto"
                aria-label=" with two button addons"
                aria-describedby="button-addon4"
              />
              <div class="input-group-append" id="button-addon4">
                <button
                  class="btn btn-outline-secondary"
                  type="button"
                  @click="searchClient()"
                >
                  Buscar Cliente
                </button>
              </div>
            </div>
            <table class="table table-bordered table-sm">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Nombres</th>
                  <th>Documento</th>
                  <th scope="col">Direccion</th>
                  <th>Telefono</th>
                  <th>Correo</th>
                  <th>Contacto</th>
                  <th>Estado</th>
                  <th>Opciones</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="client in listingClient.data" v-bind:key="client.id">
                  <th scope="row">{{ client.code }}</th>
                  <td>{{ client.name }}</td>
                  <td>{{ client.document }}</td>
                  <td>{{ client.address }}</td>
                  <td>{{ client.mobile }}</td>
                  <td>{{ client.email }}</td>
                  <td>
                    {{ client.contact }}
                  </td>
                  <td>
                    <span class="badge badge-success">Activo</span>
                  </td>
                  <td>
                    <button class="btn" @click="addClient(client.id)">
                      <i class="bi bi-plus-circle"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >
              Cerrar
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
      listingProducts: {},
      listingClient: {},
      order: {
        id_client: 0,
        productsOrder: {
          id_produc: 0,
          name: "",
          price: 0.0,
          quantity: 0.0,
          discount: 0,
        },
      },
    };
  },
  methods: {
    addProduct() {},
    addClient() {},
    listProducts() {
      let me = this;
      axios.get("api/products").then(function (response) {
        me.listingProducts = response.data.products;
      });
    },
    searchProduct() {},
    listClients() {
      let me = this;
      axios.get("api/clients").then(function (response) {
        me.listingClient = response.data.clients;
      });
    },
    searchClient() {},
  },
};
</script>