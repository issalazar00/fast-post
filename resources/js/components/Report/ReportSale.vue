<template>
	<div class="page w-100">
		<div class="page-header">
			<h3>Reporte de venta</h3>
		</div>
		<div class="page-content">
			<section class="p-3">
				<table class="table table-sm table-bordered table-hover">
					<thead class=" thead-dark">
						<tr>
							<th>Fecha venta</th>
							<th>Número de ventas</th>
							<th>Total Descuento</th>
							<th>Total Pago</th>
						</tr>
					</thead>
					<tbody v-if="List.length > 0">
						<tr v-for="(l, index) in List" :key="index">
							<td>
								{{ l.payment_date }}
							</td>
							<td>
								{{ l.number_of_orders }}
							</td>
							<td>
								{{ (l.total_discount).toFixed(2) }}
							</td>
							<td>
								{{ (l.total_paid).toFixed(2) }}
							</td>
						</tr>
					</tbody>
					<tbody v-else>
						<tr>
							<td colspan="3">
								No hay resultados
							</td>
						</tr>
					</tbody>
				</table>
			</section>
		</div>
	</div>
</template>

<script>
export default {
	data() {
		return {
			List: {},
			filter: {
				client: "",
				no_invoice: "",
				from: "",
				to: ""
			}
		};
	},
	methods: {
		getOrders(page = 1) {
			let me = this;
			axios
				.get(
					`api/reports/report-sales?page=${page}&client=${me.filter.client}&no_invoice=${me.filter.no_invoice}&from=${me.filter.from}&to=${me.filter.to}`,
					this.$root.config
				)
				.then(function(response) {
					me.List = response.data;
				});
		}
	},
	mounted() {
		this.getOrders(1);
	}
};
</script>
