<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleRedirectionTest extends TestCase
{
    use RefreshDatabase;

    protected $director;
    protected $manager;
    protected $employee;
    protected $inactiveUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Register 'role' middleware alias for tests
        $this->app['router']->aliasMiddleware('role', \App\Http\Middleware\CheckRole::class);

        // Create a branch for users
        $branch = \App\Models\Branch::create([
            'name' => 'Test Branch',
            'address' => '123 Test St',
            'phone' => '1234567890',
            'email' => 'testbranch@example.com',
            'is_active' => true,
        ]);

        // Create users for each role with branch_id
        $this->director = User::factory()->create([
            'role' => 'director',
            'is_active' => true,
            'branch_id' => $branch->id,
            'password' => bcrypt('password'),
        ]);
        $this->manager = User::factory()->create([
            'role' => 'manager',
            'is_active' => true,
            'branch_id' => $branch->id,
            'password' => bcrypt('password'),
        ]);
        $this->employee = User::factory()->create([
            'role' => 'employee',
            'is_active' => true,
            'branch_id' => $branch->id,
            'password' => bcrypt('password'),
        ]);
        $this->inactiveUser = User::factory()->create([
            'role' => 'employee',
            'is_active' => false,
            'branch_id' => $branch->id,
            'password' => bcrypt('password'),
        ]);
    }

    public function test_director_login_redirects_to_director_dashboard()
    {
        $response = $this->post('/login', [
            'email' => $this->director->email,
            'password' => 'password',
        ]);
        $response->assertRedirect(route('director.dashboard'));
    }

    public function test_manager_login_redirects_to_manager_dashboard()
    {
        $response = $this->post('/login', [
            'email' => $this->manager->email,
            'password' => 'password',
        ]);
        $response->assertRedirect(route('manager.dashboard'));
    }

    public function test_employee_login_redirects_to_employee_dashboard()
    {
        $response = $this->post('/login', [
            'email' => $this->employee->email,
            'password' => 'password',
        ]);
        $response->assertRedirect(route('employee.dashboard'));
    }

    public function test_inactive_user_cannot_login()
    {
        $response = $this->post('/login', [
            'email' => $this->inactiveUser->email,
            'password' => 'password',
        ]);
        $response->assertSessionHasErrors('email');
    }

    public function test_login_with_invalid_credentials_fails()
    {
        $response = $this->post('/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'wrongpassword',
        ]);
        $response->assertSessionHasErrors('email');
    }

    public function test_registration_redirects_based_on_role()
    {
        $response = $this->withMiddleware('web')->post('/register', [
            'name' => 'New Manager',
            'email' => 'newmanager@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'manager',
        ]);
        $response->assertRedirect(route('manager.dashboard'));

        $redirectResponse = $this->get(route('manager.dashboard'));
        $redirectResponse->assertSee('Dashboard');

        $response = $this->withMiddleware('web')->post('/register', [
            'name' => 'New Employee',
            'email' => 'newemployee@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'employee',
        ]);
        $response->assertRedirect(route('employee.dashboard'));

        $redirectResponse = $this->get(route('employee.dashboard'));
        $redirectResponse->assertSee('Dashboard');
    }

    public function test_unauthorized_access_redirects_to_login_with_error()
    {
        $response = $this->withMiddleware('web')->get(route('director.dashboard'));
        $response->assertRedirect(route('login'));

        $this->actingAs($this->employee);
        $response = $this->withMiddleware('web')->get(route('director.dashboard'));
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error', 'Unauthorized access. Director role required.');
    }

    public function test_user_permission_methods()
    {
        $this->assertTrue($this->director->canManageUsers());
        $this->assertTrue($this->manager->canManageUsers());
        $this->assertFalse($this->employee->canManageUsers());

        $this->assertTrue($this->employee->canProcessTransactions());
        $this->assertTrue($this->manager->canProcessTransactions());
        $this->assertFalse($this->director->canProcessTransactions());
    }
}
