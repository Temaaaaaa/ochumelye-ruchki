<?php

namespace App\Http\Controllers;

use App\Models\CreativityType;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CreativityTypeController extends Controller
{
    public function show(Request $request, CreativityType $creativityType): View
    {
        $masterClasses = $creativityType->masterClasses()
            ->with(['teacher', 'enrollments'])
            ->withCount('enrollments')
            ->orderBy('date')
            ->orderBy('time_slot')
            ->get();

        $userEnrollmentIds = [];
        $userBookedSlots = [];

        if ($request->user()?->isVisitor()) {
            $enrollments = $request->user()
                ->enrollments()
                ->with('masterClass:id,date,time_slot')
                ->get();

            $userEnrollmentIds = $enrollments->pluck('master_class_id')->flip()->all();
            $userBookedSlots = $enrollments
                ->filter(fn ($enrollment) => $enrollment->masterClass !== null)
                ->mapWithKeys(fn ($enrollment) => [
                    $enrollment->masterClass->date->format('Y-m-d').'|'.$enrollment->masterClass->time_slot => true,
                ])
                ->all();
        }

        return view('types.show', compact(
            'creativityType',
            'masterClasses',
            'userEnrollmentIds',
            'userBookedSlots',
        ));
    }
}
