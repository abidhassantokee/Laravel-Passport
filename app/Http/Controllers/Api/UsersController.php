<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        try {
            return response(User::all(), 200);
        } catch (HttpException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getStatusCode());
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json([
                    'message' => $e->getMessage(),
                    'trace' => $e->getTrace()
                ], 500);
            }
            return response()->json("Oops! Something went wrong.", 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
                'is_admin' => 'required|boolean'
            ]);

            if ($validator->fails()) {
                return response(['errors' => $validator->errors()->all()], 422);
            }

            User::create($request->all());

            return response(['message' => 'User created successfully.'], 201);
        } catch (HttpException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getStatusCode());
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json([
                    'message' => $e->getMessage(),
                    'trace' => $e->getTrace()
                ], 500);
            }
            return response()->json("Oops! Something went wrong.", 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|string|email|max:255|unique:users',
                'password' => 'sometimes|required|string|min:6|confirmed',
                'is_admin' => 'sometimes|required|boolean'
            ]);

            if ($validator->fails()) {
                return response(['errors' => $validator->errors()->all()], 422);
            }

            $user = User::findOrFail($id);
            $user->fill(auth()->user()->is_admin ? $request->all() : $request->except(['is_admin']));
            $user->save();

            return response(['message' => 'User updated successfully.'], 200);
        } catch (HttpException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getStatusCode());
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json([
                    'message' => $e->getMessage(),
                    'trace' => $e->getTrace()
                ], 500);
            }
            return response()->json("Oops! Something went wrong.", 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response(['message' => 'User deleted successfully.'], 200);
        } catch (HttpException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getStatusCode());
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json([
                    'message' => $e->getMessage(),
                    'trace' => $e->getTrace()
                ], 500);
            }
            return response()->json("Oops! Something went wrong.", 500);
        }
    }
}
