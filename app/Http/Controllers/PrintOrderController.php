<?php

namespace App\Http\Controllers;

use App\Models\Order;
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


		// Información empresarial

		// Config de impresora

		$id_factura = $request->id;



		$detalle_facturacion = DetalleFacturacion::leftJoin('facturacion', 'detalle_facturacion.id_factura', '=', 'facturacion.id')
			->leftJoin('articulos', 'detalle_facturacion.id_producto', '=', 'articulos.id')
			->leftJoin('presentacion', 'articulos.id_presentacion', '=', 'presentacion.id')
			->select()
			->where('detalle_facturacion.id_factura', '=', $id_factura)
			->get();

		$facturacion = Facturacion::leftJoin('personas', 'personas.id', 'facturacion.id_tercero')
			->leftJoin('personas as p_usuarios', 'p_usuarios.id', 'facturacion.id_usuario')
			->leftJoin('zona', 'zona.id', 'facturacion.lugar')
			->select('facturacion.id as id', 'facturacion.fec_crea', 'personas.nombre1', 'personas.nombre2', 'personas.apellido1', 'personas.apellido2', 'p_usuarios.nombre as cajero', 'p_usuarios.id as idusuario', 'personas.id as idpersona', 'total', 'zona.zona', 'facturacion.num_factura')->where('facturacion.id', '=', $id_factura)
			->first();
		// ->get();


		$connector = new WindowsPrintConnector('cocina');

		$impresora = new Printer($connector);
		$impresora->initialize();
		// $impresora->setPrintWidth(25);
		$impresora->setJustification(Printer::JUSTIFY_CENTER);
		try {
			$logo = EscposImage::load('logo.jpg', false);
			$impresora->bitImage($logo);
		} catch (Exception $e) {
			/* Images not supported on your PHP, or image file not found */
			$impresora->text($e->getMessage() . "\n");
		}
		$impresora->text("\n===============================\n");
		$impresora->setTextSize(1, 2);
		$impresora->setEmphasis(true);
		$impresora->text($infoEmpresa->nombre . "\n");
		$impresora->setTextSize(1, 1);
		$impresora->setEmphasis(false);
		$impresora->text("NIT: ");
		$impresora->text($infoEmpresa->nit . "\n");
		$impresora->text("Dirección: ");
		$impresora->text($infoEmpresa->direccion . "\n");
		$impresora->setJustification(Printer::JUSTIFY_LEFT);
		$impresora->setEmphasis(true);
		$impresora->text("Mesa: ");
		$impresora->text($facturacion->zona . "\n");
		$impresora->text("Cajero(a): ");
		$impresora->text($facturacion->cajero . "\n");
		$impresora->setEmphasis(false);
		$impresora->text("Fecha: ");
		$impresora->text($facturacion->fec_crea . "\n");
		if (isset($facturacion->num_factura) || $facturacion->num_factura != NULL) {
			$impresora->text("N° Factura: ");
			$impresora->text($facturacion->num_factura . "\n");        // 
		}
		$impresora->text("Cliente: ");
		$impresora->text($facturacion->nombre1 . " " . $facturacion->nombre2 . " " . $facturacion->apellido1 . " " . $facturacion->apellido2 . "\n");
		$impresora->setLineSpacing(2);
		$impresora->setJustification(Printer::JUSTIFY_CENTER);
		$impresora->text("\n-----------------------------------------" . "\n\n");
		$impresora->setLineSpacing(1);
		$impresora->setEmphasis(true);
		$impresora->text(sprintf('%-20s %+10.8s %+10.7s', 'ARTICULO', 'CANT', 'PRECIO'));
		$impresora->text("\n-----------------------------------------" . "\n\n");
		$impresora->setEmphasis(false);
		$impresora->text("\n");
		$total = 0;

		foreach ($order_details as $df) {
			$line = sprintf('%-20s %10.0f %10.2f ', '-' . $df->product, $df->quantity, $df->precio);
			$total +=  $df->cantidad * $df->precio;
			$impresora->text($line);
			$impresora->text("\n");
		}
		$impresora->text("\n==========================================" . "\n");
		$impresora->setEmphasis(true);
		$impresora->setLineSpacing(2);
		$impresora->setTextSize(1, 2);
		$impresora->text(sprintf('%-25s %+15.15s', 'TOTAL', number_format($total, 2)));
		$impresora->setEmphasis(false);
		$impresora->text("\n=========================================" . "\n\n");
		// $impresora->text("\nTotal: $" . number_format($total, 2) . "\n");
		$impresora->setTextSize(1, 1);
		if (isset($request->valorEfectivo)) {
			$valorEfectivo = $request->valorEfectivo;

			$impresora->text(sprintf('%-25s %+15.15s', 'Efectivo:', number_format($valorEfectivo, 0) . "\n"));

			// $impresora->text("\nEfectivo: ".$valorEfectivo);
		}
		if (isset($request->valorCambio)) {
			$valorCambio = $request->valorCambio;
			// $impresora->text("\Cambio: ".$valorCambio);
			$impresora->text(sprintf('%-25s %+15.15s', 'Cambio:', number_format($valorCambio, 0) . "\n"));
		}
		$impresora->setJustification(Printer::JUSTIFY_CENTER);
		$impresora->setLineSpacing(2);
		$impresora->text("\n******************************************\n");
		$impresora->setEmphasis(false);
		$impresora->text("Gracias por su compra\n");
		$impresora->text("\n******************************************\n");
		$impresora->setFont(Printer::MODE_FONT_B);
		$impresora->text("Sasseri");
		$impresora->text("\nwww.fractalagenciadigital.com\n");



		$impresora->cut();
		$impresora->pulse();
		$impresora->close();

		return redirect()->back()->with("mensaje", "Ticket impreso");
	}
}
