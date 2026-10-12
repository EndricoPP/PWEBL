<?php

namespace Tests\Feature;

use Database\Seeders\AdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    private function register(string $email, string $name = 'User'): void
    {
        $this->postJson('/register', [
            'name' => $name, 'email' => $email, 'password' => 'rahasia123',
        ])->assertStatus(201);
    }

    private function login(string $email, string $password = 'rahasia123')
    {
        return $this->postJson('/login', ['email' => $email, 'password' => $password]);
    }

    public function test_dashboard_ditolak_tanpa_login(): void
    {
        $this->getJson('/admin/dashboard')
            ->assertStatus(403)
            ->assertJson(['message' => 'Akses ditolak. Anda bukan Admin.']);
    }

    public function test_admin_bisa_masuk_dashboard_dan_ditolak_lagi_setelah_logout(): void
    {
        $this->seed(AdminSeeder::class);

        $this->login('admin@example.com', 'password')->assertOk();
        $this->getJson('/admin/dashboard')->assertOk();

        $this->postJson('/logout')
            ->assertOk()
            ->assertJson(['status' => 'Anda telah keluar dari sistem']);

        $this->getJson('/admin/dashboard')->assertStatus(403);
    }

    public function test_user_biasa_tidak_bisa_masuk_dashboard_admin(): void
    {
        $this->register('budi@example.com');
        $this->login('budi@example.com')->assertOk();

        $this->getJson('/admin/dashboard')->assertStatus(403);
    }

    public function test_register_validasi_dan_email_unik(): void
    {
        $this->register('budi@example.com');

        $this->postJson('/register', ['name' => 'X', 'email' => 'budi@example.com', 'password' => 'rahasia123'])
            ->assertStatus(422);
        $this->postJson('/register', ['name' => 'X', 'email' => 'bukan-email', 'password' => '1'])
            ->assertStatus(422);
    }

    public function test_login_gagal_dengan_password_salah(): void
    {
        $this->register('budi@example.com');

        $this->login('budi@example.com', 'salah')->assertStatus(401);
        $this->login('tidakada@example.com')->assertStatus(401);
    }

    public function test_barang_butuh_login(): void
    {
        $this->getJson('/barang')->assertStatus(401);
        $this->postJson('/barang', ['nama' => 'Mouse', 'harga' => 1, 'stok' => 1])->assertStatus(401);
    }

    public function test_setiap_akun_hanya_melihat_barang_miliknya(): void
    {
        $this->register('budi@example.com');
        $this->register('siti@example.com');

        $this->login('budi@example.com');
        $this->postJson('/barang', ['nama' => 'Mouse Budi', 'harga' => 50000, 'stok' => 5])->assertStatus(201);
        $this->postJson('/barang', ['nama' => ''])->assertStatus(422);
        $this->getJson('/barang')->assertOk()->assertJson(['jumlah' => 1])->assertJsonPath('data.0.nama', 'Mouse Budi');
        $this->postJson('/logout');

        $this->login('siti@example.com');
        $this->getJson('/barang')->assertOk()->assertJson(['jumlah' => 0]);
        $this->postJson('/barang', ['nama' => 'Kabel Siti', 'harga' => 10000, 'stok' => 2])->assertStatus(201);
        $this->getJson('/barang')->assertJson(['jumlah' => 1])->assertJsonPath('data.0.nama', 'Kabel Siti');
        $this->postJson('/logout');

        // Budi login lagi: barangnya masih ada (tersimpan di database), milik Siti tidak terlihat
        $this->login('budi@example.com');
        $this->getJson('/barang')->assertJson(['jumlah' => 1])->assertJsonPath('data.0.nama', 'Mouse Budi');
    }

    public function test_login_kosong_ditolak_422_json_walau_tanpa_header_accept(): void
    {
        $this->post('/login')->assertStatus(422)->assertJsonValidationErrors(['email', 'password']);
        $this->post('/register')->assertStatus(422)->assertJsonValidationErrors(['name', 'email', 'password']);
    }
}
