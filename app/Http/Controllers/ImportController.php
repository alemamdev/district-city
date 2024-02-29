<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\City;

class ImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv',
        ]);

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            
            // Generate a unique file name to avoid conflicts
            $fileName = time() . '_' . $file->getClientOriginalName();
            
            // Move the uploaded file to a temporary location
            $file->move(storage_path('uploads'), $fileName);
            
            // Build the full path to the uploaded file
            $filePath = storage_path('uploads') . DIRECTORY_SEPARATOR . $fileName;

            // Import data from the uploaded CSV file
            try {
                (new FastExcel)->import($filePath, function ($line) {
                    // dd($line);
                    City::create([
                        'district' => $line['district'],
                        'area' => $line['area'],
                        'post_code' => $line['post_code'],
                        'charge' => $line['charge'],
                    ]);
                });
                
                // Delete the temporary file after import
                unlink($filePath);
                
                return response('Cities imported successfully.');
            } catch (\Exception $e) {
                // Handle any exceptions that occur during the import process
                unlink($filePath); // Delete the temporary file if an error occurs
                return response('An error occurred while importing cities: ' . $e->getMessage());
            }
        }

        // If no file is provided or an unexpected error occurs, redirect back with an error message
        return response('No file provided or invalid file format.');
    }
}
