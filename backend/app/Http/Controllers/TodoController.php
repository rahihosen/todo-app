<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    public function index(Request $request)
    {


        $todos = Todo::where('user_id', $request->user()->id)->get();
        return response()->json($todos);
    }

    public function store(Request $request)
    {
        $request->validate(['title' => 'required']);

        try {
            $todo = new Todo();
            $todo->title = $request->title;
            $todo->description = $request->description;
            $todo->status = 'pending';
            $todo->user_id = Auth::id(); // Assuming you have authentication set up
            $todo->save();

            return response()->json($todo, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error creating todo'], 500);
        }
    }

    public function update(Request $request, Todo $todo)
    {
        if ($todo->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate(['title' => 'required']);

        try {
            $todo->title = $request->title;
            $todo->description = $request->description;
            $todo->status = $request->status;
            $todo->save();

            return response()->json($todo);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating todo'], 500);
        }
    }

    public function destroy(Request $request, Todo $todo)
    {
        try {
            if ($todo->user_id !== $request->user()->id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            $todo->delete();

            return response()->json(['message' => 'Todo deleted']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting todo'], 500);
        }
    }
}
