<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use App\Models\Organization;

class BranchController extends Controller
{
    public function addBranch(Request $request)
    {
        $request->validate([
            'branchname' => 'required',
            'branchaddress' => 'required',
            'branchphone' => 'required',
            'branchbusinesslicense' => 'required',
            'organizationsID' => 'required',
        ]);

        $organization = Organization::where('_id', $request->organizationsID)->first();

        if (!$organization) {
            return response()->json([
                'message' => 'Organization not found. Please provide a valid organizationsID.'
            ], 404);
        }

        $branch = Branch::create([
            'branchname' => $request->branchname,
            'branchaddress' => $request->branchaddress,
            'branchphone' => $request->branchphone,
            'branchbusinesslicense' => $request->branchbusinesslicense,
            'organizationsID' => $request->organizationsID,
        ]);

        return response()->json($branch, 201);
    }

    public function updateBranch(Request $request, $id)
    {
        $branch = Branch::find($id);
        if (!$branch) {
            return response()->json(['message' => 'Branch not found'], 404);
        }
        $branch->update($request->all());
        return response()->json($branch, 200);
    }

    public function deleteBranch($id)
    {
        $branch = Branch::find($id);
        if (!$branch) {
            return response()->json(['message' => 'Branch not found'], 404);
        }
        $branch->delete();
        return response()->json(['message' => 'Branch deleted successfully'], 200);
    }
}
