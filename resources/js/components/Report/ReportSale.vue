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
							<th>Número de facturas</th>
							<th>Nro. facturas registradas</th>
							<th>Nro. facturas suspendidas</th>
							<th>Nro. facturas cotizadas</th>
							<th>Total precio de costo</th>
							<th>Total IVA excluido</th>
							<th>Total IVA incluido</th>
							<th>Total Descuento</th>
							<th>Total Pago</th>
							<th>Total Ganancia</th>
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
								{{ l.registered }}
							</td>
							<td>
								{{ l.suspended }}
							</td>
							<td>
								{{ l.quoted }}
							</td>
							<td>
								{{ l.total_cost_price_tax_inc.toFixed(2) | currency }}
							</td>
							<td>
								{{ l.total_iva_exc.toFixed(2)  | currency}}
							</td>
							<td>
								{{ l.total_iva_inc.toFixed(2)  | currency}}
							</td>
							<td>
								{{ l.total_discount.toFixed(2)  | currency}}
							</td>
							<td>
								{{ l.total_paid.toFixed(2) | currency }}
							</td>
							<td>
								{{(l.total_iva_exc - l.total_cost_price_tax_inc).toFixed(2)  | currency}}
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
