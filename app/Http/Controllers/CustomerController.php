<?php

namespace App\Http\Controllers;

use App\Models\customer;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use function PHPUnit\Framework\returnArgument;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = customer::all();

        return response($data, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required|numeric',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return response($validator->errors(), 422);
        } else {

            $customer = new customer;
            $customer->name = $request->name;
            $customer->email = $request->email;
            $customer->phone = $request->phone;
            $customer->address = $request->address;
            $customer->save();

        }


        return Response($customer, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\customer $customer
     * @return \Illuminate\Http\Response
     */
    public function show(customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\customer $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\customer $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, customer $customer)
    {


        $customer->update($request->all());

        return response('success', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\customer $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(customer $customer)
    {
        $customer->delete();
        return response()->json('success');
    }

    public function filter(Request $request)
    {


        $data = customer::where('name', 'LIKE', '%' . $request->text . '%')->orWhere('email', 'LIKE', '%' . $request->text . '%')->get();


        return response($data, 200);

    }

    public function import(Request $request)
    {
        $file = $request->file('file');


        // File Details
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $tempPath = $file->getRealPath();
        $fileSize = $file->getSize();
        $mimeType = $file->getMimeType();

        // Valid File Extensions
        $valid_extension = array("csv");

        // 2MB in Bytes
        $maxFileSize = 2097152;

        // Check file extension
        if (in_array(strtolower($extension), $valid_extension)) {

            // Check file size
            if ($fileSize <= $maxFileSize) {

                // File upload location
                $location = 'uploads';

                // Upload file
                $file->move($location, $filename);

                // Import CSV to Database
                $filepath = public_path($location . "/" . $filename);

                // Reading file
                $file = fopen($filepath, "r");

                $importData_arr = array();
                $i = 0;

                while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {

                    $num = count($filedata);

                    // Skip first row (Remove below comment if you want to skip the first row)
                    /*if($i == 0){
                       $i++;
                       continue;
                    }*/
                    for ($c = 0; $c < $num; $c++) {

                        $importData_arr[$i][] = $filedata [$c];
                    }
                    $i++;
                }
                fclose($file);

                // Insert to MySQL database
                foreach (array_slice($importData_arr,1) as $importData) {

                    $insertData = array(
                        "name" => $importData[1],
                        "email" => $importData[2],
                        "phone" => $importData[3],
                        "address" => $importData[4]);

                customer::insert($insertData);



                }

                return response('successfully import', 200);
            } else {
                return response('File too large. File must be less than 2MB');
            }
        } else {
            return response('Invalid File Extension');
        }

    }





}
