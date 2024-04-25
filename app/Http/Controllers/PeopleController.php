<?php

namespace App\Http\Controllers;

use App\Exports\PeopleExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PeopleController extends Controller
{
    public function index()
    {
        return view('users.index');
    }

    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required'
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }

        /*
         * Store File
         * Return path and file content
         */
        $file = $request->file('file');
        $path = $file->store('json_file', 'public');

        $characters = file_get_contents($file);
        $data = json_decode($characters, true);

        return response(
            compact('data', 'path')
        );
    }

    public function export(Request $request)
    {
        $path = request('path');

        /*
         * Download
         */
        return (new PeopleExport($path))->download('people'.time().'.xlsx');

        /*
         * Queue
         */
//        (new PeopleExport($path))->queue('people'.time().'.xlsx');
//        return back()->withSuccess('Export started!');
    }
}
