<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\SuperAdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Passport;
use Tests\TestCase;

class SuperAdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    private string $passportKeyDirectory;

    protected function setUp(): void
    {
        parent::setUp();

        $key = openssl_pkey_new([
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
            'private_key_bits' => 2048,
        ]);
        $this->assertNotFalse($key);
        $this->assertTrue(openssl_pkey_export($key, $privateKey));

        $keyDetails = openssl_pkey_get_details($key);
        $this->assertIsArray($keyDetails);

        $this->passportKeyDirectory = sys_get_temp_dir().'/passport-test-'.Str::uuid();
        mkdir($this->passportKeyDirectory, 0700, true);
        file_put_contents($this->passportKeyDirectory.'/oauth-private.key', $privateKey);
        file_put_contents($this->passportKeyDirectory.'/oauth-public.key', $keyDetails['key']);
        chmod($this->passportKeyDirectory.'/oauth-private.key', 0600);
        chmod($this->passportKeyDirectory.'/oauth-public.key', 0600);
        Passport::loadKeysFrom($this->passportKeyDirectory);
    }

    protected function tearDown(): void
    {
        unlink($this->passportKeyDirectory.'/oauth-private.key');
        unlink($this->passportKeyDirectory.'/oauth-public.key');
        rmdir($this->passportKeyDirectory);
        Passport::loadKeysFrom(storage_path());

        parent::tearDown();
    }

    public function test_user_management_requires_a_superadmin(): void
    {
        $this->getJson('/api/users')->assertUnauthorized();

        $user = User::factory()->create();
        Passport::actingAs($user);

        $this->getJson('/api/users')->assertForbidden();
    }

    public function test_superadmin_can_log_in_and_receive_a_passport_token(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'correct-password',
            'is_superadmin' => true,
        ]);
        app(ClientRepository::class)->createPersonalAccessGrantClient('Test client', 'users');

        $response = $this->postJson('/api/auth/login', [
            'email' => $admin->email,
            'password' => 'correct-password',
        ])->assertOk()
            ->assertJsonPath('token_type', 'Bearer');

        $this->withToken($response->json('access_token'))
            ->getJson('/api/auth/current-user')
            ->assertOk()
            ->assertJsonPath('data.email', $admin->email);
    }

    public function test_superadmin_seeder_persists_credentials_in_the_database(): void
    {
        $this->seed(SuperAdminSeeder::class);

        $admin = User::where('email', 'superadmin@example.com')->firstOrFail();
        $this->assertSame('Super Admin', $admin->name);
        $this->assertTrue($admin->is_superadmin);
        $this->assertTrue(Hash::check('Password@123', $admin->password));
    }

    public function test_superadmin_can_create_a_user_without_an_image(): void
    {
        $admin = User::factory()->create(['is_superadmin' => true]);
        Passport::actingAs($admin);

        $this->postJson('/api/users', [
            'name' => 'New User',
            'email' => 'new-user@example.com',
            'password' => 'a-strong-password',
        ])->assertCreated()
            ->assertJsonPath('data.avatar_url', null);

        $this->assertDatabaseHas('users', [
            'email' => 'new-user@example.com',
            'avatar_path' => null,
            'is_superadmin' => false,
        ]);
    }

    public function test_user_index_returns_resource_data_with_the_existing_pagination_envelope(): void
    {
        $admin = User::factory()->create(['is_superadmin' => true]);
        User::factory()->create(['name' => 'Listed User']);
        Passport::actingAs($admin);

        $this->getJson('/api/users')
            ->assertOk()
            ->assertJsonPath('data.0.name', 'Listed User')
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [['id', 'name', 'email', 'avatar_path', 'avatar_url', 'created_at']],
                'pagination' => ['current_page', 'last_page', 'per_page', 'total'],
            ]);
    }

    public function test_superadmin_can_update_a_user_without_replacing_the_image(): void
    {
        $admin = User::factory()->create(['is_superadmin' => true]);
        $user = User::factory()->create(['name' => 'Before Update']);
        Passport::actingAs($admin);

        $this->putJson('/api/users/'.$user->id, [
            'name' => 'After Update',
            'email' => $user->email,
        ])->assertOk()
            ->assertJsonPath('data.name', 'After Update')
            ->assertJsonPath('data.email', $user->email);
    }

    public function test_superadmin_can_create_a_user_with_an_image(): void
    {
        Storage::fake();

        $admin = User::factory()->create(['is_superadmin' => true]);
        Passport::actingAs($admin);

        $response = $this->post('/api/users', [
            'name' => 'Image User',
            'email' => 'image-user@example.com',
            'password' => 'a-strong-password',
            'image' => UploadedFile::fake()->image('avatar.jpg'),
        ], ['Accept' => 'application/json']);

        $response->assertCreated();

        $user = User::where('email', 'image-user@example.com')->firstOrFail();
        $this->assertNotNull($user->avatar_path);
        Storage::assertExists($user->avatar_path);
        $response->assertJsonPath('data.avatar_url', route('users.avatar', $user));

        $this->get(route('users.avatar', $user))
            ->assertOk()
            ->assertHeader('content-type', 'image/jpeg');
    }
}
