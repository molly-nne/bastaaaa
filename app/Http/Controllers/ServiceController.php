<?php

namespace App\Http\Controllers;
use App\Models\Service;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index() {
        return view('index');
    }
 
    public function fetchAll() {
        $service = Service::all();
        $output = '';
        if ($service->count() > 0) {
            $output .= '<table class="table table-striped align-middle">
            <thead>
              <tr>
                <th>ID</th>
                <th>Service Image</th>
                <th>Service Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>';
            foreach ($service as $rs) {
                $output .= '<tr>
                <td>' . $rs->id . '</td>
                <td><img src="storage/images/' . $rs->service_image . '" width="50" class="img-thumbnail rounded-circle"></td>
                <td>' . $rs->service_name .'</td>
                <td>' . $rs->description . '</td>
                <td>' . $rs->price . '</td>
                <td>
                  <a href="#" id="' . $rs->id . '" class="text-success mx-1 editIcon" data-bs-toggle="modal" data-bs-target="#editServiceModal"><i class="bi-pencil-square h4"></i></a>
                  <a href="#" id="' . $rs->id . '" class="text-danger mx-1 deleteIcon"><i class="bi-trash h4"></i></a>
                </td>
              </tr>';
            }
            $output .= '</tbody></table>';
            echo $output;
        } else {
            echo '<h1 class="text-center text-secondary my-5">No record in the database!</h1>';
        }
    }
 
    // insert a new services ajax request
    public function store(Request $request) {
        $file = $request->file('service_image');
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public/images', $fileName); //php artisan storage:link
 
        $empData = ['service_name' => $request->service_name, 'description' => $request->description, 'price' => $request->price, 'service_image' => $fileName];
        Service::create($empData);
        return response()->json([
            'status' => 200,
        ]);
    }
 
    // edit an services ajax request
    public function edit(Request $request) {
        $id = $request->id;
        $emp = Service::find($id);
        return response()->json($emp);
    }
 
    // update an services ajax request
    public function update(Request $request) {
        $fileName = '';
        $emp = Service::find($request->emp_id);
        if ($request->hasFile('service_image')) {
            $file = $request->file('service_image');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/images', $fileName);
            if ($emp->service_image) {
                Storage::delete('public/images/' . $emp->service_inmage);
            }
        } else {
            $fileName = $request->emp_service_image;
        }
 
        $empData = ['service_name' => $request->service_name, 'description' => $request->description, 'price' => $request->price, 'service_image' => $fileName];
 
        $emp->update($empData);
        return response()->json([
            'status' => 200,
        ]);
    }
 
    // delete an service ajax request
    public function delete(Request $request) {
        $id = $request->id;
        $emp = Service::find($id);
        if (Storage::delete('public/images/' . $emp->service_image)) {
            Service::destroy($id);
        }
    }

    // Add a method to export service data
    public function export() {
        $services = Service::all();
        return response()->json($services);
    }
}
