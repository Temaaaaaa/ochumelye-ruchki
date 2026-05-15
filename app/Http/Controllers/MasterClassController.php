<?php

namespace App\Http\Controllers;

use App\Models\CreativityType;
use App\Models\MasterClass;
use App\Support\MasterClassSchedule;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MasterClassController extends Controller
{
    public function create(Request $request): View
    {
        $creativityTypes = CreativityType::query()->orderBy('title')->get();
        $occupiedSlotsByDate = $this->occupiedSlotsByDate($request->user()->id);

        return view('master-classes.create', [
            'creativityTypes' => $creativityTypes,
            'timeSlots' => MasterClassSchedule::all(),
            'occupiedSlotsByDate' => $occupiedSlotsByDate,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'time_slot' => MasterClassSchedule::normalize($request->input('time_slot')),
        ]);

        $validated = $request->validate([
            'creativity_type_id' => ['required', 'exists:creativity_types,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'date' => ['required', 'date'],
            'time_slot' => [
                'required',
                Rule::in(array_keys(MasterClassSchedule::all())),
                Rule::unique('master_classes')->where(
                    fn (Builder $query): Builder => $query
                        ->where('user_id', $request->user()->id)
                        ->whereDate('date', $request->input('date'))
                ),
            ],
            'max_people' => ['required', 'integer', 'min:1'],
            'price' => ['required', 'numeric', 'min:0'],
        ], [
            'time_slot.unique' => 'Этот временной слот уже занят.',
        ]);

        MasterClass::create([
            'user_id' => $request->user()->id,
            'creativity_type_id' => $validated['creativity_type_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'date' => $validated['date'],
            'time_slot' => $validated['time_slot'],
            'max_people' => $validated['max_people'],
            'price' => $validated['price'],
        ]);

        return redirect()
            ->route('cabinet.index')
            ->with('success', 'Мастер-класс добавлен в расписание.');
    }

    public function edit(Request $request, MasterClass $masterClass): View
    {
        abort_unless($masterClass->user_id === $request->user()->id, 403);

        $masterClass->load(['creativityType', 'teacher']);

        return view('master-classes.edit', compact('masterClass'));
    }

    public function update(Request $request, MasterClass $masterClass): RedirectResponse
    {
        abort_unless($masterClass->user_id === $request->user()->id, 403);

        $validated = $request->validate([
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
        ]);

        $masterClass->update($validated);

        return redirect()
            ->route('cabinet.index')
            ->with('success', 'Мастер-класс обновлен.');
    }

    private function occupiedSlotsByDate(int $userId): array
    {
        return MasterClass::query()
            ->where('user_id', $userId)
            ->get(['date', 'time_slot'])
            ->groupBy(fn (MasterClass $masterClass) => $masterClass->date->format('Y-m-d'))
            ->map(fn ($items) => $items
                ->pluck('time_slot')
                ->map(fn (?string $slot) => MasterClassSchedule::normalize($slot))
                ->values()
                ->all())
            ->all();
    }
}
