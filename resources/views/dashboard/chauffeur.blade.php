@extends('layouts.app')

@section('title', 'Tableau de bord - Chauffeur')

@section('content')
    <h3 class="mb-4">Bonjour {{ auth()->user()->chauffeur?->prenom }}</h3>
    <p class="text-muted">Votre véhicule affecté et vos derniers pleins s'afficheront ici (Epic 3 et Epic 5).</p>
@endsection
