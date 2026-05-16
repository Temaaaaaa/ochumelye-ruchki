<?php

namespace Tests\Feature;

use App\Models\Enrollment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesDomainModels;
use Tests\TestCase;

class EnrollmentTest extends TestCase
{
    use CreatesDomainModels;
    use RefreshDatabase;

    public function test_visitor_can_open_confirmation_and_book_master_class(): void
    {
        $type = $this->createCreativityType();
        $teacher = $this->createTeacher();
        $visitor = $this->createVisitor();
        $masterClass = $this->createMasterClass($teacher, $type);

        $this->actingAs($visitor)
            ->get(route('bookings.confirm', $masterClass))
            ->assertOk();

        $this->actingAs($visitor)
            ->post(route('bookings.store', $masterClass))
            ->assertRedirect(route('types.show', $type));

        $this->assertDatabaseHas('enrollments', [
            'user_id' => $visitor->id,
            'master_class_id' => $masterClass->id,
        ]);
    }

    public function test_visitor_cannot_book_same_master_class_twice(): void
    {
        $type = $this->createCreativityType();
        $teacher = $this->createTeacher();
        $visitor = $this->createVisitor();
        $masterClass = $this->createMasterClass($teacher, $type);

        Enrollment::create([
            'user_id' => $visitor->id,
            'master_class_id' => $masterClass->id,
        ]);

        $this->actingAs($visitor)
            ->post(route('bookings.store', $masterClass))
            ->assertRedirect(route('types.show', $type))
            ->assertSessionHas('error');

        $this->assertDatabaseCount('enrollments', 1);
    }

    public function test_visitor_cannot_book_two_classes_in_same_slot(): void
    {
        $type = $this->createCreativityType();
        $teacher = $this->createTeacher();
        $otherTeacher = $this->createTeacher();
        $visitor = $this->createVisitor();
        $firstClass = $this->createMasterClass($teacher, $type);
        $secondClass = $this->createMasterClass($otherTeacher, $type, [
            'date' => '2026-06-01',
            'time_slot' => '09:00',
            'title' => 'Второй мастер-класс',
        ]);

        Enrollment::create([
            'user_id' => $visitor->id,
            'master_class_id' => $firstClass->id,
        ]);

        $this->actingAs($visitor)
            ->post(route('bookings.store', $secondClass))
            ->assertRedirect(route('types.show', $type))
            ->assertSessionHas('error');

        $this->assertDatabaseCount('enrollments', 2);
    }

    public function test_full_master_class_cannot_be_booked(): void
    {
        $type = $this->createCreativityType();
        $teacher = $this->createTeacher();
        $firstVisitor = $this->createVisitor();
        $secondVisitor = $this->createVisitor();
        $masterClass = $this->createMasterClass($teacher, $type, ['max_people' => 1]);

        Enrollment::create([
            'user_id' => $firstVisitor->id,
            'master_class_id' => $masterClass->id,
        ]);

        $this->actingAs($secondVisitor)
            ->post(route('bookings.store', $masterClass))
            ->assertRedirect(route('types.show', $type))
            ->assertSessionHas('error');

        $this->assertDatabaseCount('enrollments', 1);
    }

    public function test_teacher_cannot_book_master_class(): void
    {
        $type = $this->createCreativityType();
        $teacher = $this->createTeacher();
        $masterClass = $this->createMasterClass($teacher, $type);

        $this->actingAs($teacher)
            ->post(route('bookings.store', $masterClass))
            ->assertForbidden();
    }
}
