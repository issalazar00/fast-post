<template>
  <div>
    <div class="page-header d-flex justify-content-between p-4 border my-2">
      <h3 class="col-6">Reporte de egresos</h3>
      <ul class="list-group col-6">
        <li class="list-group-item">
          <h5 class="text-dark font-weight-bold">Total egresos: {{ dataExpenseTotal.price | currency }}</h5>
        </li>
      </ul>
    </div>
    <div class="page-search p-4 border my-2">
      <h6 class="text-primary text-uppercase">Filtrar:</h6>
      <form class="w-100">
        <div class="form-row">
          <div class="form-group col-12 col-md-3">
            <label for="search_from">Desde:</label>
            <input type="date" id="search_from" name="search_from" class="form-control" placeholder="Desde"
              v-model="search_from" :max="now" />
          </div>
          <div class="form-group col-12 col-md-3">
            <label for="search_to">Hasta:</label>
            <input type="date" id="search_to" name="search_to" class="form-control" placeholder="Hasta"
              v-model="search_to" :max="now" />
          </div>
          <div class="form-group col-12 col-md-3">
            <label for="search_type_output">Tipo de salida:</label>
            <input type="text" id="search_type_output" name="search_type_output" class="form-control"
              placeholder="Tipo de salida" v-model="search_type_output" />
          </div>
          <div class="form-group col-12 col-md-3">
            <label for="">Mostrar {{ search_results }} resultados:</label>
            <input type="number" id="search_results" name="search_results" class="form-control" placeholder="Desde"
              v-model="search_results" />
          </div>
        </div>
        <div class="form-row text-right m-auto">
          <div class="form-group offset-md-6 col-md-3 col-12 ">
            <button class="btn btn-success w-100" type="button" @click="listExpenses(1)">
              <i class="bi bi-search"></i> Buscar
            </button>
          </div>
          <div class="form-group col-md-3 col-12  ">
            <download-excel class="btn btn-primary w-100" :fields="json_fields" :data="ExpenseList.data"
              name="report-expenses.xls" type="xls">
              <i class="bi bi-file-earmark-arrow-down-fill"></i> Descargar .xls
            </download-excel>
          </div>
        </div>
      </form>
    </div>
    <div class="page-content">
      <section class="table-responsive">
        <table class="table table-bordered table-sm">
          <thead>
            <tr class="text-center">
              <th>ID</th>
              <th>Tipo</th>
              <th>Afectación</th>
              <th>Valor</th>
            </tr>
          </thead>
          <tbody v-if="ExpenseList.data &&
            ExpenseList.data.length > 0
            ">
            <tr v-for="r in ExpenseList.data" :key="r.id">
              <td>{{ r.id }}</td>
              <td>{{ r.type_output }}</td>
              <td>{{ r.description | affectation('expense') }}</td>
              <td class="text-right">{{ r.price | currency }}</td>
            </tr>
          </tbody>
        </table>
        <pagination :align="'center'" :data="ExpenseList" :limit="2" @pagination-change-page="listExpenses">
          <span slot="prev-nav"><i class="bi bi-chevron-double-left"></i></span>
          <span slot="next-nav"><i class="bi bi-chevron-double-right"></i></span>
        </pagination>
      </section>
    </div>
  </div>
</template>

<script>
import { dollarFilter } from '../../filters'
export default {
  data() {
    return {
      ExpenseList: {},
      dataExpenseTotal: {},
      search_from: "",
      search_to: "",
      search_type_output: "",
      now: moment().format('YYYY-MM-DD'),
      json_fields: {

        'Tipo': {
          field: 'type_output',
          callback: (value) => value
        },
        'Afectación': {
          field: 'description',
          callback: (value) => {
            return this.$options.filters.affectation(value, 'expense');
          }
        },
        'Valor egresos': {
          field: 'price',
          callback: (value) => {
            return this.$options.filters.currency(value, 'export');
          }
        }
      },
      search_results: 15
    };
  },
  mounted() {
    this.listExpenses(1);
  },
  methods: {
    listExpenses(page = 1) {
      let me = this;

      let data = {
        page: page,
        from: this.search_from,
        to: this.search_to,
        type_output: this.search_type_output,
        results: this.search_results
      }

      axios
        .get(
          `api/reports/expenses`,
          {
            params: data,
            headers: this.$root.config.headers,
          }
        )
        .then(function (response) {
          me.ExpenseList = response.data.expenses;
          me.dataExpenseTotal = response.data.totals;
        });
    },
  },
};
</script>
