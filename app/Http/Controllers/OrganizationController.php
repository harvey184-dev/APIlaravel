<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use App\Models\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class OrganizationController extends Controller
{
    public function addOrganization(Request $request)
    {
        $request->validate([
            'nameorg' => 'required',
            'nameadmin' => 'required',
            'emailadmin' => 'required',
            'phoneadmin' => 'required',
            'businessBase64' => 'required',
            'password' => 'required',
            'addressadmin' => 'required',
            'imgidentification' => 'required'
        ]);


        $organization = Organization::create([
            'nameorg' => $request->nameorg,
            'nameadmin' => $request->nameadmin,
            'emailadmin' => $request->emailadmin,
            'addressadmin' => $request->addressadmin,
            'phoneadmin' => $request->phoneadmin,
            'businessBase64' => $request->businessBase64,
            'tokenorg' => uniqid('org_'),
            'statusorg' => 'active',
        ]);


        $adminUser = User::create([
            'fullname' => $request->nameadmin,
            'address' => $request->addressadmin,
            'organizationalvalue' => 'admin',
            'phone' => $request->phoneadmin,
            'imgidentification' => '',
            'cccd' => $request->cccd ?? uniqid('admin_'),
            'tokenuser' => uniqid('user_'),
            'organizationsID' => $organization->tokenorg,
            'branchID' => null,
        ]);


        $login = Login::create([
            'cccd' => $adminUser->cccd,
            'typeusers' => 'admin',
            'password' => Hash::make($request->password),
        ]);


        return response()->json([
            'message' => 'Organization, admin user, and login created successfully!',
            'organization' => $organization,
            'adminUser' => $adminUser,
        ], 201);
    }

    public function getOrganizationDetails($id)
    {
        $organization = Organization::find($id);
        if (!$organization) {
            return response()->json(['message' => 'Organization not found'], 404);
        }
        return response()->json($organization, 200);
    }

    public function changePassword(Request $request, $id)
    {
        $request->validate(['new_password' => 'required']);

        $organization = Organization::find($id);
        if (!$organization) {
            return response()->json(['message' => 'Organization not found'], 404);
        }

        $login = Login::where('cccd', $organization->cccd)->first();
        $login->password = bcrypt($request->new_password);
        $login->save();

        return response()->json(['message' => 'Password changed successfully'], 200);
    }

    public function updateOrganization(Request $request, $id)
    {
        $organization = Organization::find($id);
        if (!$organization) {
            return response()->json(['message' => 'Organization not found'], 404);
        }
        $organization->update($request->all());
        return response()->json($organization, 200);
    }

    public function getAllOrganization()
    {
        return response()->json(Organization::all(), 200);
    }
}
