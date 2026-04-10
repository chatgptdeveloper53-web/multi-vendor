@extends('admin.layouts.app')

@section('title', 'Gestion des cours')

@section('content')
@include('admin.components.page-header', [
  'title' => 'Gestion des cours',
  'subtitle' => 'Gérez tous les cours de la plateforme',
  'action' => '<a href="javascript:void(0)" class="btn btn-primary">Ajouter un nouveau cours</a>'
])

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h5>Liste des cours</h5>
      </div>
      <div class="card-body">
        <div class="alert alert-info">
          <iconify-icon icon="solar:info-circle-line-duotone" class="me-2"></iconify-icon>
          Le module de gestion des cours arrive bientôt. Configurez votre structure de cours ici.
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
