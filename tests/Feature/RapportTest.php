<?php

namespace Tests\Feature;

use App\Models\Entretien;
use App\Models\Plein;
use App\Models\User;
use App\Models\Vehicule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RapportTest extends TestCase
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
        $response = $this->get(route('rapports.depenses'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function un_chauffeur_ne_peut_pas_acceder_aux_rapports(): void
    {
        $chauffeur = User::factory()->create(['role' => 'chauffeur']);

        $this->actingAs($chauffeur)->get(route('rapports.depenses'))->assertForbidden();
        $this->actingAs($chauffeur)->get(route('rapports.kilometrage'))->assertForbidden();
    }

    /**
     * US13 — « Étant donné des entretiens et des pleins enregistrés, quand le
     * gestionnaire ouvre le tableau de bord, alors les dépenses cumulées par
     * véhicule s'affichent. »
     *
     * @test
     */
    public function les_depenses_cumulees_par_vehicule_sont_calculees_correctement(): void
    {
        $vehicule = Vehicule::factory()->create();
        Entretien::factory()->create(['vehicule_id' => $vehicule->id, 'cout' => 50000]);
        Entretien::factory()->create(['vehicule_id' => $vehicule->id, 'cout' => 30000]);
        Plein::factory()->create(['vehicule_id' => $vehicule->id, 'montant' => 20000]);

        $response = $this->actingAs($this->admin)->get(route('rapports.depenses'));

        $response->assertOk();
        $response->assertViewHas('vehicules', function ($vehicules) use ($vehicule) {
            $ligne = $vehicules->firstWhere('id', $vehicule->id);

            return $ligne->cout_entretiens == 80000
                && $ligne->cout_carburant == 20000
                && $ligne->cout_total == 100000;
        });
    }

    /** @test */
    public function le_vehicule_le_plus_couteux_apparait_en_premier(): void
    {
        $vehiculeCher = Vehicule::factory()->create(['immatriculation' => 'CHER-001']);
        Entretien::factory()->create(['vehicule_id' => $vehiculeCher->id, 'cout' => 500000]);

        $vehiculePasCher = Vehicule::factory()->create(['immatriculation' => 'PASCHER-002']);
        Entretien::factory()->create(['vehicule_id' => $vehiculePasCher->id, 'cout' => 1000]);

        $response = $this->actingAs($this->admin)->get(route('rapports.depenses'));

        $response->assertViewHas('vehicules', function ($vehicules) {
            return $vehicules->first()->immatriculation === 'CHER-001';
        });
    }

    /** @test */
    public function lexport_csv_des_depenses_est_accessible_et_bien_forme(): void
    {
        $vehicule = Vehicule::factory()->create(['immatriculation' => 'EXPORT-01']);
        Entretien::factory()->create(['vehicule_id' => $vehicule->id, 'cout' => 15000]);

        $response = $this->actingAs($this->admin)->get(route('rapports.depenses.export'));

        $response->assertOk();
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');

        $contenu = $response->streamedContent();
        $this->assertStringContainsString('EXPORT-01', $contenu);
        $this->assertStringContainsString('Immatriculation', $contenu);
    }

    /**
     * US14 — « Étant donné les kilométrages de tous les véhicules, quand le
     * gestionnaire ouvre le dashboard, alors le kilométrage cumulé du parc
     * s'affiche. »
     *
     * @test
     */
    public function le_kilometrage_cumule_du_parc_est_correct(): void
    {
        Vehicule::factory()->create(['kilometrage' => 10000]);
        Vehicule::factory()->create(['kilometrage' => 25000]);
        Vehicule::factory()->create(['kilometrage' => 5000]);

        $response = $this->actingAs($this->admin)->get(route('rapports.kilometrage'));

        $response->assertOk();
        $response->assertViewHas('kilometrageTotal', 40000);
        $response->assertViewHas('kilometrageMoyen', 13333);
    }

    /** @test */
    public function le_dashboard_admin_affiche_les_statistiques_du_parc(): void
    {
        $vehicule = Vehicule::factory()->create(['kilometrage' => 12000, 'statut' => 'disponible']);
        Entretien::factory()->create(['vehicule_id' => $vehicule->id, 'cout' => 10000]);
        Plein::factory()->create(['vehicule_id' => $vehicule->id, 'montant' => 15000]);

        $response = $this->actingAs($this->admin)->get(route('dashboard'));

        $response->assertOk();
        $response->assertViewHas('nombreVehicules', 1);
        $response->assertViewHas('vehiculesDisponibles', 1);
        $response->assertViewHas('kilometrageTotal', 12000);
        $response->assertViewHas('depensesTotal', 25000);
    }
}
