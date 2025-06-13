<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\VisitorService;

class VisitorController extends Controller
{
    protected $visitorService;

    public function __construct(VisitorService $visitorService)
    {
        $this->visitorService = $visitorService;
    }

    public function index()
    {
        try {
            $visitors = $this->visitorService->getVisitors();
            return view('admin.visitor.index', compact('visitors'));
        } catch (\Exception $e) {
            Log::error('Error fetching visitors: ' . $e->getMessage());
            return redirect()->back()->withErrors('Failed to load visitors.');
        }
    }

    public function fetchData()
    {
        try {
            $visitors = $this->visitorService->getVisitors();
            return response()->json(['visitors' => $visitors]);
        } catch (\Exception $e) {
            Log::error('Error fetching visitors data: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch visitors data'], 500);
        }
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
