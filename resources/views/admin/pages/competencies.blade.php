@extends('admin.layouts.app')

@section('title', 'Gestion des compétences')

@section('content')
@include('admin.components.page-header', [
  'title' => 'Gestion des compétences',
  'subtitle' => 'Définissez et gérez les compétences de votre plateforme',
  'action' => '<a href="javascript:void(0)" class="btn btn-primary">Ajouter une compétence</a>'
])

<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <h5>Compétences disponibles</h5>
      </div>
      <div class="card-body">
        <table class="table table-hover">
          <thead class="table-light">
            <tr>
              <th>Nom</th>
              <th>Description</th>
              <th>Nombres de cours</th>
              <th>Étudiants ayant acquis</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td colspan="5">
                <p class="text-muted">Aucune compétence trouvée</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
