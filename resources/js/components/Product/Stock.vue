<template>
	<div class="w-100">
		<div class="page-header text-center mb-2">
			<h3 class="">Stock</h3>
		</div>
		<moon-loader
			class="m-auto"
			:loading="isLoading"
			:color="'#032F6C'"
			:size="100"
		/>
		<div class="page-search mx-2" v-if="!isLoading">
			<div class="form-row">
				<div class="col my-4">
					<input
						type="text"
						class="form-control"
						id="search_product"
						placeholder="Nombre | Código de barras"
						v-model="search_product"
						autofocus
						@keyup="listProducts(1)"
					/>
				</div>
				<div class="col my-4">
					<v-select
						:options="categoryList"
						label="name"
						:reduce="category => category.id"
						v-model="search_category"
					/>
				</div>
				<div class="col my-4">
					<v-select
						:options="brandList"
						label="name"
						:reduce="brand => brand.id"
						v-model="search_brand"
					/>
				</div>
				<div class="col my-4">
					<button class="btn btn-success btn-block" @click="listProducts(1)">
						Buscar <i class="bi bi-search"></i>
					</button>
				</div>
			</div>
		</div>
		<div class="page-content mx-2">
			<section class="mt-4">
				<table class="table table-sm table-bordered table-responsive-sm">
					<thead class="thead-primary">
						<tr>
							<th>Código de barras</th>
							<th scope="col">Producto</th>
							<th>Categoria</th>
							<th>Marca</th>
							<th scope="col">Cantidad</th>
							<td v-if="$root.validatePermission('product.update')">Editar</td>
						</tr>
					</thead>
					<tbody>
						<tr v-for="product in ProductList.data" v-bind:key="product.id">
							<td>{{ product.barcode }}</td>
							<td>{{ product.product }}</td>
							<td>{{ product.category.name }}</td>
							<td>
								<span v-if="product.brand">
									{{ product.brand.name }}
								</span>
							</td>
							<td>{{ product.quantity }}</td>
							<td v-if="$root.validatePermission('product.update')">
								<div class="input-group mb-3">
									<input
										type="number"
										class="form-control"
										placeholder="Cantidad de productos"
										aria-label="Product quantity"
										aria-describedby="button-qty"
										value="0"
										v-model="product.qty"
									/>
									<div class="input-group-append">
										<button
											class="btn btn-outline-success"
											type="button"
											id="button-qty"
											v-if="product.qty && product.qty != 0"
											@click="updateStock(product.id, product.qty)"
										>
											<i class="bi bi-check2-circle"></i>
										</button>
									</div>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
				<pagination
					:align="'center'"
					:data="ProductList"
					:limit="8"
					@pagination-change-page="listProducts"
				>
					<span slot="prev-nav"><i class="bi bi-chevron-double-left"></i></span>
					<span slot="next-nav"
						><i class="bi bi-chevron-double-right"></i
					></span>
				</pagination>
			</section>
		</div>
	</div>
</template>

<script>
export default {
	data() {
		return {
			search_product: "",
			search_category: 0,
			search_brand: 0,
			isLoading: false,
			ProductList: {},
			categoryList: [],
			brandList: []
		};
	},
	created() {
		this.isLoading = true;
		let me = this;
		axios
			.get(`api/products?page=1`, this.$root.config)
			.then(function(response) {
				me.ProductList = response.data.products;
			})
			.finally(() => (this.isLoading = false));
	},
	methods: {
		listProducts(page = 1) {
			let me = this;
			axios
				.get(
					`api/products?page=${page}&product=${me.search_product}&category_id=${me.search_category}&brand_id=${me.search_brand}`,
					this.$root.config
				)
				.then(function(response) {
					me.ProductList = response.data.products;
				});
		},
		listCategories() {
			let me = this;
			axios
				.get(`api/categories/category-list`, this.$root.config)
				.then(function(response) {
					me.categoryList = response.data.categories;
				});
		},
		listBrands() {
			let me = this;
			axios
				.get(`api/brands/brand-list`, this.$root.config)
				.then(function(response) {
					me.brandList = response.data.brands;
				});
		},
		updateStock(id, quantity) {
			let me = this;
			axios
				.post(
					`api/products/stock-update/${id}`,
					{ quantity: quantity },
					this.$root.config
				)
				.then(function(response) {
					me.listProducts(1);
				});
		}
	},
	mounted() {
		this.listCategories();
		this.listBrands();
	}
};
</script>

<style></style>
