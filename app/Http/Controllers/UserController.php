<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateStoreUserRequest;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $usernameQuery = $request->query('username');
        $emailQuery = $request->query('email');

        $users = DB::table('users');

        if ($usernameQuery) {
            $users->where('username', 'like', '%'. $usernameQuery. '%');
        }

        if ($emailQuery) {
            $users->where('email', 'like', '%'. $emailQuery. '%');
        }

        return response()->json(UserResource::collection($users->get()));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Str::random(8); // Le colocamos una contraseña por defecto

        $user = User::create($data);
        
        return response()->json(UserResource::make($user), 201);
    }

    public function show(User $user)
    {

        return response()->json(UserResource::make($user));
    }

    public function update(UpdateStoreUserRequest $request, User $user)
    {
        $data = $request->validated();
        $user->update($data);
 
        return response()->json(UserResource::make($user));
 
    }

    public function patch(Request $request, User $user)
    {
        $data = $request->only(['name', 'email', 'username']); 
        $user->update(array_filter($data)); 

        return response()->json(UserResource::make($user));
    }

    public function destroy(User $user)
    {
        $user->delete(); // This will now perform a soft delete

        return response()->json(['message' => 'El usuario ha sido eliminado correctamente.'], 204);
    }


}
