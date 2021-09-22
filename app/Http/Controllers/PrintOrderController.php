<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Exception;
use Faker\Provider\ar_JO\Company;
use Illuminate\Http\Request;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

class PrintOrderController extends Controller
{
	public function printTicket($order_id)
	{
		// Orden

		$order = Order::find($order_id);
		$order_details = $order->detailOrders()->get();
		$system_user = $order->user()->first();

		// Información empresarial
		$company = new Company;

		// Config de impresora

		$connector = new WindowsPrintConnector('cocina');

		$printer = new Printer($connector);
		$printer->initialize();
		$printer->setJustification(Printer::JUSTIFY_CENTER);
		try {
			// Este logo también debe ser dinamico
			$logo = EscposImage::load('logo.jpg', false);
			$printer->bitImage($logo);
		} catch (Exception $e) {
			/* Images not supported on your PHP, or image file not found */
			$printer->text($e->getMessage() . "\n");
		}
		$printer->text("\n===============================\n");
		$printer->setTextSize(1, 2);
		$printer->setEmphasis(true);
		$printer->text($company->name . "\n");
		$printer->setTextSize(1, 1);
		$printer->setEmphasis(false);
		$printer->text("NIT: ");
		$printer->text($company->nit . "\n");
		$printer->text("Dirección: ");
		$printer->text($company->address . "\n");
		$printer->setJustification(Printer::JUSTIFY_LEFT);
		$printer->setEmphasis(true);
		$printer->text("Cajero(a): ");
		$printer->text($system_user->name . "\n");
		$printer->setEmphasis(false);
		$printer->text("Fecha: ");
		$printer->text(date('Y-m-d h:i:s A') .  "\n");
		$printer->text("N° Factura: ");
		$printer->text($order->id . "\n");        // 
		$printer->text("Cliente: ");
		$printer->text($order->client->name . "\n");
		$printer->setLineSpacing(2);
		$printer->setJustification(Printer::JUSTIFY_CENTER);
		$printer->text("\n-----------------------------------------" . "\n\n");
		$printer->setLineSpacing(1);
		$printer->setEmphasis(true);
		$printer->text(sprintf('%-20s %+10.8s %+10.7s', 'ARTICULO', 'CANT', 'PRECIO'));
		$printer->text("\n-----------------------------------------" . "\n\n");
		$printer->setEmphasis(false);
		$printer->text("\n");
		$total = 0;

		foreach ($order_details as $df) {
			$line = sprintf('%-20s %10.0f %10.2f ', '-' . $df->product, $df->quantity, $df->price_tax_inc_total);
			$total +=  $df->price_tax_inc_total;
			$printer->text($line);
			$printer->text("\n");
		}
		$printer->text("\n==========================================" . "\n");
		$printer->setEmphasis(true);
		$printer->setLineSpacing(2);
		$printer->setTextSize(1, 2);
		$printer->text(sprintf('%-25s %+15.15s', 'TOTAL', number_format($total, 2)));
		$printer->setEmphasis(false);
		$printer->text("\n=========================================" . "\n\n");
		// $printer->text("\nTotal: $" . number_format($total, 2) . "\n");
		$printer->setTextSize(1, 1);
		
		$printer->setJustification(Printer::JUSTIFY_CENTER);
		$printer->setLineSpacing(2);
		$printer->text("\n******************************************\n");
		$printer->setEmphasis(false);
		$printer->text("Gracias por su compra\n");
		$printer->text("\n******************************************\n");
		$printer->setFont(Printer::MODE_FONT_B);
		$printer->text("Sasseri");
		$printer->text("\nwww.fractalagenciadigital.com\n");



		$printer->cut();
		$printer->pulse();
		$printer->close();

		return redirect()->back()->with("mensaje", "Ticket impreso");
	}
}
