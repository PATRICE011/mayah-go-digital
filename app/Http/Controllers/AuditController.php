<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Audit;
use Carbon\Carbon;
use App\Models\User;

class AuditController extends Controller
{
    //
    public function adminaudit(Request $request)
    {
        $query = Audit::query();

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Apply action type filter
        if ($request->filled('action')) {
            $action = $request->action;
            $query->where(function ($q) use ($action) {
                if ($action === 'login') {
                    $q->where('action', 'like', '%login%');
                } elseif ($action === 'logout') {
                    $q->where('action', 'like', '%logout%');
                } elseif ($action === 'create') {
                    $q->where('action', 'like', '%add%')
                        ->orWhere('action', 'like', '%create%');
                } elseif ($action === 'update') {
                    $q->where('action', 'like', '%update%')
                        ->orWhere('action', 'like', '%edit%');
                } elseif ($action === 'delete') {
                    $q->where('action', 'like', '%delete%');
                }
            });
        }

        // Apply user filter
        if ($request->filled('user')) {
            $query->where('user_id', $request->user);
        } else {
            // Default restriction to admin and staff roles
            $query->whereHas('user', function ($userQuery) {
                $userQuery->whereIn('role_id', [1, 2]); // Restrict to admin and staff
            });
        }

        // Apply date range filter
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Sort by latest (descending order)
        $query->orderBy('created_at', 'desc');

        // Check if it's an AJAX request
        if ($request->ajax() && !$request->wantsJson()) {
            $audits = $query->with(['user.role'])->paginate(10)->appends($request->all());
            return view('admins.partials.audit_table', compact('audits'))->render();
        }

        // Retrieve audits with associated user and role data, paginated by 10
        $audits = $query->with(['user.role'])->paginate(10)->appends($request->all());

        return view('admins.adminaudit', compact('audits'));
    }

    /**
     * Get audit details for the modal
     */
    public function getAuditDetails($id)
    {
        try {
            $audit = Audit::with('user.role')->findOrFail($id);

            // Decode the stored JSON values
            $oldValues = json_decode($audit->old_values, true) ?? [];
            $changes = json_decode($audit->changes, true) ?? [];

            // Format dates for better readability
            $formattedDate = Carbon::parse($audit->created_at)->format('h:i A, d-m-Y');

            // Get model information
            $modelInfo = '';
            if ($audit->model_type && $audit->model_id) {
                $modelParts = explode('\\', $audit->model_type);
                $modelName = end($modelParts);
                $modelInfo = $modelName . ' #' . $audit->model_id;
            }

            return response()->json([
                'success' => true,
                'audit' => [
                    'id' => $audit->id,
                    'action' => $audit->action,
                    'created_at' => $formattedDate,
                    'model_info' => $modelInfo
                ],
                'user' => $audit->user ? [
                    'name' => $audit->user->name,
                    'role' => $audit->user->role->name ?? 'N/A'
                ] : null,
                'old_values' => $oldValues,
                'changes' => $changes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve audit details.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getUsersList()
    {
        try {
            // Get only admin and staff users
            $users = User::whereIn('role_id', [1, 2])->select('id', 'name')->orderBy('name')->get();

            return response()->json([
                'success' => true,
                'users' => $users
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve users list.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
