<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Audit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
class CustomerController extends Controller
{
    //

    public function admincustomers(Request $request)
    {

        if ($request->ajax()) {
            $search = $request->input('search', ''); // Capture the search term

            $employees = User::with('role')
                ->where('role_id', 3) // Assuming role_id 2 is for employees
                ->when($search != '', function ($query) use ($search) {
                    return $query->where('name', 'like', "%{$search}%")
                        ->orWhere('mobile', 'like', "%{$search}%");
                })
                ->paginate(10);


            return response()->json($employees);
        }

        return view("admins.admincustomers");
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
                    'message' => 'Customer not found.'
                ], 404);
            }

            // Delete the employee
            $employee->delete();

            // Log the audit
            Audit::create([
                'user_id' => Auth::id(),
                'action' => 'Deleted a Customer',
                'model_type' => User::class,
                
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Customer deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the customer.',
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
                    'message' => 'Customer not found.',
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

            // Log the audit
            Audit::create([
                'user_id' => Auth::id(),
                'action' => 'Updated a Customer Information',
                'model_type' => User::class,
                
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Customer updated successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the Customer.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function exportEmployees()
    {
        // Fetch all employees (modify as needed, e.g., filtering, sorting)
        $employees = User::where('role_id', 3)->get(); // Assuming role_id = 2 is for employees

        // Return a view specifically designed for printing
        return view('admins.export-customers', compact('employees'));
    }
}
