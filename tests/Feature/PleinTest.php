<?php

namespace Tests\Feature;

use App\Models\Plein;
use App\Models\User;
use App\Models\Vehicule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PleinTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    /** @test */
    public function un_visiteur_non_connecte_est_redirige_vers_le_login(): void
    {
        $response = $this->get(route('pleins.index'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function un_chauffeur_ne_peut_pas_acceder_a_la_gestion_des_pleins(): void
    {
        $chauffeur = User::factory()->create(['role' => 'chauffeur']);

        $response = $this->actingAs($chauffeur)->get(route('pleins.index'));

        $response->assertForbidden();
    }

    /** @test */
    public function ladmin_peut_consulter_la_liste_des_pleins(): void
    {
        Plein::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)->get(route('pleins.index'));

        $response->assertOk();
    }

    /**
     * US11 — « Étant donné un véhicule existant, quand le gestionnaire saisit la
     * date, le volume et le montant du plein, alors le plein est enregistré. »
     *
     * @test
     */
    public function ladmin_peut_enregistrer_un_plein_valide(): void
    {
        $vehicule = Vehicule::factory()->create(['kilometrage' => 10000]);

        $response = $this->actingAs($this->admin)->post(route('pleins.store'), [
            'vehicule_id' => $vehicule->id,
            'chauffeur_id' => null,
            'date_plein' => now()->format('Y-m-d'),
            'litres' => 45.5,
            'montant' => 35000,
            'kilometrage' => 10500,
        ]);

        $response->assertRedirect(route('pleins.index'));
        $this->assertDatabaseHas('pleins', [
            'vehicule_id' => $vehicule->id,
            'kilometrage' => 10500,
        ]);
    }

    /** @test */
    public function le_kilometrage_du_vehicule_est_mis_a_jour_apres_un_plein(): void
    {
        $vehicule = Vehicule::factory()->create(['kilometrage' => 10000]);

        $this->actingAs($this->admin)->post(route('pleins.store'), [
            'vehicule_id' => $vehicule->id,
            'date_plein' => now()->format('Y-m-d'),
            'litres' => 40,
            'montant' => 30000,
            'kilometrage' => 10800,
        ]);

        $this->assertEquals(10800, $vehicule->fresh()->kilometrage);
    }

    /** @test */
    public function un_plein_avec_un_kilometrage_inferieur_au_dernier_est_refuse(): void
    {
        $vehicule = Vehicule::factory()->create(['kilometrage' => 10000]);
        Plein::factory()->create(['vehicule_id' => $vehicule->id, 'kilometrage' => 10500]);

        $response = $this->actingAs($this->admin)->post(route('pleins.store'), [
            'vehicule_id' => $vehicule->id,
            'date_plein' => now()->format('Y-m-d'),
            'litres' => 30,
            'montant' => 20000,
            'kilometrage' => 10200,
        ]);

        $response->assertSessionHasErrors('kilometrage');
        $this->assertDatabaseMissing('pleins', ['kilometrage' => 10200]);
    }

    /** @test */
    public function les_champs_obligatoires_sont_valides(): void
    {
        $response = $this->actingAs($this->admin)->post(route('pleins.store'), []);

        $response->assertSessionHasErrors(['vehicule_id', 'date_plein', 'litres', 'montant', 'kilometrage']);
    }

    /** @test */
    public function ladmin_peut_modifier_un_plein(): void
    {
        $plein = Plein::factory()->create(['litres' => 30]);

        $response = $this->actingAs($this->admin)->put(route('pleins.update', $plein), [
            'vehicule_id' => $plein->vehicule_id,
            'chauffeur_id' => null,
            'date_plein' => $plein->date_plein->format('Y-m-d'),
            'litres' => 50,
            'montant' => $plein->montant,
            'kilometrage' => $plein->kilometrage,
        ]);

        $response->assertRedirect(route('pleins.index'));
        $this->assertEquals(50, $plein->fresh()->litres);
    }

    /** @test */
    public function ladmin_peut_supprimer_un_plein(): void
    {
        $plein = Plein::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('pleins.destroy', $plein));

        $response->assertRedirect(route('pleins.index'));
        $this->assertDatabaseMissing('pleins', ['id' => $plein->id]);
    }

    /**
     * US12 — « Étant donné l'historique des pleins d'un véhicule, quand le
     * gestionnaire consulte sa fiche, alors la consommation moyenne en
     * L/100km s'affiche. »
     *
     * @test
     */
    public function la_consommation_moyenne_est_calculee_correctement(): void
    {
        $vehicule = Vehicule::factory()->create(['kilometrage' => 10300]);

        Plein::factory()->create(['vehicule_id' => $vehicule->id, 'kilometrage' => 10000, 'litres' => 40]);
        Plein::factory()->create(['vehicule_id' => $vehicule->id, 'kilometrage' => 10300, 'litres' => 30]);

        // distance = 300 km, litres total = 70 L -> (70/300)*100 = 23.33 L/100km
        $this->assertEquals(23.33, $vehicule->consommationMoyenne());
    }

    /** @test */
    public function la_consommation_est_nulle_si_un_seul_plein_est_enregistre(): void
    {
        $vehicule = Vehicule::factory()->create();
        Plein::factory()->create(['vehicule_id' => $vehicule->id]);

        $this->assertNull($vehicule->consommationMoyenne());
    }

    /** @test */
    public function la_page_consommation_est_accessible_et_affiche_les_vehicules(): void
    {
        $vehicule = Vehicule::factory()->create();
        Plein::factory()->count(2)->create(['vehicule_id' => $vehicule->id]);

        $response = $this->actingAs($this->admin)->get(route('pleins.consommation'));

        $response->assertOk();
        $response->assertSee($vehicule->immatriculation);
    }
}
