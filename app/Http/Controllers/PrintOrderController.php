<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\Configuration;
use App\Models\Order;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

class PrintOrderController extends Controller
{
	public function openBox()
	{
		$configuration = new Configuration();
		$company =  $configuration->select()->first();
		if (!$company->printer || $company->printer == '') {
			throw new Exception("Error, no hay impresoras configuradas", 400);
		}
		$connector = new WindowsPrintConnector($company->printer);
		$printer = new Printer($connector);
		$printer->initialize();
		$printer->pulse();
		$printer->close();
	}

	public function printTicket($order_id, $cash = null, $change = null)
	{
		// Orden
		$order = Order::find($order_id);
		$order_details = $order->detailOrders()->get();
		$system_user = $order->user()->first();
		$payment_methods = (object) ($order->payment_methods);

		// Información empresarial
		$configuration = new Configuration();
		$company =  $configuration->select()->first();

		if (!$company->printer || $company->printer == '') {
			throw new Exception("Error, no hay impresoras configuradas", 400);
		}

		// Config de impresora
		$connector = new WindowsPrintConnector($company->printer);

		try {

			$printer = new Printer($connector);
			$printer->initialize();
			$printer->setJustification(Printer::JUSTIFY_CENTER);
			try {
				$logo = EscposImage::load($company->logo, false);
				$printer->bitImage($logo);
			} catch (Exception $e) {
				/* Images not supported on your PHP, or image file not found */
				//$printer->text($e->getMessage() . "\n");
				$logo = EscposImage::load('images/logo.jpeg', false);
			}

			$printer->setTextSize(1, 2);
			$printer->setEmphasis(true);
			$printer->text($company->name . "\n");
			$printer->setJustification(Printer::JUSTIFY_LEFT);
			$printer->setTextSize(1, 1);
			$printer->setEmphasis(false);
			$printer->text("NIT: ");
			$printer->text($company->nit . "\n");
			$printer->text("Dirección: ");
			$printer->text($company->address . "\n");

			$printer->setEmphasis(true);
			$printer->text("Cajero(a): ");
			$printer->text($system_user->name . "\n");
			$printer->setEmphasis(false);
			$printer->text("Fecha: ");
			$printer->text(date('Y-m-d h:i:s A') .  "\n");
			$printer->text("N° Factura: ");

			if (isset($order->bill_number)) {
				$printer->text($order->bill_number . "\n");        // 
			} else {
				$printer->text($order->id . "\n");        // 
			}
			$printer->text("\n-----------------------------------------" . "\n");
			$printer->text("Cliente: ");
			$printer->text($order->client->name . $order->client->last_name . "\n");
			if ($order->client->type_person) {
				$printer->text('Tipo de persona: ' . $order->client->type_person . "\n");
			}
			if ($order->client->document) {
				$printer->text('Documento: ' . $order->client->type_document . ' '  . $order->client->document . "\n");
			}
			if ($order->client->address) {
				$printer->text('Dirección: ' . $order->client->address . "\n");
			}
			if ($order->client->contact) {
				$printer->text('Contacto: ' . $order->client->contact . "\n");
			}
			$printer->text("\n");
			$printer->setLineSpacing(2);
			$printer->setJustification(Printer::JUSTIFY_CENTER);
			$printer->text("\n-----------------------------------------" . "\n\n");
			$printer->setLineSpacing(1);
			$printer->setEmphasis(true);
			$printer->text(sprintf('%-22s %+8s %+10.7s', 'ARTICULO', 'CANT', 'PRECIO'));
			$printer->text("\n-----------------------------------------" . "\n\n");
			$printer->setEmphasis(false);
			$printer->text("\n");
			$total = 0;
			foreach ($order_details as $df) {
				$line = sprintf('%-22s %8.2f %10.2f ', '-' . mb_strimwidth($df->product, 0, 21, ''), $df->quantity, $df->price_tax_inc_total);
				$total +=  $df->price_tax_inc_total;
				$printer->text($line);
				$printer->text("\n");
			}
			$printer->text("\n==========================================" . "\n");
			$printer->setEmphasis(true);
			$printer->setLineSpacing(2);
			$printer->text("\n");
			$printer->text(sprintf('%-25s %+15.15s', 'Subtotal', number_format($order->total_iva_exc, 2), '.', ','));
			$printer->text("\n");
			$printer->text(sprintf('%-25s %+15.15s', 'Iva', number_format($order->total_iva_inc - $order->total_iva_exc, 2, '.', ',')));
			$printer->text("\n");
			$printer->setTextSize(1, 2);
			$printer->text(sprintf('%-25s %+15.15s', 'TOTAL', number_format($total, 2, '.', ',')));
			$printer->text("\n");
			$printer->setTextSize(1, 1);
			$printer->setEmphasis(false);

			isset($payment_methods->cash)  &&  $payment_methods->cash  > 0 ?		$printer->text(sprintf('%-25s %+15.15s', 'Efectivo', number_format($payment_methods->cash, 2, '.', ',') . "\n")) : '';
			isset($payment_methods->card)  &&  $payment_methods->card  > 0 ?		$printer->text(sprintf('%-25s %+15.15s', 'Tarjeta', number_format($payment_methods->card, 2, '.', ',') . "\n")) : '';
			isset($payment_methods->nequi)  && $payment_methods->nequi  > 0 ?		$printer->text(sprintf('%-25s %+15.15s', 'Nequi', number_format($payment_methods->nequi, 2, '.', ',') . "\n")) : '';
			isset($payment_methods->others)  && $payment_methods->others > 0 ?		$printer->text(sprintf('%-25s %+15.15s', 'Otros', number_format($payment_methods->others, 2, '.', ',') . "\n")) : '';
			$printer->setEmphasis(true);
			isset($payment_methods->change)  ?	$printer->text(sprintf('%-25s %+15.15s', 'Cambio', number_format($payment_methods->change, 2, '.', ',') . "\n")) : 0;
			$printer->setEmphasis(false);

			if ($change != null) {
				$printer->text("\n");
				$change > 0 ?	$printer->text(sprintf('%-25s %+15.15s', 'Cambio', number_format($change, 2, '.', ','))) : $printer->text(sprintf('%-25s %+15.15s', 'Cambio', number_format(0, 2, '.', ',')));
			}
			$printer->text("\n");

			if (isset($order->bill_number)) {

				$consecutiveBox = $order->consecutiveBox();
				if ($consecutiveBox) {
					$from_date = Carbon::createFromFormat('Y-m-d', $consecutiveBox->from_date);
					$until_date = Carbon::createFromFormat('Y-m-d', $consecutiveBox->until_date);

					$printer->setJustification(Printer::JUSTIFY_CENTER);
					$printer->text("VENCE: " . $until_date->toDateString() . " MESES VIG. :  " . ($until_date->month - $from_date->month) . "\n");
					$printer->text("PREFIJO: " . $order->box->prefix . "\n");

					$printer->text("DE No. " . $consecutiveBox->from_nro . " AL " . $consecutiveBox->until_nro . " AUTORIZA\n");
				}
			}
			$printer->text("\n");
			$printer->setJustification(Printer::JUSTIFY_CENTER);
			$printer->setLineSpacing(2);
			$printer->setEmphasis(false);
			$printer->setFont(Printer::MODE_FONT_B);
			$printer->text($company->condition_order . "\n");
			$printer->text("Tecnoplus");
			$printer->text("\nwww.tecnoplus.com\n");
			$printer->cut();
			$printer->pulse();
			$printer->close();

			return redirect()->back()->with("mensaje", "Ticket impreso");
		} catch (\Throwable $th) {
			throw new Exception("Error, no se pudo completar el proceso", 400);
			return [
				'code' => $th->getCode()
			];
		}
	}

	public function printTicketBilling($billing_id, $cash = null, $change = null)
	{
		// Orden
		$billing = Billing::find($billing_id);
		$billing_details = $billing->detailBillings()->get();
		$system_user = $billing->user()->first();

		// Información empresarial
		$configuration = new Configuration();
		$company =  $configuration->select()->first();

		if (!$company->printer || $company->printer == '') {
			throw new Exception("Error, no hay impresoras configuradas", 400);
		}

		// Config de impresora
		$connector = new WindowsPrintConnector($company->printer);

		try {

			$printer = new Printer($connector);
			$printer->initialize();
			$printer->setJustification(Printer::JUSTIFY_CENTER);
			try {
				$logo = EscposImage::load($company->logo, false);
				$printer->bitImage($logo);
			} catch (Exception $e) {
				/* Images not supported on your PHP, or image file not found */
				//$printer->text($e->getMessage() . "\n");
				$logo = EscposImage::load('images/logo.jpeg', false);
			}

			$printer->setTextSize(1, 2);
			$printer->setEmphasis(true);
			$printer->text($company->name . "\n");
			$printer->setJustification(Printer::JUSTIFY_LEFT);
			$printer->setTextSize(1, 1);
			$printer->setEmphasis(false);
			$printer->text("NIT: ");
			$printer->text($company->nit . "\n");
			$printer->text("Dirección: ");
			$printer->text($company->address . "\n");

			$printer->setEmphasis(true);
			$printer->text("Cajero(a): " . $system_user->name . "\n");
			$printer->setEmphasis(false);
			$printer->text("Fecha: ");
			$printer->text(date('Y-m-d h:i:s A') .  "\n");
			$printer->text("N° compra: " . $billing->id . "\n");
			$printer->text("\n-----------------------------------------" . "\n");
			$printer->text("Cliente: ");
			$printer->text($billing->supplier->name . "\n");
			if ($billing->supplier->type_person) {
				$printer->text('Tipo de persona: ' . $billing->supplier->type_type_person . "\n");
			}
			if ($billing->supplier->document) {
				$printer->text('Documento: ' . $billing->supplier->type_document . ' '  . $billing->supplier->document . "\n");
			}
			if ($billing->supplier->address) {
				$printer->text('Dirección: ' . $billing->supplier->address . "\n");
			}
			if ($billing->supplier->contact) {
				$printer->text('Contacto: ' . $billing->supplier->contact . "\n");
			}
			$printer->text("\n");
			$printer->setLineSpacing(2);
			$printer->setJustification(Printer::JUSTIFY_CENTER);
			$printer->text("\n-----------------------------------------" . "\n\n");
			$printer->setLineSpacing(1);
			$printer->setEmphasis(true);
			$printer->text(sprintf('%-22s %+8s %+10.7s', 'ARTICULO', 'CANT', 'PRECIO'));
			$printer->text("\n-----------------------------------------" . "\n\n");
			$printer->setEmphasis(false);
			$printer->text("\n");
			$total = 0;
			foreach ($billing_details as $df) {
				$line = sprintf('%-22s %8.2f %10.2f ', '-' . mb_strimwidth($df->product, 0, 21, ''), $df->quantity, $df->price_tax_inc_total);
				$total +=  $df->price_tax_inc_total;
				$printer->text($line);
				$printer->text("\n");
			}
			$printer->text("\n==========================================" . "\n");
			$printer->setEmphasis(true);
			$printer->setLineSpacing(2);
			$printer->text("\n");
			$printer->text(sprintf('%-25s %+15.15s', 'Subtotal', number_format($billing->total_iva_exc, 2), '.', ','));
			$printer->text("\n");
			$printer->text(sprintf('%-25s %+15.15s', 'Iva', number_format($billing->total_iva_inc - $billing->total_iva_exc, 2, '.', ',')));
			$printer->text("\n");
			$printer->setTextSize(1, 2);
			$printer->text(sprintf('%-25s %+15.15s', 'TOTAL', number_format($total, 2, '.', ',')));
			$printer->setTextSize(1, 1);
			$printer->setEmphasis(false);
			if ($cash != null && $change != null) {
				$printer->text("\n");
				$printer->text(sprintf('%-25s %+15.15s', 'Efectivo', number_format($cash, 2, '.', ',')));
				$printer->text("\n");
				$printer->text(sprintf('%-25s %+15.15s', 'Cambio', number_format($change, 2, '.', ',')));
			}
			$printer->text("\n");


			$printer->text("\n");
			$printer->setJustification(Printer::JUSTIFY_CENTER);
			$printer->setLineSpacing(2);
			$printer->setEmphasis(false);
			$printer->setFont(Printer::MODE_FONT_B);
			$printer->text($company->condition_order . "\n");
			$printer->text("Tecnoplus");
			$printer->text("\nwww.tecnoplus.com\n");
			$printer->cut();
			$printer->pulse();
			$printer->close();

			return redirect()->back()->with("mensaje", "Ticket impreso");
		} catch (\Throwable $th) {
			throw new Exception("Error, no se pudo completar el proceso", 400);
			return [
				'code' => $th->getCode()
			];
		}
	}
}
