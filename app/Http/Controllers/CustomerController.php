<?php

namespace App\Http\Controllers;

use App\Jobs\Exportdata;
use App\Models\customer;
use App\Models\Page;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Support\Jsonable;
use function PHPUnit\Framework\returnArgument;
use App\Jobs\Importcustomerdata;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $data = customer::all();

//        return Customer::paginate(2);

        return response($data, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Application|ResponseFactory|Response
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
     * @param customer $customer
     * @return void
     */
    public function show(customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param customer $customer
     * @return void
     */
    public function edit(customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param customer $customer
     * @return Response
     */
    public function update(Request $request, customer $customer)
    {


        $customer->update($request->all());

        return response('success', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param customer $customer
     * @return Response
     */
    public function destroy(customer $customer)
    {
        $customer->delete();
        return response()->json('success');
    }

    public function filter(Request $request)
    {
        $text = $request->text;
        $status = $request->status;

        $status1 = null;
        $status2 = null;

        if (isset($status)) {
            if (sizeof($status) === 1) {
                $status1 = $status[0];
            } else {
                $status1 = $status[0];
                $status2 = $status[1];
            }

            $data=customer::where('status',$status1)->
                orwhere('status',$status2)
                ->get();


        }
        if (isset($text)){

            $data = customer::where('name', 'LIKE', '%' . $text . '%')->orWhere('email', 'LIKE', '%' . $text . '%')->get();

        }


        return response($data, 200);

    }

    public function import(Request $request)
    {
        $file = $request->file('file');
        $email=$request->input('email');


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

                    for ($c = 0; $c < $num; $c++) {

                        $importData_arr[$i][] = $filedata [$c];
                    }
                    $i++;
                }
                fclose($file);

                // Insert to MySQL database


                //dispatch through in jobs
                Importcustomerdata::dispatch($importData_arr,$email);

//                foreach (array_slice($importData_arr,1) as $importData) {
//
//                    $insertData = array(
//                        "name" => $importData[1],
//                        "email" => $importData[2],
//                        "phone" => $importData[3],
//                        "address" => $importData[4]);
//
//                customer::insert($insertData);
//
//                }

                return response('successfully import', 200);
            } else {
                return response('File too large. File must be less than 2MB');
            }
        } else {
            return response('Invalid File Extension');
        }

    }
    public function export(Request $request){


        $email=$request->email;

        Exportdata::dispatch($email);
       return response('success',200);
    }
    public function statusFilter(Request $request){



         $data = customer::orderBy('id',$request->order[0])->get();


        return response($data,200);


    }
    public function  datefilter(Request $request){


        $startDate= $request->startDate;
        $endDate =$request->endDate;
        dd($startDate);

        $startDate = Carbon::createFromFormat('d/m/Y', '01/01/2021');
        $endDate = Carbon::createFromFormat('d/m/Y', '06/01/2021');
        $data = customer::select('id', 'name', 'email','phone','address', 'created_at')
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->get();

        return response($data,200);
    }
}
