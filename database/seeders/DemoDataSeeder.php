<?php

namespace Database\Seeders;

use App\Models\CreativityType;
use App\Models\Enrollment;
use App\Models\MasterClass;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $architecture = CreativityType::where('title', 'Архитектурное моделирование')->firstOrFail();
        $cooking = CreativityType::where('title', 'Кулинария')->firstOrFail();
        $wood = CreativityType::where('title', 'Резьба по дереву')->firstOrFail();

        $teacher = User::updateOrCreate(
            ['email' => 'teacher@example.com'],
            [
                'full_name' => 'Иванова Ольга Ивановна',
                'password' => Hash::make('password'),
                'phone' => '+79990000001',
                'role' => 'teacher',
                'photo' => 'img/driver-page.png',
            ],
        );

        $woodTeacher = User::updateOrCreate(
            ['email' => 'wood.teacher@example.com'],
            [
                'full_name' => 'Сидорова Елена Викторовна',
                'password' => Hash::make('password'),
                'phone' => '+79990000002',
                'role' => 'teacher',
                'photo' => 'img/driver2.png',
            ],
        );

        $user = User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'full_name' => 'Смирнова Анна Игоревна',
                'password' => Hash::make('password'),
                'phone' => '+79990000003',
                'role' => 'visitor',
                'photo' => null,
            ],
        );

        $secondVisitor = User::updateOrCreate(
            ['email' => 'petrov.visitor@example.com'],
            [
                'full_name' => 'Петров Сергей Алексеевич',
                'password' => Hash::make('password'),
                'phone' => '+79990000004',
                'role' => 'visitor',
                'photo' => null,
            ],
        );

        $masterClasses = [
            'architecture' => MasterClass::updateOrCreate(
                [
                    'user_id' => $teacher->id,
                    'date' => '2026-05-03',
                    'time_slot' => '09:00',
                ],
                [
                    'creativity_type_id' => $architecture->id,
                    'title' => 'Моделирование зданий и сооружений',
                    'description' => 'Практика по сборке макетов зданий, проектированию стен, крыш и архитектурных элементов.',
                    'max_people' => 6,
                    'price' => 1100,
                ],
            ),
            'chocolate' => MasterClass::updateOrCreate(
                [
                    'user_id' => $teacher->id,
                    'date' => '2026-05-04',
                    'time_slot' => '11:00',
                ],
                [
                    'creativity_type_id' => $cooking->id,
                    'title' => 'Шоколадные поделки',
                    'description' => <<<'TEXT'
Шоколадные фонтаны, фруктовые пальмы, приготовление шоколадных конфет, мороженого и других сладостей.
Вы готовите только из проверенных компонентов, делаете яства с любовью, что, несомненно, отражается на их вкусе.
Мы научим вас делать любой праздник оригинальнее и вкуснее!
TEXT,
                    'max_people' => 4,
                    'price' => 1250,
                ],
            ),
            'steaks' => MasterClass::updateOrCreate(
                [
                    'user_id' => $teacher->id,
                    'date' => '2026-05-06',
                    'time_slot' => '13:00',
                ],
                [
                    'creativity_type_id' => $cooking->id,
                    'title' => 'Приготовление стейков',
                    'description' => <<<'TEXT'
Мы все любим стейки, но не у каждого из нас получается их правильно приготовить. На этом мастер-классе мы расскажем вам всё о стейках: как выбрать мясо, какую часть использовать для того или иного вида стейка, какие степени прожарки бывают. Мы приготовим гарнир и идеальный соус. Теперь вы сможете порадовать своих гостей и себя самого идеальными стейками!
TEXT,
                    'max_people' => 6,
                    'price' => 1500,
                ],
            ),
            'geometry_wood' => MasterClass::updateOrCreate(
                [
                    'user_id' => $woodTeacher->id,
                    'date' => '2026-05-05',
                    'time_slot' => '09:00',
                ],
                [
                    'creativity_type_id' => $wood->id,
                    'title' => 'Геометрическая резьба по дереву',
                    'description' => <<<'TEXT'
Данный мастер-класс для начинающих, знакомит с геометрической резьбой, с самых основных элементов. Несложными движениями и творческим комбинированием создаются удивительные узоры на дереве.
TEXT,
                    'max_people' => 5,
                    'price' => 1000,
                ],
            ),
            'wood_toys' => MasterClass::updateOrCreate(
                [
                    'user_id' => $woodTeacher->id,
                    'date' => '2026-05-07',
                    'time_slot' => '15:00',
                ],
                [
                    'creativity_type_id' => $wood->id,
                    'title' => 'Деревянные игрушки',
                    'description' => <<<'TEXT'
На мастер-классе вы научитесь вырезать фигурки животных из качественных пород дерева с помощью профессиональных инструментов.
Обработка фигурок натуральными составами обеспечит прочность, долговечность и экологичность созданных игрушек.
TEXT,
                    'max_people' => 5,
                    'price' => 1300,
                ],
            ),
        ];

        foreach ([[$user, $masterClasses['chocolate']], [$secondVisitor, $masterClasses['geometry_wood']]] as [$visitor, $masterClass]) {
            Enrollment::updateOrCreate([
                'user_id' => $visitor->id,
                'master_class_id' => $masterClass->id,
            ]);
        }
    }
}
