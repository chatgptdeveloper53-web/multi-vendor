<div class="row mb-4">
  <div class="col-12">
    <div class="d-flex align-items-center justify-content-between">
      <div>
        <h1 class="mb-0 fw-bold">{{ $title }}</h1>
        @if (isset($subtitle))
        <p class="text-muted mb-0">{{ $subtitle }}</p>
        @endif
      </div>
      @if (isset($action))
      <div>
        {!! $action !!}
      </div>
      @endif
    </div>
  </div>
</div>
