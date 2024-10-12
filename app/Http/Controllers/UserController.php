<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Login;
use App\Models\Organization;
use App\Models\Branch;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function createUser(Request $request)
    {
        $request->validate([
            'fullname' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'cccd' => 'required',
            'organizationsID' => 'required',
            'branchID' => 'required',
            'typeusers' => 'required|in:admin,doctor',
            'password' => 'required',
        ]);

        $organization = Organization::where('tokenorg', $request->organizationsID)->first();
        if (!$organization) {
            return response()->json([
                'message' => 'Organization not found. Please provide a valid organizationsID.'
            ], 404);
        }

        $branch = Branch::where('tokenbranch', $request->branchID)
            ->where('organizationsID', $request->organizationsID)
            ->first();
        if (!$branch) {
            return response()->json([
                'message' => 'Branch not found or does not belong to the provided organization.'
            ], 404);
        }

        $user = User::create([
            'fullname' => $request->fullname,
            'address' => $request->address,
            'organizationalvalue' => 'User',
            'phone' => $request->phone,
            'imgidentification' => '',
            'cccd' => $request->cccd,
            'tokenuser' => uniqid('user_'),
            'organizationsID' => $request->organizationsID,
            'branchID' => $request->branchID,
        ]);

        $login = Login::create([
            'cccd' => $user->cccd,
            'typeusers' => $request->typeusers,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'User and login created successfully!',
            'user' => $user,
        ], 201);
    }

    public function updateUser(Request $request, $cccd)
    {
        $request->validate([
            'fullname' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|required|string|max:255',
            'phone' => 'sometimes|required|string|max:20',
        ]);

        $user = User::where('cccd', $cccd)->first();
        if (!$user) {
            return response()->json([
                'message' => 'User not found.'
            ], 404);
        }

        $user->update($request->only(['fullname', 'address', 'phone']));

        return response()->json([
            'message' => 'User information updated successfully!',
            'user' => $user,
        ]);
    }

    public function changePassword(Request $request, $cccd)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required',
        ]);

        $login = Login::where('cccd', $cccd)->first();
        if (!$login) {
            return response()->json([
                'message' => 'Login not found for this User.'
            ], 404);
        }

        if (!Hash::check($request->old_password, $login->password)) {
            return response()->json([
                'message' => 'Old password is incorrect.'
            ], 400);
        }

        $login->password = Hash::make($request->new_password);
        $login->save();

        return response()->json([
            'message' => 'Password changed successfully!',
        ]);
    }
}
