<div class="card {{ $bgColor ?? 'bg-secondary-subtle' }} overflow-hidden shadow-none">
  <div class="card-body p-4">
    <span class="text-dark-light">{{ $title }}</span>
    <div class="hstack gap-6">
      <h5 class="mb-0 fs-7">{{ $value }}</h5>
      <span class="fs-11 text-dark-light fw-semibold">{{ $label ?? 'Active' }}</span>
    </div>
  </div>
</div>
