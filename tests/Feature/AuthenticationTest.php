<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesDomainModels;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use CreatesDomainModels;
    use RefreshDatabase;

    public function test_guest_can_open_auth_pages(): void
                {$this->get(route('login'))->assertOk();
        $this->get(route('register'))->assertOk();
    }

    public function test_visitor_can_register_and_is_redirected_home(): void
    {
        $response = $this->post(route('register.store'), [
            'full_name' => 'Анна Смирнова',
            'email' => 'anna@example.com',
            'password' => 'password',
            'phone' => '+79991234567',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'anna@example.com',
            'role' => 'visitor',
        ]);
    }

    public function test_teacher_is_redirected_to_cabinet_after_login(): void
    {
        $teacher = $this->createTeacher([
            'email' => 'teacher@example.com',
            'password' => 'password',
        ]);

        $response = $this->post(route('login.store'), [
            'email' => $teacher->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('cabinet.index'));
        $this->assertAuthenticatedAs($teacher);
    }

    public function test_invalid_login_returns_validation_error(): void
    {
        $response = $this->from(route('login'))->post(route('login.store'), [
            'email' => 'missing@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirect(route('home'));
        $this->assertGuest();
    }
}
