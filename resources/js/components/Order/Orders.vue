<template>
	<div class="w-100">
		<header class="page-header justify-content-between row px-4">
			<h3>Ordenes</h3>
			<router-link
				class="btn btn-outline-primary"
				:to="{
					name: 'create-edit-order',
					params: { order_id: 0 }
				}"
				v-if="$root.validatePermission('order.store')"
			>
				Nueva orden
			</router-link>
		</header>
		<section>
			<load-pdf :loading="load_pdf" />
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
					<div class="form-group offset-9 col-md-3">
						<button class="btn btn-success btn-block" @click="getOrders(1)">
							Buscar
						</button>
					</div>
				</div>
				<table class="table table-sm table-bordered table-responsive-sm">
					<thead>
						<tr>
							<th>#</th>
							<th>Total Pago</th>
							<th>Total Sin Iva</th>
							<th>Total Descuento</th>
							<th>Cliente</th>
							<th>Estado</th>
							<th>Ver</th>
							<th>Ticket</th>
							<th>Imprimir</th>
							<th v-if="$root.validatePermission('order.update')">Editar</th>
							<th v-if="$root.validatePermission('order.delete')">Eliminar</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="o in OrderList.data" :key="o.id">
							<th scope="row">{{ o.id }} - {{ o.bill_number }}</th>
							<td>{{ o.total_paid }}</td>
							<td>{{ o.total_iva_exc }}</td>
							<td>{{ o.total_discount }}</td>
							<td>{{ o.client.name }}</td>
							<td>
								{{ statusOrders[o.state] }}
							</td>
							<td>
								<router-link
									class="btn"
									:to="{ name: 'details-order', params: { order_id: o.id } }"
								>
									<i class="bi bi-eye"></i>
								</router-link>
							</td>
							<td>
								<button class="btn" v-if="o.state != 0 && o.state != 2" @click="printTicket(o.id)">
									<i class="bi bi-receipt"></i>
								</button>
							</td>
							<td>
								<button class="btn" @click="generatePdf(o.id)">
									<i class="bi bi-printer"></i>
								</button>
							</td>
							<td v-if="$root.validatePermission('order.update')">
								<router-link
									class="btn"
									:to="{
										name: 'create-edit-order',
										params: { order_id: o.id }
									}"
								>
									<i class="bi bi-pencil-square"></i>
								</router-link>
							</td>
							<td v-if="$root.validatePermission('order.delete')">
								<button class="btn" @click="deleteOrder(o.id)">
									<i class="bi bi-trash"></i>
								</button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<pagination
				:align="'center'"
				:data="OrderList"
				:limit="8"
				@pagination-change-page="getOrders"
			>
				<span slot="prev-nav"><i class="bi bi-chevron-double-left"></i></span>
				<span slot="next-nav"><i class="bi bi-chevron-double-right"></i></span>
			</pagination>
		</section>
		<div class="footer"></div>
	</div>
</template>
<script>
import LoadPdf from "./LoadPdf.vue";
export default {
	components: { LoadPdf },
	data() {
		return {
			load_pdf: false,
			OrderList: {},
			filter: {
				client: "",
				no_invoice: "",
				from: "",
				to: ""
			},
			statusOrders: {
				0: "Desechada",
				1: "Suspender",
				2: "Facturado",
				3: "Cotizar",
				4: "Facturar e imprimir",
				5: "Credito",
				6: "Credito e imprimir"
			}
		};
	},
	created() {
		this.$root.validateToken();
		this.getOrders(1);
	},
	methods: {
		getOrders(page = 1) {
			let me = this;
			axios
				.get(
					`api/orders?page=${page}&client=${me.filter.client}&no_invoice=${me.filter.no_invoice}&from=${me.filter.from}&to=${me.filter.to}`,
					this.$root.config
				)
				.then(function(response) {
					me.OrderList = response.data.orders;
				});
		},
		deleteOrder(order_id) {
			axios
				.delete(`api/orders/${order_id}`, this.$root.config)
				.then(() => this.getOrders(1));
		},
		generatePdf(id) {
			this.load_pdf = true;
			axios
				.get("api/orders/generatePdf/" + id, this.$root.config)
				.then(response => {
					console.log(response);

					const pdf = response.data.pdf;
					var a = document.createElement("a");
					a.href = "data:application/pdf;base64," + pdf;
					a.download = `Order-${id}.pdf`;
					a.click();
				})
				.finally(() => {
					this.load_pdf = false;
				});
		},
		printTicket(order_id) {
			axios.get(`api/print-order/${order_id}`, this.$root.config);
		}
	}
};
</script>
