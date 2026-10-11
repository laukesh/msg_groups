<div {{ $attributes->merge(['class' => 'card']) }}>
  <div class="card-header">
    <h4>{{ $title }}</h4>
    @isset($subtitle)
      <span class="card-subtitle">{{ $subtitle }}</span>
    @endisset
  </div>
  <div class="card-body">
    {{ $slot }}
  </div>
</div>
