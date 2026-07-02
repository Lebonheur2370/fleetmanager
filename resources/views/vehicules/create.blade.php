@extends('layouts.app')

@section('title', 'Ajouter un véhicule')

@section('content')
    <h3 class="mb-4"><i class="fa-solid fa-car"></i> Ajouter un véhicule</h3>

    <div class="card shadow-sm" style="max-width: 700px;">
        <div class="card-body">
            <form method="POST" action="{{ route('vehicules.store') }}">
                @csrf
                @include('vehicules._form')

                <button type="submit" class="btn btn-success">
                    <i class="fa-solid fa-check"></i> Enregistrer le véhicule
                </button>
                <a href="{{ route('vehicules.index') }}" class="btn btn-outline-secondary">Annuler</a>
            </form>
        </div>
    </div>
@endsection
