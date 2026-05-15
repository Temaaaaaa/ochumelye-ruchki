<?php

namespace App\Http\Controllers;

use App\Models\CreativityType;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        $types = CreativityType::query()
            ->withCount('masterClasses')
            ->orderBy('title')
            ->get();

        $enrollments = collect();

        if ($request->user()?->isVisitor()) {
            $enrollments = $request->user()
                ->enrollments()
                ->with(['masterClass.creativityType', 'masterClass.teacher'])
                ->latest()
                ->get();
        }

        return view('home', compact('types', 'enrollments'));
    }
}
