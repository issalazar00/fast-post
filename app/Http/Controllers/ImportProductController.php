<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Helper\Sample; //eliminar


class ImportProductController extends Controller
{
  public function downloadExample()
  {
    $path = public_path('files/productos.xls');
    return response()->download($path);
  }

  public function uploadFile(Request $request)
  {
    $request->validate([
      'file' => 'required|mimes:csv,xlx,xls|max:2048'
    ]);

    if ($request->file()) {
      $upload_path = public_path('uploads');
      $file_name = $request->file->getClientOriginalName();
      $generated_new_name = time() . '.' . $request->file->getClientOriginalExtension();

      if ($request->file->move($upload_path, $generated_new_name)) {
        $products = $this->uploadData((public_path('uploads')) . '\\' . $generated_new_name);


        $this->inserData($products);


        // return response()->json(['success' => 'You have successfully uploaded "' . $file_name . '"']); //Descartar
      }
    }
  }

  public function uploadData($file)
  {
    $helper = new Sample();
    $sheetData = array();
    $inputFileName = $file;
    $spreadsheet = IOFactory::load($inputFileName);
    $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
    return ['products' => $sheetData];
  }

  public function inserData($productsData)
  {
    if (is_array($productsData) && count($productsData) > 0) {
      $product = new Product();
      foreach ($productsData as $item) {
        foreach ($item as $p) {
          $tax_id = 1;
          $tax_percentage = 0;
          $percentage = 0;
          if (isset($p["K"]) && $p["K"] != '') {
            $tax = Tax::firstOrCreate(['percentage' => $p["K"]]);
            $tax_id = $tax->id;
            $tax_percentage = $tax->percentage;
            if ($tax_percentage > 0) {
              $percentage = ($tax->percentaje / 100) + 1;
            }
          } else {
            $tax = Tax::firstOrCreate(['percentage' => '0']);
            $tax_id = $tax->id;
            $percentage = 0;
          }

          $type = 1;

          if (isset($p["J"])) {
            if ($p["J"] == 'UNIDAD') {
              $type =  1;
            }
            if ($p["J"] == 'GRANEL') {
              $type =  2;
            }
          }


          if (isset($p["C"])) {
            $cost_price = (float) str_replace(',', '', preg_replace('/[$\@\;\" "]+/', '', $p["C"]));
            $sale_price_tax_inc = (float) str_replace(',', '', preg_replace('/[$\@\;\" "]+/', '', $p["D"]));
            $wholesale_price_tax_inc = (float) str_replace(',', '', preg_replace('/[$\@\;\" "]+/', '', $p["E"]));
            $quantity = (float) str_replace(',', '', preg_replace('/[$\@\;\" "]+/', '', $p["G"]));
            $minimum = (float) str_replace(',', '', preg_replace('/[$\@\;\" "]+/', '', $p["H"]));
            $maximum = (float) str_replace(',', '', preg_replace('/[$\@\;\" "]+/', '', $p["I"]));

            if (!is_string($quantity)) {
              $product = new Product();
              $product->barcode = $p["A"];
              $product->product = $p["B"];
              $product->cost_price = $cost_price;
              $product->sale_price_tax_inc = $sale_price_tax_inc;
              $product->wholesale_price_tax_inc = $wholesale_price_tax_inc;
              $product->category_id = 1;
              $product->quantity = $quantity;
              $product->minimum = $minimum;
              $product->maximum = $maximum;
              $product->type = $type;
              $product->tax_id = $tax_id;

              if ($percentage != 0) {
                $product->sale_price_tax_exc = ($sale_price_tax_inc) / ($percentage);
              } else {
                $product->sale_price_tax_exc = ($sale_price_tax_inc);
              }

              if ($percentage != 0) {
                $product->wholesale_price_tax_exc = ($wholesale_price_tax_inc) / ($percentage);
              } else {
                $product->wholesale_price_tax_exc = ($wholesale_price_tax_inc);
              }
              $product->gain = $product->wholesale_price_tax_exc - $product->wholesale_price_tax_inc;
              $product->save();
            }
          }
        }
      }
    }
  }
}
