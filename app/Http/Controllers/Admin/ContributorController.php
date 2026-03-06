<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ContributorController extends Controller
{
    /**
     * Show all contributors.
     */
    public function index(): View
    {
        $contributors = User::where('role', 'contributor')
            ->withCount('memorials')
            ->paginate(15);

        return view('admin.contributors.index', compact('contributors'));
    }

    /**
     * Show create contributor form.
     */
    public function create(): View
    {
        return view('admin.contributors.create');
    }

    /**
     * Store a new contributor.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
        ]);

        $password = \Str::random(16);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($password),
            'role' => 'contributor',
        ]);

        // TODO: Send email with credentials
        // Mail::send('emails.contributor-credentials', ['user' => $user, 'password' => $password], ...)

        return redirect()->route('admin.contributors.index')
            ->with('success', 'Contributor created. Send credentials to: ' . $validated['email']);
    }

    /**
     * Show contributor details.
     */
    public function show(User $contributor): View
    {
        $memorials = $contributor->memorials()->with('ward.union.thana.district')->paginate(10);
        return view('admin.contributors.show', compact('contributor', 'memorials'));
    }

    /**
     * Delete a contributor.
     */
    public function destroy(User $contributor): RedirectResponse
    {
        if ($contributor->isAdmin()) {
            return back()->with('error', 'Cannot delete admin users.');
        }

        $contributor->delete();

        return redirect()->route('admin.contributors.index')
            ->with('success', 'Contributor deleted.');
    }
}
