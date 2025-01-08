<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    //

    public function adminemployee(Request $request)
    {
        if ($request->ajax()) {
            $employees = User::with('role') // Assuming 'role' is the correct relationship
                ->where('role_id', 2)  // Filtering by role_id
                ->where(function ($query) use ($request) {
                    if (!empty($request->search)) {
                        $query->where('name', 'like', "%{$request->search}%")
                            ->orWhere('email', 'like', "%{$request->search}%");  // Assuming you might also want to search by email
                    }
                })
                ->paginate(10); // Adjust pagination as needed

            return response()->json($employees);
        }

        return view("admins.adminemployee");
    }

    public function store(Request $request)
    {
        // Validate the incoming data except for the unique mobile for now
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'mobile' => 'required|string',  // Temporarily remove the unique rule
            'password' => 'required|string|min:5',
        ]);

        // Early return if validation fails on basic fields
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // Check for existing mobile number in the database
        $existingUser = DB::table('users_area')->where('mobile', $request->mobile)->first();
        if ($existingUser) {
            // Manually add an error message for the mobile number
            return response()->json([
                'success' => false,
                'errors' => ['mobile' => ['The mobile number is already registered. Please use a different number.']]
            ], 422);
        }

        // Insert a new user into the database using the DB facade
        DB::table('users_area')->insert([
            'name' => $request->name,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),  // Hash the password securely
            'role_id' => 2,  // Assuming '2' is the role ID for employees
            'created_at' => now(),  // Manually handle timestamps
            'updated_at' => now(),
        ]);

        // Return a successful response
        return response()->json(['success' => true, 'message' => 'Employee added successfully!']);
    }

    public function delete($id)
    {
        try {
            // Find the employee by ID
            $employee = User::find($id);

            // Check if employee exists
            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found.'
                ], 404);
            }

            // Delete the employee
            $employee->delete();

            return response()->json([
                'success' => true,
                'message' => 'Employee deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the employee.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
