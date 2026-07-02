<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * La racine du site redirige un visiteur non connecté vers la page de connexion.
     */
    public function test_guest_visiting_root_is_redirected_to_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('login'));
    }

    /**
     * La page de connexion est accessible publiquement.
     */
    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }
}
