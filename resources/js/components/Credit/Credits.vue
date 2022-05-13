<template>
  <div class="w-100">
    <header class="page-header justify-content-between row px-4">
      <h3>Compras</h3>
      <router-link
        class="btn btn-outline-primary"
        :to="{
          name: 'create-edit-credit',
          params: { credit_id: 0 },
        }"
        v-if="$root.validatePermission('credit.store')"
      >
        Nueva compra
      </router-link>
    </header>
    <section>
      <div class="card-body">
        <div class="form-row">
          <h6 class="w-100">Buscar...</h6>
          <div class="form-group col-3">
            <label for="nro_factura">Nro Factura</label>
            <input
              type="text"
              name="nro_factura"
              id="nro_factura"
              class="form-control"
              placeholder="Nro Factura"
              v-model="filter.no_invoice"
            />
          </div>
          <div class="form-group col-3">
            <label for="name_client">Cliente</label>
            <input
              type="text"
              name="name_client"
              id="name_client"
              class="form-control"
              placeholder="Cliente"
              v-model="filter.client"
            />
          </div>
          <div class="form-group col-md-3">
            <label for="from_date">Desde</label>
            <input
              type="date"
              class="form-control"
              id="from_date"
              v-model="filter.from"
            />
          </div>
          <div class="form-group col-md-3">
            <label for="to_date">Hasta</label>
            <input
              type="date"
              class="form-control"
              id="to_date"
              v-model="filter.to"
            />
          </div>
          <div class="form-group offset-8 col-md-1">
            <button
              class="btn btn-success btn-block"
              data-toggle="modal"
              data-target="#modalPaymentCredit"
            >
              Abonar
            </button>
          </div>
          <div class="form-group col-md-3">
            <button class="btn btn-success btn-block" @click="getCredits(1)">
              Buscar
            </button>
          </div>
        </div>
		<payment-credit @get-credits="getCredits()" ref="PaymentCredit"/>
        <table class="table table-sm table-bordered table-responsive-sm">
          <thead>
            <tr>
              <th>#</th>
              <th>Total Pago</th>
              <th>Abono</th>
              <th>Saldo</th>
              <th>Total Sin Iva</th>
              <th>Total Descuento</th>
              <th>Cliente</th>
              <th>Estado</th>
              <th>Ver</th>

							<th>Imprimir</th>
							<th v-if="$root.validatePermission('credit.update')">Editar</th>
							<th v-if="$root.validatePermission('credit.delete')">
								Eliminar
							</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="o in creditList.data" :key="o.id">
							<th scope="row">{{ o.id }} - {{ o.no_invoice }}</th>
							<td>{{ o.total_paid | currency }}</td>
							<td class="bg-success">{{ o.paid_payment | currency }}</td>
							<td :class="{ 'bg-danger': o.total_paid - o.paid_payment > 0 }">
								{{ (o.total_paid - o.paid_payment) | currency }}
							</td>
							<td>{{ o.total_iva_exc | currency }}</td>
							<td>{{ o.total_discount | currency }}</td>
							<td>{{ o.client.name }}</td>
							<td>
								<span v-if="o.state == 0">Desechada</span>
								<span v-if="o.state == 1">Abierta</span>
								<span v-if="o.state == 2">Registrada</span>
								<span v-if="o.state == 3">Pagada</span>
							</td>
							<td>
								<router-link
									class="btn"
									:to="{
										name: 'details-credit',
										params: { credit_id: o.id }
									}"
								>
									<i class="bi bi-eye"></i>
								</router-link>
							</td>
							<td>
								<button class="btn" @click="generatePdf(o.id)">
									<i class="bi bi-printer"></i>
								</button>
							</td>
							<td v-if="$root.validatePermission('credit.update')">
								<router-link
									class="btn"
									:to="{
										name: 'create-edit-credit',
										params: { credit_id: o.id }
									}"
								>
									<i class="bi bi-pencil-square"></i>
								</router-link>
							</td>
							<td v-if="$root.validatePermission('credit.delete')">
								<button class="btn" @click="deleteCredit(o.id)">
									<i class="bi bi-trash"></i>
								</button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<pagination
				:align="'center'"
				:data="creditList"
				:limit="8"
				@pagination-change-page="getCredits"
			>
				<span slot="prev-nav"><i class="bi bi-chevron-double-left"></i></span>
				<span slot="next-nav"><i class="bi bi-chevron-double-right"></i></span>
			</pagination>
		</section>
		<div class="footer">
			<moon-loader
				:loading="isLoading"
				class="m-auto"
				:color="'#032F6C'"
				:size="100"
			/>
		</div>
	</div>
</template>
<script>
import PaymentCredit from "./PaymentCredit.vue";
export default {
	components: { PaymentCredit },
	data() {
		return {
			isLoading: false,
			creditList: {},
			filter: {
				client: "",
				no_invoice: "",
				from: "",
				to: ""
			}
		};
	},
	created() {
		this.$root.validateToken();
		this.getCredits(1);
	},
	methods: {
		getCredits(page = 1) {
			let me = this;
			axios
				.get(
					`api/credits?page=${page}&client=${me.filter.client}&no_invoice=${me.filter.no_invoice}&from=${me.filter.from}&to=${me.filter.to}`,
					this.$root.config
				)
				.then(function(response) {
					me.creditList = response.data.credits;
				});
		},
		deleteCredit(credit_id) {
			axios
				.delete(`api/credits/${credit_id}`, this.$root.config)
				.then(() => this.getCredits(1));
		},
		generatePdf(id) {
			this.isLoading = true;
			axios
				.get("api/credits/generatePdf/" + id, this.$root.config)
				.then(response => {
					const pdf = response.data.pdf;
					var a = document.createElement("a");
					a.href = "data:application/pdf;base64," + pdf;
					a.download = `Credit-${id}.pdf`;
					a.click();
				})
				.finally((this.isLoading = false));
		}
	}
};
</script>
