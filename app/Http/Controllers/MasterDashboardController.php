<?php

namespace App\Http\Controllers;

use App\Models\MasterClass;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class MasterDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $masterClasses = $request->user()
            ->masterClasses()
            ->with(['creativityType', 'enrollments.user'])
            ->withCount('enrollments')
            ->orderBy('date')
            ->orderBy('time_slot')
            ->get();

        return view('cabinet.index', [
            'master' => $request->user(),
            'masterClasses' => $masterClasses,
        ]);
    }

    public function show(Request $request, MasterClass $masterClass): View
    {
        abort_unless($masterClass->user_id === $request->user()->id, 403);

        $masterClass->load(['creativityType', 'enrollments.user', 'teacher']);

        return view('cabinet.show', compact('masterClass'));
    }
}
