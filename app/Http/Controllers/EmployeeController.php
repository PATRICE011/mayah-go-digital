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
            $search = $request->input('search', ''); // Capture the search term

            $employees = User::with('role')
                ->where('role_id', 2) // Assuming role_id 2 is for employees
                ->when($search != '', function ($query) use ($search) {
                    return $query->where('name', 'like', "%{$search}%")
                        ->orWhere('mobile', 'like', "%{$search}%");
                })
                ->paginate(10);


            return response()->json($employees);
        }

        return view('admins.adminemployee');
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

    public function update(Request $request, $id)
    {
        // Validate the incoming data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|regex:/^[0-9]+$/|min:10|max:15', // Validate phone number format
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Check if the employee exists
            $employee = DB::table('users_area')->where('id', $id)->first();

            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found.',
                ], 404);
            }

            // Update the employee's data using the DB facade
            DB::table('users_area')
                ->where('id', $id)
                ->update([
                    'name' => $request->name,
                    'mobile' => $request->mobile,
                    'updated_at' => now(), // Update the timestamp
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Employee updated successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the employee.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function exportEmployees()
    {
        // Fetch all employees (modify as needed, e.g., filtering, sorting)
        $employees = User::where('role_id', 2)->get(); // Assuming role_id = 2 is for employees

        // Return a view specifically designed for printing
        return view('admins.export-employees', compact('employees'));
    }
}
