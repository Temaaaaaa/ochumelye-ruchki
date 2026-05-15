<?php

namespace Tests\Unit;

use App\Models\Enrollment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesDomainModels;
use Tests\TestCase;

class ModelBehaviorTest extends TestCase
{
    use CreatesDomainModels;
    use RefreshDatabase;

    public function test_user_role_helpers_reflect_role(): void
    {
        $teacher = $this->createTeacher();
        $visitor = $this->createVisitor();

        $this->assertTrue($teacher->isTeacher());
        $this->assertTrue($teacher->isMaster());
        $this->assertFalse($teacher->isVisitor());
        $this->assertTrue($visitor->isVisitor());
    }

    public function test_master_class_computed_attributes_reflect_capacity(): void
    {
        $type = $this->createCreativityType();
        $teacher = $this->createTeacher();
        $visitor = $this->createVisitor();
        $masterClass = $this->createMasterClass($teacher, $type, ['max_people' => 2]);

        Enrollment::create([
            'user_id' => $visitor->id,
            'master_class_id' => $masterClass->id,
        ]);

        $masterClass->loadCount('enrollments');

        $this->assertSame(1, $masterClass->seats_left);
        $this->assertFalse($masterClass->is_full);
        $this->assertSame('09:00 - 11:00', $masterClass->formatted_time_slot);
    }
}
