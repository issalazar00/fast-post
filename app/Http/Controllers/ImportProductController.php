<?php

namespace App\Http\Controllers;

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
            $request->file->move($upload_path, $generated_new_name);

            return response()->json(['success' => 'You have successfully uploaded "' . $file_name . '"']);
        }
    }

    public function uploadData($file)
    {
        $helper = new Sample();

        // $inputFileName = __DIR__ . '/sampleData/example1.xls';
        $inputFileName = $file;
        $helper->log('Loading file ' . pathinfo($inputFileName, PATHINFO_BASENAME) . ' using IOFactory to identify the format');
        $spreadsheet = IOFactory::load($inputFileName);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        echo '<pre>';
        var_dump($sheetData);
        echo '</pre>';
    }
}
