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
        // Get the filters from the request
        $action = $request->input('action');
        $user = $request->input('user');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Query for audits
        $query = Audit::query();

        // Apply action type filter
        if ($action) {
            $query->where(function ($q) use ($action) {
                switch ($action) {
                    case 'login':
                        $q->where('action', 'like', '%Logged In%');
                        break;
                    case 'logout':
                        $q->where('action', 'like', '%logout%');
                        break;
                    case 'create':
                        $q->where('action', 'like', '%add%')
                            ->orWhere('action', 'like', '%create%');
                        break;
                    case 'update':
                        $q->where('action', 'like', '%update%')
                            ->orWhere('action', 'like', '%edit%');
                        break;
                    case 'delete':
                        $q->where('action', 'like', '%delete%');
                        break;
                }
            });
        }

        // Apply user filter
        if ($user) {
            $query->where('user_id', $user);
        } else {
            // Default restriction to admin and staff roles
            $query->whereHas('user', function ($userQuery) {
                $userQuery->whereIn('role_id', [1, 2]); // Admin and staff
            });
        }

        // Apply date range filter (adjust date format to include full date and time)
        if ($startDate) {
            $startDate = Carbon::parse($startDate)->startOfDay();
            $query->where('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $endDate = Carbon::parse($endDate)->endOfDay();
            $query->where('created_at', '<=', $endDate);
        }

        // Sort by latest (descending order)
        $query->orderBy('created_at', 'desc');

        // Fetch paginated results
        $audits = $query->with(['user.role'])->paginate(10);

        // Return the view with filters and pagination
        return view('admins.adminaudit', compact('audits', 'action', 'user', 'startDate', 'endDate'));
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
