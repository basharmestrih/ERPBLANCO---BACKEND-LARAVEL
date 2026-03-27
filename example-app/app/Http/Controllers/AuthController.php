<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();

        return UserResource::collection($users);
    }

    public function roles()
    {
        return response()->json(
            Role::query()
                ->select('id', 'name')
                ->orderBy('name')
                ->get()
        );
    }


    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();

        $token = $user->createToken('ERPToken')->plainTextToken;

        return response()->json([
            'user'  => $user->load('roles'),
            'token' => $token
        ]);
    }

    public function me()
    {
        $user = Auth::user()->load('roles');

        return response()->json([
            'user' => $user,
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('name')
        ]);
    }


    public function register(Request $request)
    {
        $this->authorize('create user');

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        return response()->json([
            'user' => $user->load('roles')
        ], 201);
    }


    public function update(Request $request, $id)
    {
        $this->authorize('update user');

        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $request->validate([
            'name'  => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only(['name', 'email']));

        return response()->json([
            'user' => $user
        ]);
    }


    public function assignRole(Request $request, $id)
    {
        $this->authorize('assign roles');

        $request->validate([
            'role' => 'required|exists:roles,name'
        ]);

        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->syncRoles([$request->role]);

        return response()->json([
            'message' => 'Role updated successfully',
            'roles' => $user->getRoleNames()
        ]);
    }


    public function destroy($id)
    {
        $this->authorize('delete user');

        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }
}
