<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="header col-12">
        <h3>Crear Ticket</h3>
      </div>
      <hr class="hr" />

      <div class="col-12">
        <div class="form-row">
          <div class="form-group">
            <label for="product" class="">Código del producto: </label>
            <input
              type="text"
              id="product"
              name="product"
              placeholder="Código de barras"
              class="form-control"
            />
            <!-- <div class="col-auto"> -->
            <button class="btn btn-outline-primary">Añadir producto</button>
            <!-- </div> -->
          </div>

          <div class="form-group">
            <label for="cliente">Asignar Cliente</label>
            <input
              type="text"
              class="form-control"
              id="cliente"
              name="cliente"
              placeholder="cliente"
            />
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
              </tr>
            </thead>
            <tbody>
              <tr>
                <th scope="row">1</th>
                <td>444222</td>
                <td>Producto 1</td>
                <td>4</td>
                <td>$2000</td>
                <td>10%</td>
                <td>$4000</td>
              </tr>
              <tr>
                <th scope="row">1</th>
                <td>444222</td>
                <td>Producto 1</td>
                <td>4</td>
                <td>$2000</td>
                <td>10%</td>
                <td>$4000</td>
              </tr>
              <tr>
                <th scope="row">1</th>
                <td>444222</td>
                <td>Producto 1</td>
                <td>4</td>
                <td>$2000</td>
                <td>10%</td>
                <td>$4000</td>
              </tr>
              <tr>
                <th scope="row">1</th>
                <td>444222</td>
                <td>Producto 1</td>
                <td>4</td>
                <td>$2000</td>
                <td>10%</td>
                <td>$4000</td>
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
        </div>
      </section>
    </div>
  </div>
</template>