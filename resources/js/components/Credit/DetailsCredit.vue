<template>
  <div>
    <section class="page-header">
      <h4 class="w-100 text-center">Detalles de Factura</h4>
      <table class="table table-bordered w-100 table-sm">
        <tbody>
          <tr>
            <td>No. Factura</td>
            <th>
              {{ creditInformation.no_invoice }}
            </th>
          </tr>
          <tr>
            <td>Fecha</td>
            <th>
              {{ creditInformation.updated_at }}
            </th>
          </tr>
          <tr>
            <th colspan="2" class="text-center">Cliente</th>
          </tr>
          <tr>
            <td>Nombres:</td>
            <th>
              {{ creditInformation.client.name }}
            </th>
          </tr>
          <tr>
            <td>Documento / Nit:</td>
            <th>
              {{ creditInformation.client.document }}
            </th>
          </tr>
          <tr>
            <td>Direccion</td>
            <td>{{ creditInformation.client.address }}</td>
          </tr>
          <tr>
            <td>Email</td>
            <td>{{ creditInformation.client.email }}</td>
          </tr>
          <tr>
            <td>Celular / Télefono</td>
            <td>{{ creditInformation.client.mobile }}</td>
          </tr>
        </tbody>
      </table>
      <br>
      <h4 class="w-100 text-center">Abonos</h4>
      <table class="table table-bordered w-100 table-sm">
        <thead>
          <tr>
            <th>Abono</th>
            <th>Fecha</th>
          </tr>
        </thead>
        <tbody>
          <tr  v-for="paidCredit in creditInformation.payment_credits" :key="paidCredit.id">
            <td>$ {{ paidCredit.pay }}</td>
            <td>{{ paidCredit.created_at }}</td>
          </tr>
        </tbody>
      </table>
    </section>
    <section class="mt-5">
      <h5 class="text-center">Detalles</h5>
      <table class="table table-sm table-bordered">
        <thead>
          <tr>
            <th>#</th>
            <th>Código de barras</th>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio sin IVA</th>
            <th>Precio con IVA</th>
            <th>Descuento %</th>
            <th>Descuento $</th>
            <th>Precio Total</th>
          </tr>
        </thead>
        <tbody class="">
          <tr v-for="(i, index) in ItemList" :key="i.id">
            <td>{{ index + 1 }}</td>
            <td>{{ i.barcode }}</td>
            <td>{{ i.product }}</td>
            <td>{{ i.quantity }}</td>
            <td>$ {{ i.price_tax_exc }}</td>
            <td>$ {{ i.price_tax_inc }}</td>
            <td>{{ i.discount_percentage }} %</td>
            <td>$ {{ i.discount_price }}</td>
            <td class="text-right">$ {{ i.price_tax_inc_total }}</td>
          </tr>
        </tbody>
        <tfoot class="table-secondary">
          <tr>
            <td colspan="8">Subtotal</td>
            <td class="text-right">$ {{ creditInformation.total_iva_exc }}</td>
          </tr>
          <tr>
            <td colspan="8">Descuento</td>
            <td class="text-right">
              $ {{ creditInformation.total_discount }}
            </td>
          </tr>
          <tr>
            <td colspan="8">Total</td>
            <th class="h5 text-right">
              $ {{ creditInformation.total_iva_inc }}
            </th>
          </tr>
          <tr>
            <td colspan="8">Abono</td>
            <th class="h5 text-right">
              $ {{ creditInformation.paid_payment }}
            </th>
          </tr>
          <tr>
            <td colspan="8">Saldo</td>
            <th class="h5 text-right">
              $ {{ creditInformation.total_iva_inc - creditInformation.paid_payment }}
            </th>
          </tr>
        </tfoot>
      </table>
    </section>
  </div>
</template>

<script>
export default {
  props: ["order_id"],
  filters: {
    showDate(value){
      let date = "";
      let dateText = ""
      try{
        date = new Date(value);
        dateText = value.split('T')[0]
      }catch(error){
        
      }
      
      return  [
        dateText,
        `${date.getHours()}:${date.getMinutes()}:${date.getSeconds()}`
      ].join(" ");
    }
  },
  data() {
    return {
      creditInformation: {
        client: "",
        payment_credits: []
      },
      ItemList: {},
    };
  },
  created() {
    this.getDetailsCredit();
  },
  methods: {
    getDetailsCredit() {
      let me = this;
      axios
        .get(`api/orders/${this.order_id}`, this.$root.config)
        .then(function (response) {
          me.creditInformation = response.data.order_information;
          me.ItemList = response.data.order_details;
        });
    },
  },
};
</script>
