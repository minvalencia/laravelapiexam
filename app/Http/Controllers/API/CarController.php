<?php

namespace App\Http\Controllers\API;

use App\Models\Car;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class CarController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    //
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
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'customer_id' => 'required|integer|max:255',
      'name' => 'required|string|max:255',
      'model' => 'required|string|max:255',
    ]);

    if ($validator->fails()) {
      return response()->json(['success' => false, 'message' => [$validator->errors()], 'code' => 500]);
    }
    $user = User::find($request->customer_id);
    if (!$user) {
      return response()->json(['success' => true, 'message' => 'No record has been added because entered customer id did not match in any customer.', 'code' => 500]);
    }
    $car = Car::create([
      'user_id' => $request->customer_id,
      'name' => $request->name,
      'model' => $request->model
    ]);

    return response()->json(['success' => true, 'data' => [$car], 'message' => 'Record has been added.', 'code' => 200]);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'customer_id' => 'required|integer|max:255',
      'name' => 'required|string|max:255',
      'model' => 'required|string|max:255',
    ]);

    if ($validator->fails()) {
      return response()->json(['success' => false, 'message' => [$validator->errors()], 'code' => 500]);
    }
    $user = User::find($request->customer_id);
    if (!$user) {
      return response()->json(['success' => true, 'message' => 'No record has been added because entered customer id did not match in any customer.', 'code' => 500]);
    }
    $car = Car::find($id);
    $car->update([
      'user_id' => $request->customer_id,
      'name' => $request->name,
      'model' => $request->model
    ]);
    return response()->json(['success' => true, 'data' => [$car], 'message' => 'Record has been saved.', 'code' => 200]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $car = Car::destroy($id);
    if ($car) {
      return response()->json(['success' => true, 'message' => 'Record has been deleted.', 'code' => 200]);
    } else {
      return response()->json(['success' => false, 'message' => 'Record has not been deleted, something went wrong.', 'code' => 500]);
    }
  }
}
