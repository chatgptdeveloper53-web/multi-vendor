<nav aria-label="breadcrumb" class="mb-4">
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    </li>
    @foreach ($items as $label => $url)
    <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}" @if ($loop->last) aria-current="page" @endif>
      @if ($loop->last)
        {{ $label }}
      @else
        <a href="{{ $url }}">{{ $label }}</a>
      @endif
    </li>
    @endforeach
  </ol>
</nav>
