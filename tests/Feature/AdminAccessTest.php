<?php

namespace Tests\Feature;

use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    public function test_dashboard_ditolak_tanpa_login(): void
    {
        $this->getJson('/admin/dashboard')
            ->assertStatus(403)
            ->assertJson(['message' => 'Akses ditolak. Anda bukan Admin.']);
    }

    public function test_dashboard_bisa_diakses_setelah_login(): void
    {
        $this->postJson('/login')->assertOk();

        $this->getJson('/admin/dashboard')
            ->assertOk()
            ->assertJson(['message' => 'Selamat datang di Panel Admin']);
    }

    public function test_dashboard_ditolak_lagi_setelah_logout(): void
    {
        $this->postJson('/login');
        $this->postJson('/logout')
            ->assertOk()
            ->assertJson(['status' => 'Anda telah keluar dari sistem']);

        $this->getJson('/admin/dashboard')->assertStatus(403);
    }

    public function test_tambah_barang_butuh_login_dan_tersimpan_di_session(): void
    {
        $payload = ['nama' => 'Mouse', 'harga' => 50000, 'stok' => 5];

        $this->postJson('/barang', $payload)->assertStatus(403);

        $this->postJson('/login');
        $this->postJson('/barang', $payload)->assertStatus(201);
        $this->postJson('/barang', ['nama' => ''])->assertStatus(422);

        $this->getJson('/barang')->assertOk()->assertJson(['jumlah' => 1]);
    }
}
