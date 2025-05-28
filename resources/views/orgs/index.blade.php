@extends('layouts.nice', ['active'=>'orgs','title'=>'Organizaciones'])

@section('content')
    <head>
        <link rel="stylesheet" href="{{ asset('theme/resi/assets/css/orgs.css') }}">
    </head>

    <div class="orgs-container">
        <div class="orgs-header">
            <div class="orgs-title">
                <h1>Organizaciones</h1>
                <p class="orgs-subtitle">Gestiona todas tus organizaciones desde un solo lugar</p>
            </div>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/"><i class="bi bi-house-door"></i> <span class="breadcrumb-text">Home</span></a></li>
                    <li class="breadcrumb-item active">Organizaciones</li>
                </ol>
            </nav>
        </div>

        <section class="orgs-dashboard">
            <div class="orgs-grid">
                @foreach($orgs as $org)
                <div class="org-card-wrapper">
                    <div class="org-card {{ $org->active ? 'active' : 'inactive' }}">
                        <div class="org-card-content">
                            <div class="org-card-banner">
                                <div class="org-status">
                                    @if($org->active)
                                    <span class="status-indicator active" aria-hidden="true"></span>
                                    <span class="status-text">Activado</span>
                                    @else
                                    <span class="status-indicator inactive" aria-hidden="true"></span>
                                    <span class="status-text">Desactivado</span>
                                    @endif
                                </div>
                                <div class="org-id">
                                    <span class="rut-label">RUT</span>
                                    <span class="rut-value">{{$org->rut}}</span>
                                </div>
                            </div>

                            <div class="org-card-body">
                                <div class="org-icon-container">
                                    <div class="org-icon">
                                        <i class="bi bi-map-fill" aria-hidden="true"></i>
                                    </div>
                                    @if($org->active)
                                    <div class="org-status-ring" aria-hidden="true"></div>
                                    @endif
                                </div>

                                <div class="org-info">
                                    <div class="org-names">
                                        <h2 class="org-fantasy-name" title="{{$org->fantasy_name}}">{{$org->fantasy_name}}</h2>
                                        <h3 class="org-name" title="{{$org->name}}">{{$org->name}}</h3>
                                    </div>

                                    <div class="org-location">
                                        <i class="bi bi-geo-alt" aria-hidden="true"></i>
                                        <address title="{{ $org->address}}, {{$org->commune}}, {{$org->state}}, Chile">
                                            <span class="location-primary">{{ $org->address}}</span>
                                            <span class="location-secondary">
                                                <span class="location-commune">{{$org->commune}}</span>,
                                            </span>
                                            <span class="location-tertiary">
                                                <span class="location-state">{{$org->state}}</span>,
                                                <span class="location-country">Chile</span>
                                            </span>
                                        </address>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <a href="{{route('orgs.dashboard',$org->id)}}" class="org-action-btn primary">
                            <span class="btn-text">Ingresar</span>
                            <i class="bi bi-arrow-right" aria-hidden="true"></i>
                        </a>

                        <div class="org-card-background" aria-hidden="true"></div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="orgs-pagination">
                {!! $orgs->render('pagination::bootstrap-4') !!}
            </div>
        </section>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/organizations.css') }}">
@endpush
