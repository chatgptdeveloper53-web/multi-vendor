@extends('admin.layouts.app')

@section('title', 'Gestion des quiz et examens')

@section('content')
@include('admin.components.page-header', [
  'title' => 'Quiz et Examens',
  'subtitle' => 'Créez et gérez les évaluations de vos cours',
  'action' => '<a href="javascript:void(0)" class="btn btn-primary">Créer un nouveau quiz</a>'
])

<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <h5>Liste des quiz</h5>
      </div>
      <div class="card-body">
        <table class="table table-hover">
          <thead class="table-light">
            <tr>
              <th>Titre</th>
              <th>Cours</th>
              <th>Questions</th>
              <th>Durée limite</th>
              <th>Seuil de réussite</th>
              <th>Tentatives</th>
              <th>Taux de réussite</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td colspan="8">
                <p class="text-muted">Aucun quiz trouvé</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
