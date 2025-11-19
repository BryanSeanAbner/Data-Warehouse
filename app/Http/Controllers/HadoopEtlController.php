<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

class HadoopEtlController extends Controller
{
    public function index()
    {
        // Get list of CSV files in input folder
        $inputFiles = [];
        $inputPath = storage_path('hadoop/input');
        if (is_dir($inputPath)) {
            $files = scandir($inputPath);
            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'csv') {
                    $inputFiles[] = [
                        'name' => $file,
                        'size' => filesize($inputPath . '/' . $file),
                        'modified' => filemtime($inputPath . '/' . $file),
                    ];
                }
            }
        }

        // Get list of processed TSV files
        $processedFiles = [];
        $processedPath = storage_path('hadoop/processed');
        if (is_dir($processedPath)) {
            $files = scandir($processedPath);
            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'tsv') {
                    $processedFiles[] = [
                        'name' => $file,
                        'size' => filesize($processedPath . '/' . $file),
                        'modified' => filemtime($processedPath . '/' . $file),
                    ];
                }
            }
        }

        // Get Hadoop import stats
        $totalImported = DB::table('retail_sales_fact')->count();
        $lastImport = DB::table('retail_sales_fact')
            ->orderBy('date_key', 'desc')
            ->first();

        return view('hadoop.index', compact('inputFiles', 'processedFiles', 'totalImported', 'lastImport'));
    }

    public function uploadCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240', // Max 10MB
        ]);

        $file = $request->file('csv_file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(storage_path('hadoop/input'), $filename);

        return redirect()->route('hadoop.index')
            ->with('success', "File {$filename} berhasil diupload!");
    }

    public function runMapReduce()
    {
        // This would trigger WSL bash script
        // For now, return instruction
        return response()->json([
            'status' => 'info',
            'message' => 'Jalankan command berikut di WSL:',
            'command' => 'cd /mnt/c/laragon/www/Data-Warehouse/storage/hadoop/scripts && bash run_etl_root.sh',
        ]);
    }

    public function importTsv(Request $request)
    {
        $request->validate([
            'tsv_file' => 'required|string',
        ]);

        $filename = $request->input('tsv_file');
        
        try {
            // Run artisan command
            Artisan::call('hadoop:import', ['file' => $filename]);
            $output = Artisan::output();

            return redirect()->route('hadoop.index')
                ->with('success', "Import berhasil! {$output}");
        } catch (\Exception $e) {
            return redirect()->route('hadoop.index')
                ->with('error', "Import gagal: " . $e->getMessage());
        }
    }

    public function exportToHadoop()
    {
        try {
            // Run export command
            Artisan::call('export:sqlcsv');
            $output = Artisan::output();

            return redirect()->route('hadoop.index')
                ->with('success', "Export berhasil! {$output}");
        } catch (\Exception $e) {
            return redirect()->route('hadoop.index')
                ->with('error', "Export gagal: " . $e->getMessage());
        }
    }

    public function deleteFile(Request $request)
    {
        $request->validate([
            'file_path' => 'required|string',
            'file_type' => 'required|in:input,processed',
        ]);

        $type = $request->input('file_type');
        $filename = basename($request->input('file_path'));
        $path = storage_path("hadoop/{$type}/{$filename}");

        if (file_exists($path)) {
            unlink($path);
            return redirect()->route('hadoop.index')
                ->with('success', "File {$filename} berhasil dihapus!");
        }

        return redirect()->route('hadoop.index')
            ->with('error', "File tidak ditemukan!");
    }
}
