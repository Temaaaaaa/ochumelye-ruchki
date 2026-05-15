<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\MasterClass;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    public function create(Request $request, MasterClass $masterClass): View|RedirectResponse
    {
        if ($response = $this->validateEnrollmentAccess($request, $masterClass)) {
            return $response;
        }

        $masterClass->load(['creativityType', 'teacher']);

        return view('enrollments.confirm', compact('masterClass'));
    }

    public function store(Request $request, MasterClass $masterClass): RedirectResponse
    {
        if ($response = $this->validateEnrollmentAccess($request, $masterClass)) {
            return $response;
        }

        try {
            DB::transaction(function () use ($request, $masterClass): void {
                $lockedMasterClass = MasterClass::query()
                    ->whereKey($masterClass->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($lockedMasterClass->enrollments()->count() >= $lockedMasterClass->max_people) {
                    throw new \RuntimeException('full');
                }

                Enrollment::create([
                    'user_id' => $request->user()->id,
                    'master_class_id' => $lockedMasterClass->id,
                ]);
            });
        } catch (QueryException) {
            return redirect()
                ->route('types.show', $masterClass->creativity_type_id)
                ->with('error', 'Вы уже записаны на этот мастер-класс.');
        } catch (\RuntimeException $exception) {
            if ($exception->getMessage() === 'full') {
                return redirect()
                    ->route('types.show', $masterClass->creativity_type_id)
                    ->with('error', 'Свободных мест на этот мастер-класс уже нет.');
            }

            throw $exception;
        }

        return redirect()
            ->route('types.show', $masterClass->creativity_type_id)
            ->with('success', 'Вы успешно записались на мастер-класс.');
    }

    public function cancel(Request $request, MasterClass $masterClass): RedirectResponse
    {
        if (! $request->user()->isVisitor()) {
            abort(403);
        }

        return redirect()
            ->route('types.show', $masterClass->creativity_type_id)
            ->with('success', 'Запись отменена.');
    }

    private function validateEnrollmentAccess(Request $request, MasterClass $masterClass): ?RedirectResponse
    {
        $user = $request->user();

        if (! $user->isVisitor()) {
            abort(403);
        }

        if ($masterClass->enrollments()->count() >= $masterClass->max_people) {
            return redirect()
                ->route('types.show', $masterClass->creativity_type_id)
                ->with('error', 'Свободных мест на этот мастер-класс уже нет.');
        }

        $alreadyEnrolled = $user->enrollments()
            ->where('master_class_id', $masterClass->id)
            ->exists();

        if ($alreadyEnrolled) {
            return redirect()
                ->route('types.show', $masterClass->creativity_type_id)
                ->with('error', 'Вы уже записаны на этот мастер-класс.');
        }

        $alreadyBooked = $user->enrollments()
            ->whereHas('masterClass', function (Builder $query) use ($masterClass): void {
                $query
                    ->whereDate('date', $masterClass->date)
                    ->where('time_slot', $masterClass->time_slot);
            })
            ->exists();

        if ($alreadyBooked) {
            return redirect()
                ->route('types.show', $masterClass->creativity_type_id)
                ->with('error', 'Вы уже записаны на мастер-класс в эту дату и это время.');
        }

        return null;
    }
}
