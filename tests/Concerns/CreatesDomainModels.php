<?php

namespace Tests\Concerns;

use App\Models\CreativityType;
use App\Models\MasterClass;
use App\Models\User;

trait CreatesDomainModels
{
    protected function createCreativityType(array $attributes = []): CreativityType
    {
        return CreativityType::create(array_merge([
            'title' => 'Кулинария',
            'description' => 'Практические занятия',
            'image' => null,
        ], $attributes));
    }

    protected function createTeacher(array $attributes = []): User
    {
        return User::factory()->create(array_merge([
            'role' => 'teacher',
        ], $attributes));
    }

    protected function createVisitor(array $attributes = []): User
    {
        return User::factory()->create(array_merge([
            'role' => 'visitor',
        ], $attributes));
    }

    protected function createMasterClass(User $teacher, CreativityType $type, array $attributes = []): MasterClass
    {
        return MasterClass::create(array_merge([
            'user_id' => $teacher->id,
            'creativity_type_id' => $type->id,
            'title' => 'Шоколадный мастер-класс',
            'description' => 'Описание занятия',
            'date' => '2026-06-01',
            'time_slot' => '09:00',
            'max_people' => 3,
            'price' => 1200,
        ], $attributes));
    }
}
