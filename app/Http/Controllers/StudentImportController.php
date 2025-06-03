<?php

public function showImportForm()
    {
        return view('students.import');
    }

    public function downloadTemplate()
    {
        $filePath = storage_path('app/templates/student_import_template.xlsx');

        if (!file_exists($filePath)) {
            // You should create this template file in storage/app/templates
            return redirect()->back()->with('error', 'Template file not found.');
        }

        return response()->download($filePath, 'template_import_siswa.xlsx');
    }

    public function previewImport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('students.import')
                ->withErrors($validator)
                ->withInput();
        }

        $file = $request->file('excel_file');
        $filename = 'import_' . time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('temp_imports', $filename);

        try {
            $import = new StudentsImport();
            $previewData = $import->preview($file);

            return view('students.import', [
                'previewData' => $previewData,
                'filename' => $filename
            ]);

        } catch (\Exception $e) {
            return redirect()
                ->route('students.import')
                ->with('error', 'Error reading file: ' . $e->getMessage());
        }
    }

    public function processImport(Request $request)
    {
        $request->validate([
            'filename' => 'required|string'
        ]);

        $filePath = storage_path('app/temp_imports/' . $request->filename);

        if (!file_exists($filePath)) {
            return redirect()
                ->route('students.import')
                ->with('error', 'File not found. Please try again.');
        }

        try {
            $import = new StudentsImport();
            $import->setOptions([
                'skip_duplicates' => $request->has('skip_duplicates'),
                'update_existing' => $request->has('update_existing')
            ]);

            Excel::import($import, $filePath);

            // Delete the temp file
            Storage::delete('temp_imports/' . $request->filename);

            return redirect()
                ->route('students.index')
                ->with('success', 'Data siswa berhasil diimport: ' . $import->getRowCount() . ' data diproses.');

        } catch (\Exception $e) {
            return redirect()
                ->route('students.import')
                ->with('error', 'Error during import: ' . $e->getMessage());
        }
    }

