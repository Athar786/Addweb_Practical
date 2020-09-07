<?php

namespace App\Http\Controllers;

use App\Employee;
use Illuminate\Http\Request;
use Redirect,Response;
use Validator;


class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax())
        {
            return datatables()->of(Employee::latest()->get())
            ->addColumn('action',function($data){
                $button= '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-primary btn-sm">Edit</button>';
                return $button;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        
        return view('employee');
                
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('employee');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $valid = array(
            'name'=>'required',
            'address'=>'required',
            'contact' =>'required',
            'image' => 'required|image|max:2048'
        );
        $error = Validator::make($request->all(),$valid);
        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }
        $image = $request->file('image');
        $new_name = rand(). '.' .$image->getClientOriginalExtension();
        $image->move(public_path('images'), $new_name);

        $form_data = array(
            'name' => $request->name,
            'address'=> $request->address,
            'contact' => $request->contact,
            'image' => $new_name
        );
        Employee::create($form_data);
        return response()->json(['success' => 'Empployess Added successfully']);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(request()->ajax())
        {
            $data = Employee::findOrFail($id);
            return response()->json(['data' => $data]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        $image_name = $request->hidden_image;
        $image = $request->file('image');
        if($image != '')
        {
            $valid = array(
                'name'    =>  'required',
                'address'     =>  'required',
                'contact'     =>  'required',
                'image'         =>  'image|max:2048'
            );
            $error = Validator::make($request->all(), $valid);
            if($error->fails())
            {
                return response()->json(['errors' => $error->errors()->all()]);
            }

            $image_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $image_name);
        }
        else
        {
            $valid = array(
                'name'    =>  'required',
                'address'     =>  'required',
                'contact'     =>  'required'
            );

            $error = Validator::make($request->all(), $valid);

            if($error->fails())
            {
                return response()->json(['errors' => $error->errors()->all()]);
            }
        }
        $form_data = array(
            'name' => $request->name,
            'address'=> $request->address,
            'contact' => $request->contact,
            'image' => $image_name
        );
        Employee::whereId($request->hidden_id)->update($form_data);
        return response()->json(['success' => 'Data is successfully updated']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        //
    }
}
