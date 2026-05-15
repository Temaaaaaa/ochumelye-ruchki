<?php

namespace Tests\Feature;

use App\Models\Enrollment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesDomainModels;
use Tests\TestCase;

class BrowsingTest extends TestCase
{
    use CreatesDomainModels;
    use RefreshDatabase;

    public function test_home_page_lists_types_and_visitor_enrollments(): void
    {
        $type = $this->createCreativityType();
        $teacher = $this->createTeacher();
        $visitor = $this->createVisitor();
        $masterClass = $this->createMasterClass($teacher, $type);

        Enrollment::create([
            'user_id' => $visitor->id,
            'master_class_id' => $masterClass->id,
        ]);

        $response = $this->actingAs($visitor)->get(route('home'));

        $response->assertOk();
        $response->assertViewHas('types', fn ($types) => $types->contains($type));
        $response->assertViewHas('enrollments', fn ($enrollments) => $enrollments->count() === 1);
    }

    public function test_type_page_prepares_booking_context_for_visitor(): void
    {
        $type = $this->createCreativityType();
        $teacher = $this->createTeacher();
        $visitor = $this->createVisitor();
        $bookedClass = $this->createMasterClass($teacher, $type);

        Enrollment::create([
            'user_id' => $visitor->id,
            'master_class_id' => $bookedClass->id,
        ]);

        $response = $this->actingAs($visitor)->get(route('types.show', $type));

        $response->assertOk();
        $response->assertViewHas('userEnrollmentIds', fn ($ids) => isset($ids[$bookedClass->id]));
        $response->assertViewHas('userBookedSlots', fn ($slots) => isset($slots['2026-06-01|09:00']));
    }
}
