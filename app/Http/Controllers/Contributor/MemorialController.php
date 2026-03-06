<?php

namespace App\Http\Controllers\Contributor;

use App\Http\Controllers\Controller;
use App\Models\Memorial;
use App\Models\Ward;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MemorialController extends Controller
{
    /**
     * Show contributor's own memorials.
     */
    public function index(): View
    {
        $memorials = auth()->user()->memorials()
            ->with('ward.union.thana.district', 'photos')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('contributor.memorials.index', compact('memorials'));
    }

    /**
     * Show create memorial form.
     */
    public function create(): View
    {
        return view('contributor.memorials.create');
    }

    /**
     * Store a new memorial.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date',
            'date_of_death' => 'nullable|date',
            'bio' => 'nullable|string',
            'ward_id' => 'required|exists:wards,id',
            'photos.*' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
        ]);

        $memorial = auth()->user()->memorials()->create([
            'name' => $validated['name'],
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'date_of_death' => $validated['date_of_death'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'ward_id' => $validated['ward_id'],
            'status' => 'pending',
        ]);

        // Handle photo uploads
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $photo) {
                $path = $photo->store('memorials/' . $memorial->id, 'public');
                $memorial->photos()->create([
                    'photo_path' => $path,
                    'display_order' => $index,
                ]);
            }
        }

        return redirect()->route('contributor.memorials.show', $memorial)
            ->with('success', 'Memorial created. Awaiting admin approval.');
    }

    /**
     * Show a specific memorial.
     */
    public function show(Memorial $memorial): View
    {
        // Log current user and memorial for debugging
        \Log::info('Contributor Memorial Show Attempt', [
            'user_id' => auth()->id(),
            'user_role' => auth()->user()->role,
            'memorial_id' => $memorial->id,
            'memorial_user_id' => $memorial->user_id,
        ]);
        
        try {
            $this->authorize('view', $memorial);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            \Log::error('Authorization failed in MemorialController::show', [
                'user_id' => auth()->id(),
                'memorial_id' => $memorial->id,
                'message' => $e->getMessage(),
            ]);
            throw $e;
        }
        
        $memorial->load(['ward.union.thana.district', 'photos']);
        
        return view('contributor.memorials.show', compact('memorial'));
    }

    /**
     * Show edit form (only for pending/rejected).
     */
    public function edit(Memorial $memorial): View
    {
        $this->authorize('update', $memorial);

        if ($memorial->status === 'approved') {
            abort(403, 'Cannot edit approved memorials.');
        }

        $memorial->load('ward.union.thana.district');
        return view('contributor.memorials.edit', compact('memorial'));
    }

    /**
     * Update a memorial.
     */
    public function update(Memorial $memorial, Request $request): RedirectResponse
    {
        $this->authorize('update', $memorial);

        if ($memorial->status === 'approved') {
            abort(403, 'Cannot edit approved memorials.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date',
            'date_of_death' => 'nullable|date',
            'bio' => 'nullable|string',
            'ward_id' => 'required|exists:wards,id',
            'photos.*' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
        ]);

        $memorial->update([
            'name' => $validated['name'],
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'date_of_death' => $validated['date_of_death'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'ward_id' => $validated['ward_id'],
            'status' => 'pending',
            'admin_notes' => null,
            'updated_by_admin_id' => null,
        ]);

        // Handle photo uploads
        if ($request->hasFile('photos')) {
            $currentCount = $memorial->photos()->count();
            foreach ($request->file('photos') as $index => $photo) {
                $path = $photo->store('memorials/' . $memorial->id, 'public');
                $memorial->photos()->create([
                    'photo_path' => $path,
                    'display_order' => $currentCount + $index,
                ]);
            }
        }

        return redirect()->route('contributor.memorials.show', $memorial)
            ->with('success', 'Memorial updated and resubmitted for approval.');
    }

    /**
     * Delete a memorial (contribution can only delete own).
     */
    public function destroy(Memorial $memorial): RedirectResponse
    {
        $this->authorize('delete', $memorial);

        $memorial->delete();

        return redirect()->route('contributor.memorials.index')
            ->with('success', 'Memorial deleted.');
    }

    /**
     * Delete a photo from  memorial.
     */
    public function deletePhoto($photoId)
    {
        $photo = \App\Models\MemorialPhoto::findOrFail($photoId);
        $memorial = $photo->memorial;
        
        $this->authorize('update', $memorial);

        \Storage::disk('public')->delete($photo->photo_path);
        $photo->delete();

        return response()->json(['success' => true]);
    }
}
