<?php

namespace App\Http\Controllers\Api;

use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProjectsController extends Controller
{
    public function index(Request $request)
    {
        try {
            return response(Project::all(), 200);
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
                'description' => 'string',
                'user_id' => 'bail|nullable|integer|min:1|exists:users,id',
            ]);

            if ($validator->fails()) {
                return response(['errors' => $validator->errors()->all()], 422);
            }

            Project::create($request->all());

            return response(['message' => 'Project created successfully.'], 201);
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

    public function update(Request $request, $id) {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|string',
                'user_id' => 'sometimes|bail|nullable|integer|min:1|exists:users,id',
            ]);

            if ($validator->fails()) {
                return response(['errors' => $validator->errors()->all()], 422);
            }

            $project = Project::findOrFail($id);
            $project->fill(auth()->user()->is_admin ? $request->all() : $request->except(['user_id']));
            $project->save();

            return response(['message' => 'Project updated successfully.'], 200);
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

    public function destroy(Request $request, $id) {
        try {
            $project = Project::findOrFail($id);
            $project->delete();

            return response(['message' => 'Project deleted successfully.'], 200);
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
