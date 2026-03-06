<?php

namespace App\Http\Controllers;

use App\Models\Memorial;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MemorialController extends Controller
{
    /**
     * Show public memorial listing with filters.
     */
    public function index(Request $request): View
    {
        $query = Memorial::where('status', 'approved')
            ->with('ward.union.thana.district', 'photos')
            ->with('user');

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by district
        if ($request->filled('district_id')) {
            $query->whereHas('ward.union.thana.district', function ($q) use ($request) {
                $q->where('id', $request->district_id);
            });
        }

        // Filter by thana
        if ($request->filled('thana_id')) {
            $query->whereHas('ward.union.thana', function ($q) use ($request) {
                $q->where('id', $request->thana_id);
            });
        }

        // Filter by union
        if ($request->filled('union_id')) {
            $query->whereHas('ward.union', function ($q) use ($request) {
                $q->where('id', $request->union_id);
            });
        }

        // Filter by ward
        if ($request->filled('ward_id')) {
            $query->where('ward_id', $request->ward_id);
        }

        $memorials = $query->orderBy('created_at', 'desc')->paginate(12);
        $districts = District::with('thanas')->get();

        return view('memorials.index', compact('memorials', 'districts'));
    }

    /**
     * Show a single memorial.
     */
    public function show(Memorial $memorial): View
    {
        if ($memorial->status !== 'approved') {
            abort(404);
        }

        $memorial->load(['user', 'ward.union.thana.district', 'photos']);
        return view('memorials.show', compact('memorial'));
    }
}
