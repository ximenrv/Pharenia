<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VisitorAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Evita que @vite() falle cuando no hay manifest de build en testing.
        $this->withoutVite();
    }

    private static int $userCounter = 0;

    private function makeUser(string $role): User
    {
        self::$userCounter++;

        return User::create([
            'name' => 'Test ' . $role . ' ' . self::$userCounter,
            'email' => str_replace('_', '.', $role) . self::$userCounter . '@pharenia.test',
            'birthdate' => '1995-01-01',
            'role' => $role,
            'password' => bcrypt('secret123'),
        ]);
    }

    public function test_visitor_role_model_policies(): void
    {
        $visitor = $this->makeUser(User::ROLE_VISITOR);

        $this->assertTrue($visitor->isVisitor());
        $this->assertFalse($visitor->isAdmin());
        $this->assertTrue($visitor->hasGeneralAccess());
        $this->assertFalse($visitor->canAccessAdmin());
        $this->assertTrue($visitor->hasRole(User::ROLE_VISITOR));
        $this->assertFalse($visitor->hasRole(User::ROLE_ADMIN, User::ROLE_ADULT_TEA));
    }

    public function test_visitor_cannot_access_admin_area(): void
    {
        $visitor = $this->makeUser(User::ROLE_VISITOR);

        $this->actingAs($visitor)
            ->get(route('admin.visitors'))
            ->assertRedirect(route('home'));

        $this->actingAs($visitor)
            ->get(route('admin.dashboard'))
            ->assertRedirect(route('home'));

        $this->actingAs($visitor)
            ->get(route('admin.visitors.show', 1))
            ->assertRedirect(route('home'));
    }

    public function test_visitor_can_browse_general_content_like_adult_tea(): void
    {
        $visitor = $this->makeUser(User::ROLE_VISITOR);

        $this->actingAs($visitor)->get(route('activities.adultez'))->assertOk();
        $this->actingAs($visitor)->get(route('activities.youth'))->assertOk();
        $this->actingAs($visitor)->get(route('activities.child'))->assertOk();
        $this->actingAs($visitor)->get(route('games.youth.quizzsense'))->assertOk();
    }

    public function test_admin_can_manage_visitors_and_see_the_count(): void
    {
        $admin = $this->makeUser(User::ROLE_ADMIN);
        $visitorOne = $this->makeUser(User::ROLE_VISITOR);
        $visitorTwo = $this->makeUser(User::ROLE_VISITOR);

        // El panel admin debe incluir el contador dinámico de visitantes.
        $dashboard = $this->actingAs($admin)->get(route('admin.dashboard'));
        $dashboard->assertOk();
        $dashboard->assertSee('Visitantes');

        // La gestión de visitantes lista el CRUD completo.
        $this->actingAs($admin)
            ->get(route('admin.visitors'))
            ->assertOk()
            ->assertSee($visitorOne->email)
            ->assertSee($visitorTwo->email);

        $this->actingAs($admin)
            ->get(route('admin.visitors.show', $visitorOne->id))
            ->assertOk()
            ->assertSee($visitorOne->name);

        $this->actingAs($admin)
            ->get(route('admin.visitors.edit', $visitorTwo->id))
            ->assertOk();
    }

    public function test_admin_can_create_a_visitor(): void
    {
        $admin = $this->makeUser(User::ROLE_ADMIN);

        $this->actingAs($admin)
            ->post(route('admin.visitors.store'), [
                'name' => 'Nuevo Visitante',
                'email' => 'nuevo.visitante@pharenia.test',
                'birthdate' => '1993-08-15',
                'password' => 'TempPass1!',
            ])
            ->assertRedirect(route('admin.visitors'));

        $this->assertDatabaseHas('users', [
            'email' => 'nuevo.visitante@pharenia.test',
            'role' => User::ROLE_VISITOR,
        ]);
    }

    public function test_admin_can_destroy_a_visitor(): void
    {
        $admin = $this->makeUser(User::ROLE_ADMIN);
        $visitor = $this->makeUser(User::ROLE_VISITOR);

        $this->actingAs($admin)
            ->delete(route('admin.visitors.destroy', $visitor->id))
            ->assertRedirect(route('admin.visitors'));

        $this->assertDatabaseMissing('users', ['id' => $visitor->id]);
    }

    public function test_admin_can_update_a_visitor(): void
    {
        $admin = $this->makeUser(User::ROLE_ADMIN);
        $visitor = $this->makeUser(User::ROLE_VISITOR);

        $this->actingAs($admin)
            ->put(route('admin.visitors.update', $visitor->id), [
                'name' => 'Visitante Renombrado',
                'email' => 'renombrado@pharenia.test',
                'birthdate' => '1990-05-20',
            ])
            ->assertRedirect(route('admin.visitors'));

        $this->assertDatabaseHas('users', [
            'id' => $visitor->id,
            'name' => 'Visitante Renombrado',
            'email' => 'renombrado@pharenia.test',
        ]);
    }

    public function test_visitor_registration_rejects_users_12_or_younger(): void
    {
        $this->from(route('register'))
            ->post(route('register'), [
                'name' => 'Menor',
                'email' => 'menor@pharenia.test',
                'birthdate' => now()->subYears(12)->toDateString(),
                'role' => 'visitor',
                'terms' => '1',
                'password' => 'Passw0rd!abc',
                'password_confirmation' => 'Passw0rd!abc',
            ])
            ->assertSessionHasErrors('birthdate');

        $this->assertDatabaseMissing('users', ['email' => 'menor@pharenia.test']);
    }
}