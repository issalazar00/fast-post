<template>
  <div>
    <section>
      <table class="table table-sm table-bordered">
        <thead>
          <tr>
            <th>#</th>
            <th>Código de barras</th>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio sin IVA</th>
            <th>Descuento %</th>
            <th>Descuento $</th>
            <th>Precio Total</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(i, index) in ItemList" :key="i.id">
            <td>{{ index + 1 }}</td>
            <td>{{ i.barcode }}</td>
            <td>{{ i.product }}</td>
            <td>{{ i.quantity }}</td>
            <td>{{ i.price_tax_inc }}</td>
            <td>{{ i.discount_percentage }}</td>
            <td>{{ i.discount_price }}</td>
            <td>{{ i.price_tax_exc }}</td>
          </tr>
        </tbody>
      </table>
    </section>
  </div>
</template>

<script>
export default {
  props: ["order_id"],
  data() {
    return {
      ItemList: {},
    };
  },
  created() {
    this.getDetailsOrder();
  },
  methods: {
    getDetailsOrder() {
      let me = this;
      axios
        .get(`api/orders/${this.order_id}`, this.$root.config)
        .then(function (response) {
          me.ItemList = response.data;
        });
    },
  },
};
</script>
