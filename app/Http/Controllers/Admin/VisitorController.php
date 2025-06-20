<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\VisitorService;
use App\DataTables\VisitorDataTable;

class VisitorController extends Controller
{
    protected $visitorService;
    protected $visitorDataTable;

    public function __construct(VisitorService $visitorService, VisitorDataTable $visitorDataTable)
    {
        $this->visitorService = $visitorService;
        $this->visitorDataTable = $visitorDataTable;
    }

    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                return $this->visitorDataTable->ajax();
            }
            return $this->visitorDataTable->render('admin.visitor.index');
        } catch (\Exception $e) {
            Log::error('Error fetching visitors: ' . $e->getMessage());
            return redirect()->back()->withErrors('Failed to load visitors.');
        }
    }

    public function fetchData()
    {
        // No longer needed, handled by VisitorDataTable ajax method
        abort(404);
    }

    public function destroy($id)
    {
        try {
            $this->visitorService->deleteVisitor($id);
            return response()->json(['message' => 'Visitor deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Error deleting visitor: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete visitor'], 500);
        }
    }

    public function toggleIsBlocked($id)
    {
        try {
            $visitor = $this->visitorService->toggleIsBlocked($id);
            return response()->json(['visitor' => $visitor]);
        } catch (\Exception $e) {
            Log::error('Error toggling is_blocked: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to toggle is_blocked'], 500);
        }
    }
}
