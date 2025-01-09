<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Audit;
class AuditController extends Controller
{
    //
    public function adminaudit(Request $request)
    {
        $query = Audit::query();
    
        // Apply filters
        if ($request->filled('name')) {
            // Filter by user name
            $query->whereHas('user', function ($userQuery) use ($request) {
                $userQuery->where('name', 'like', '%' . $request->name . '%');
            });
        }
    
        if ($request->filled('role')) {
            // Filter by role ID (restricted to admin and staff only)
            $query->whereHas('user', function ($userQuery) use ($request) {
                $userQuery->whereIn('role_id', [1, 2]) // Restrict to admin and staff
                          ->where('role_id', $request->role);
            });
        } else {
            // Default restriction to admin and staff roles
            $query->whereHas('user', function ($userQuery) {
                $userQuery->whereIn('role_id', [1, 2]);
            });
        }
    
        if ($request->filled('date')) {
            // Filter by specific date
            $query->whereDate('created_at', $request->date);
        }
    
        // Sort by latest (descending order)
        $query->orderBy('created_at', 'desc');
    
        // Retrieve audits with associated user and role data, paginated by 10
        $audits = $query->with(['user.role'])->paginate(10)->appends($request->all()); // Ensure filters are appended
    
        return view('admins.adminaudit', compact('audits'));
    }
    
}
