<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesDomainModels;
use Tests\TestCase;

class MasterAreaTest extends TestCase
{
    use CreatesDomainModels;
    use RefreshDatabase;

    public function test_visitor_is_redirected_from_master_cabinet(): void
    {
        $visitor = $this->createVisitor();

        $this->actingAs($visitor)
            ->get(route('cabinet.index'))
            ->assertRedirect(route('home'));
    }

    public function test_teacher_can_view_cabinet_and_create_master_class(): void
    {
        $type = $this->createCreativityType();
        $teacher = $this->createTeacher();

        $this->actingAs($teacher)
            ->get(route('cabinet.index'))
            ->assertOk();

        $this->actingAs($teacher)
            ->post(route('master-classes.store'), [
                'creativity_type_id' => $type->id,
                'title' => 'Новый мастер-класс',
                'description' => 'Описание',
                'date' => '2026-06-02',
                'time_slot' => '11:00:00',
                'max_people' => 5,
                'price' => 1500,
            ])
            ->assertRedirect(route('cabinet.index'));

        $this->assertDatabaseHas('master_classes', [
            'user_id' => $teacher->id,
            'title' => 'Новый мастер-класс',
            'time_slot' => '11:00',
        ]);
    }

    public function test_teacher_cannot_reuse_same_slot_on_same_date(): void
    {
        $type = $this->createCreativityType();
        $teacher = $this->createTeacher();
        $this->createMasterClass($teacher, $type);

        $response = $this->actingAs($teacher)
            ->from(route('master-classes.create'))
            ->post(route('master-classes.store'), [
                'creativity_type_id' => $type->id,
                'title' => 'Дубликат',
                'description' => 'Описание',
                'date' => '2026-06-01',
                'time_slot' => '09:00',
                'max_people' => 5,
                'price' => 1500,
            ]);

        $response->assertRedirect(route('master-classes.create'));
        $response->assertSessionHasErrors('time_slot');
    }

    public function test_teacher_can_update_own_master_class_but_not_foreign_one(): void
    {
        $type = $this->createCreativityType();
        $owner = $this->createTeacher();
        $otherTeacher = $this->createTeacher();
        $masterClass = $this->createMasterClass($owner, $type);

        $this->actingAs($owner)
            ->patch(route('master-classes.update', $masterClass), [
                'description' => 'Новое описание',
                'price' => 1800,
            ])
            ->assertRedirect(route('cabinet.index'));

        $this->assertDatabaseHas('master_classes', [
            'id' => $masterClass->id,
            'description' => 'Новое описание',
            'price' => 1800,
        ]);

        $this->actingAs($otherTeacher)
            ->patch(route('master-classes.update', $masterClass), [
                'description' => 'Чужое обновление',
                'price' => 2000,
            ])
            ->assertForbidden();
    }
}
