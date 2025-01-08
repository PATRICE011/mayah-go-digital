<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    //

    public function adminemployee(Request $request)
    {
        if ($request->ajax()) {
            $employees = User::with('role') // Assuming relationship is set up correctly
                ->where(function($query) use ($request) {
                    // Filter conditions based on the request
                    if (!empty($request->search)) {
                        $query->where('name', 'like', "%{$request->search}%");
                    }
                })
                ->paginate(10); // Adjust the number as needed
    
            return response()->json($employees);
        }
    
        return view("admins.adminemployee");
    }
}
