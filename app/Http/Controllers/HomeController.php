<?php

namespace App\Http\Controllers;

use App\Models\Main\AttachedFileModel;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('home');
    }

    public function changeFile(Request $request)
    {
        /** upload quotation files */
        // upload the receipt files
        if ($request->hasFile('change_file')) {
            $file = $request->file('change_file');
            $filenameWithExt = preg_replace("/[^a-zA-Z]+/", "_", $file->getClientOriginalName());
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //get size
            $size = $file->getSize() * 0.000001;
            // Get just ext
            $extension = $file->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = trim(preg_replace('/\s+/', ' ', $filename . '_' . time() . '.' . $extension));
            // Upload File
            $path = $file->storeAs($request->path, $fileNameToStore);

            //update
            $model = AttachedFileModel::find($request->change_file_id);
            //unlink the old one
            $old_name = $model->name;

//            $fasdf = $request->path.''.$old_name;
//            unlink(storage_path($fasdf));
//            dd($request->path.'/'.$old_name);
            $model->name = $fileNameToStore;
            $model->extension = $extension;
            $model->file_size = $size;
            $model->location = $path;
            $model->save();


        }
        return Redirect::route('petty-cash-home')->with('message', "File Has Been Updated Successfully");

    }

    public function addFile(Request $request)
    {
        /** upload quotation files */
        // upload the receipt files
        if ($request->hasFile('add_file1')) {

            $file = $request->file('add_file1');
            $filenameWithExt = preg_replace("/[^a-zA-Z]+/", "_", $file->getClientOriginalName());
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //get size
            $size = $file->getSize() * 0.000001;
            // Get just ext
            $extension = $file->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = trim(preg_replace('/\s+/', ' ', $filename . '_' . time() . '.' . $extension));
            // Upload File
            $path = $file->storeAs($request->path1, $fileNameToStore);


            //add the receipt record
            $file = AttachedFileModel::updateOrCreate(
                [
                    'name' => $fileNameToStore,
                    'location' => $path,
                    'extension' => $extension,
                    'file_size' => $size,
                    'form_id' => $request->form_id1,
                    'form_type' => $request->form_type1,
                    'file_type' => $request->file_type1
                ],
                [
                    'name' => $fileNameToStore,
                    'location' => $path,
                    'extension' => $extension,
                    'file_size' => $size,
                    'form_id' => $request->form_id1,
                    'form_type' => $request->form_type1,
                    'file_type' => $request->file_type1
                ]
            );

            //
            return Redirect::route('petty-cash-home')->with('message', "File Has Been Added Successfully");

        }
        return Redirect::route('petty-cash-home')->with('error', "File failed to upload");

    }

    public function addFile2(Request $request)
    {
        /** upload quotation files */
        // upload the receipt files
        if ($request->hasFile('add_file2')) {

            $file = $request->file('add_file2');
            $filenameWithExt = preg_replace("/[^a-zA-Z]+/", "_", $file->getClientOriginalName());
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //get size
            $size = $file->getSize() * 0.000001;
            // Get just ext
            $extension = $file->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = trim(preg_replace('/\s+/', ' ', $filename . '_' . time() . '.' . $extension));
            // Upload File
            $path = $file->storeAs($request->path2, $fileNameToStore);

            //add the receipt record
            $file = AttachedFileModel::updateOrCreate(
                [
                    'name' => $fileNameToStore,
                    'location' => $path,
                    'extension' => $extension,
                    'file_size' => $size,
                    'form_id' => $request->form_id2,
                    'form_type' => $request->form_type2,
                    'file_type' => $request->file_type2
                ],
                [
                    'name' => $fileNameToStore,
                    'location' => $path,
                    'extension' => $extension,
                    'file_size' => $size,
                    'form_id' => $request->form_id2,
                    'form_type' => $request->form_type2,
                    'file_type' => $request->file_type2
                ]
            );

            //
            return Redirect::route('petty-cash-home')->with('message', "File Has Been Added Successfully");

        }
        return Redirect::route('petty-cash-home')->with('error', "File failed to upload");

    }


}




