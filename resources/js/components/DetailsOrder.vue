<template>
  <div>
    <div class="header">
      <div class="row justify-content-end mx-4">
        <router-link class="btn" to="/tickets">
          <i class="bi bi-eye"></i> Regresar
        </router-link>
      </div>
      <h3 class="h3 w-100">Ticket #0001</h3>
      <div class="mx-4">
        <div>Filtros:</div>
        <div class="form-row">
          <div class="form-group">
            <label for="barcode">Código de barras</label>
            <input
              type="text"
              name="barcode"
              id="barcode"
              class="form-control"
            />
          </div>
          <div class="form-group">
            <label for="product">Producto</label>
            <input
              type="text"
              name="barcode"
              id="barcode"
              class="form-control"
            />
          </div>
        </div>
      </div>
    </div>
    <section>
      <div class="card-body">
        <table class="table table-sm table-bordered table-responsive-sm">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th>Código de barras</th>
              <th scope="col">Producto</th>
              <th scope="col">Cantidad</th>
              <th scope="col">Precio Venta</th>
              <th>Importe</th>
              <th>Existencia</th>
              <th>Editar</th>
              <th>Eliminar</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th scope="row">1</th>
              <td>4000000002VF</td>
              <td>Producto 1</td>
              <td>5</td>
              <td>$5000</td>
              <td>$6000</td>
              <td>100000</td>
              <td>
                <button class="btn">
                  <i class="bi bi-pencil-square"></i>
                </button>
              </td>
              <td>
                <button class="btn">
                  <i class="bi bi-x-octagon-fill"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
          <li class="page-item disabled">
            <a class="page-link" href="#" tabindex="-1" aria-disabled="true"
              >Previous</a
            >
          </li>
          <li class="page-item"><a class="page-link" href="#">1</a></li>
          <li class="page-item"><a class="page-link" href="#">2</a></li>
          <li class="page-item"><a class="page-link" href="#">3</a></li>
          <li class="page-item">
            <a class="page-link" href="#">Next</a>
          </li>
        </ul>
      </nav>
    </section>
    <div class="footer"></div>
  </div>
</template>