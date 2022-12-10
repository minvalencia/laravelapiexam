<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use Validator;
use App\Models\User;

class AuthController extends Controller
{
  public function index(Request $request)
  {
    $user = User::orderBy('created_at', 'DESC')->get();
    return response()->json(['success' => true, 'data' => [$user], 'code' => 200]);
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'firstname' => 'required|string|max:255',
      'lastname' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'password' => 'required|string|min:8'
    ]);

    if ($validator->fails()) {
      return response()->json(['success' => false, 'message' => [$validator->errors()], 'code' => 500]);
    }

    $user = User::create([
      'first_name' => $request->firstname,
      'last_name' => $request->lastname,
      'email' => $request->email,
      'password' => Hash::make($request->password)
    ]);

    $token = $user->createToken('auth_token')->plainTextToken;
    return response()->json(['success' => true, 'data' => [$user], 'access_token' => $token, 'token_type' => 'Bearer', 'code' => 200]);
  }

  public function show($id)
  {
    $user = User::where('id', '=', $id)->first();
    if ($user) {
      return response()->json(['success' => true, 'data' => [$user->cars()->get()], 'code' => 200]);
    } else {
      return response()->json(['success' => false, 'message' => 'No such a record.', 'code' => 500]);
    }
  }

  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'firstname' => 'required|string|max:255',
      'lastname' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users,email,' . $id . 'id',
      'password' => 'required|string|min:8'
    ]);

    if ($validator->fails()) {
      return response()->json(['success' => false, 'message' => [$validator->errors()], 'code' => 500]);
    }
    $user = User::find($id);
    $user->update([
      'first_name' => $request->firstname,
      'last_name' => $request->lastname,
      'email' => $request->email,
      'password' => Hash::make($request->password)
    ]);
    return response()->json(['success' => true, 'data' => [$user], 'message' => 'Record has been saved.', 'code' => 200]);
  }

  public function destroy($id)
  {
    $user = User::find($id);
    if ($user) {
      if ($user->cars()->count() > 0) {
        return response()->json(['success' => false, 'message' => 'Record has not been deleted, because it has car records.', 'code' => 400]);
      } else {
        $user->delete();
        return response()->json(['success' => true, 'message' => 'Record has been deleted.', 'code' => 200]);
      }
    } else {
      return response()->json(['success' => false, 'message' => 'Record has not been deleted, something went wrong.', 'code' => 500]);
    }
  }

  public function login(Request $request)
  {
    if (!Auth::attempt($request->only('email', 'password'))) {
      return response()->json(['success' => false, 'message' => 'Unauthorized', 'code' => 500]);
    }

    $user = User::where('email', $request['email'])->firstOrFail();

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json(['success' => true, 'message' => 'Hi ' . $user->name . ', welcome to home', 'access_token' => $token, 'token_type' => 'Bearer', 'code' => 200]);
  }
}
