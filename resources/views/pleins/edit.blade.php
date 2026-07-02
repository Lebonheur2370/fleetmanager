@extends('layouts.app')

@section('title', 'Modifier un plein')

@section('content')
    <h3 class="mb-4"><i class="fa-solid fa-gas-pump"></i> Modifier le plein du {{ $plein->date_plein->format('d/m/Y') }}</h3>

    <div class="card shadow-sm" style="max-width: 700px;">
        <div class="card-body">
            <form method="POST" action="{{ route('pleins.update', $plein) }}">
                @csrf
                @method('PUT')
                @include('pleins._form')

                <button type="submit" class="btn btn-success">
                    <i class="fa-solid fa-check"></i> Mettre à jour
                </button>
                <a href="{{ route('pleins.index') }}" class="btn btn-outline-secondary">Annuler</a>
            </form>
        </div>
    </div>
@endsection
