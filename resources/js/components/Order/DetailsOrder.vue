<template>
  <div>
    <section class="page-header">
      <h4 class="w-100 text-center">Detalles de Orden</h4>
      <load-pdf :loading="load_pdf"></load-pdf>
      <table class="table table-bordered w-100 table-sm">
        <tbody>
          <tr>
            <td>No. Factura</td>
            <th>
              {{ orderInformation.bill_number }}
            </th>
          </tr>
          <tr>
            <td>Fecha</td>
            <th>
              {{ orderInformation.updated_at }}
            </th>
          </tr>
          <tr>
            <th colspan="2" class="text-center">Cliente</th>
          </tr>
          <tr>
            <td>Nombres:</td>
            <th>
              {{ orderInformation.client.name }}
            </th>
          </tr>
          <tr>
            <td>Documento / Nit:</td>
            <th>
              {{ orderInformation.client.document }}
            </th>
          </tr>
          <tr>
            <td>Direccion</td>
            <td>{{ orderInformation.client.address }}</td>
          </tr>
           <tr>
            <td>Email</td>
            <td>{{ orderInformation.client.email }}</td>
          </tr>
           <tr>
            <td>Celular / Télefono</td>
            <td>{{ orderInformation.client.mobile }}</td>
          </tr>
        </tbody>
      </table>
      <br>

      <div>
        <h4 class="w-100 text-center">Lista abonos</h4>
        <div class="text-right">
          <button class="btn btn-light text-danger" @click="generatePaymentPdf(null)">
            <i class="bi bi-file-earmark-pdf-fill"></i> Descargar PDF
          </button>
          <button class="btn btn-light" @click="generatePaymentTicket(null)">
            <i class="bi bi-receipt-cutoff"></i> Descargar Ticket
          </button>
        </div>
      </div>

      <table class="table table-bordered w-100 table-sm" v-if="orderInformation.payment_credits">
        <thead>
          <tr>
            <th>Abono</th>
            <th>Fecha</th>
            <th>Imprimir PDF</th>
            <th>Imprimir Ticket</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="paidCredit in orderInformation.payment_credits" :key="paidCredit.id">
           <template v-if=" paidCredit.pay">
            <td>$ {{ paidCredit.pay }}</td>
            <td>{{ paidCredit.created_at }}</td>
            <td class="text-right">
              <button class="btn text-danger" @click="generatePaymentPdf(paidCredit.id)">
                <i class="bi bi-file-earmark-pdf-fill"></i> PDF
              </button>
            </td>
            <td class="text-right">
              <button class="btn" @click="generatePaymentTicket(paidCredit.id)">
                <i class="bi bi-receipt-cutoff"></i> Ticket
              </button>
            </td>
           </template>
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
            <td class="text-right">
            $  {{ orderInformation.total_iva_exc }}
            </td>
          </tr>
          <tr>
            <td colspan="8">Descuento</td>
            <td class="text-right">
            $  {{ orderInformation.total_discount }}
            </td>
          </tr>
          <tr>
            <td colspan="8">Total</td>
            <th class="h5 text-right">
            $  {{ orderInformation.total_iva_inc }}
            </th>
          </tr>
        </tfoot>
      </table>
    </section>
  </div>
</template>

<script>
import LoadPdf from './LoadPdf.vue';
export default {
  components: { LoadPdf },
  props: ["order_id"],
  data() {
    return {
      orderInformation: {
        client: "",
      },
      ItemList: {},
      load_pdf: false
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
          me.orderInformation = response.data.order_information;
          me.ItemList = response.data.order_details;
        });
    },
    generatePaymentPdf(id = null) {
      this.load_pdf = true;

      let data = {
        payment_id: id
      }
      axios
        .get(`api/orders/generatePaymentPdf/${this.order_id}`, {
          params: data,
          headers: this.$root.config.headers
        })
        .then(response => {
          const pdf = response.data.pdf;
          var a = document.createElement("a");
          a.href = "data:application/pdf;base64," + pdf;
          a.download = `Credit-${this.order_id}.pdf`;
          a.click();
        })
        .finally(() => {
          this.load_pdf = false;
        });
    },
    generatePaymentTicket(id = null) {
      this.load_pdf = true;

      let data = {
        payment_id: id
      }
      axios
        .get(`api/print-payment-ticket/${this.order_id}`, {
          params: data,
          headers: this.$root.config.headers
        })
        .then(response => {
          console.log(response);
        })
        .finally(() => {
          this.load_pdf = false;
        });
    },
  },
};
</script>
