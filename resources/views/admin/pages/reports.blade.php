@extends('admin.layouts.app')

@section('title', 'Rapports')

@section('content')
@include('admin.components.page-header', [
  'title' => 'Rapports du système',
  'subtitle' => 'Affichage et analyse des rapports de plateforme'
])

<div class="row">
  <div class="col-md-3">
    @include('admin.components.stat-card', [
      'title' => 'Total Users',
      'value' => \App\Models\User::count(),
      'label' => 'Active',
      'bgColor' => 'bg-primary-subtle'
    ])
  </div>
  <div class="col-md-3">
    @include('admin.components.stat-card', [
      'title' => 'Instructors',
      'value' => \App\Models\User::where('role', App\Enums\Role::Enseignant)->count(),
      'label' => 'Teaching',
      'bgColor' => 'bg-warning-subtle'
    ])
  </div>
  <div class="col-md-3">
    @include('admin.components.stat-card', [
      'title' => 'Students',
      'value' => \App\Models\User::where('role', App\Enums\Role::Etudiant)->count(),
      'label' => 'Learning',
      'bgColor' => 'bg-success-subtle'
    ])
  </div>
  <div class="col-md-3">
    @include('admin.components.stat-card', [
      'title' => 'Admins',
      'value' => \App\Models\User::where('role', App\Enums\Role::Administrateur)->count(),
      'label' => 'System',
      'bgColor' => 'bg-danger-subtle'
    ])
  </div>
</div>

<div class="row mt-4">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h5>User Distribution by Role</h5>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Role</th>
                <th>Count</th>
                <th>Percentage</th>
              </tr>
            </thead>
            <tbody>
              @php
              $total = \App\Models\User::count();
              $roles = [
                'Administrators' => \App\Models\User::where('role', App\Enums\Role::Administrateur)->count(),
                'Instructors' => \App\Models\User::where('role', App\Enums\Role::Enseignant)->count(),
                'Students' => \App\Models\User::where('role', App\Enums\Role::Etudiant)->count(),
              ];
              @endphp
              @foreach($roles as $roleName => $count)
              <tr>
                <td>{{ $roleName }}</td>
                <td><strong>{{ $count }}</strong></td>
                <td>{{ $total > 0 ? round(($count / $total) * 100, 2) : 0 }}%</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
