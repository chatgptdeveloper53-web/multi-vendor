@extends('admin.layouts.app')

@section('title', 'Gestion des sondages')

@section('content')
@include('admin.components.page-header', [
  'title' => 'Sondages et Retours',
  'subtitle' => 'Créez des sondages pour recueillir les retours des étudiants',
  'action' => '<a href="javascript:void(0)" class="btn btn-primary">Créer un nouveau sondage</a>'
])

<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <h5>Liste des sondages</h5>
      </div>
      <div class="card-body">
        <table class="table table-hover">
          <thead class="table-light">
            <tr>
              <th>Titre</th>
              <th>Cours</th>
              <th>Questions</th>
              <th>Réponses reçues</th>
              <th>Taux de réponse</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td colspan="7">
                <p class="text-muted">Aucun sondage trouvé</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
