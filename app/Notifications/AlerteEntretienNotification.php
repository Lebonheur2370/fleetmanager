<?php

namespace App\Notifications;

use App\Models\Entretien;
use App\Models\Vehicule;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AlerteEntretienNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Vehicule $vehicule,
        public Entretien $entretien,
        public array $motifs,
    ) {}

    /**
     * US10 — « ... alors une notification est envoyée au gestionnaire. »
     * Email (MAIL_MAILER=log en local, cf. .env) + notification en base
     * consultable depuis l'application.
     */
    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("FleetManager — Entretien à prévoir : {$this->vehicule->immatriculation}")
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Le véhicule {$this->vehicule->immatriculation} ({$this->vehicule->marque} {$this->vehicule->modele}) approche d'une échéance d'entretien préventif :")
            ->line(implode(' — ', $this->motifs))
            ->action('Voir le véhicule', route('vehicules.show', $this->vehicule))
            ->line("Merci de planifier l'entretien correspondant.");
    }

    public function toArray($notifiable): array
    {
        return [
            'vehicule_id' => $this->vehicule->id,
            'immatriculation' => $this->vehicule->immatriculation,
            'entretien_id' => $this->entretien->id,
            'type_entretien' => $this->entretien->type,
            'motifs' => $this->motifs,
        ];
    }
}
