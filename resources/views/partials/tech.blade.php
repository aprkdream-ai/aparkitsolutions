@php($icons = ['Backend' => 'code', 'Frontend' => 'layout', 'Mobile' => 'smartphone', 'Databases' => 'database', 'Cloud & DevOps' => 'cloud', 'Payments' => 'card', 'Integrations' => 'plug', 'AI & Automation' => 'sparkles'])
<div class="grid grid-tech">
    @foreach ($tech as $group => $items)
        <div class="tech-group">
            <div class="tech-group-head">
                <span class="icon-tile icon-tile--sm"><x-icon :name="$icons[$group] ?? 'layers'" /></span>
                <p class="tech-group-title">{{ $group }}</p>
                <span class="tech-count">{{ count($items) }}</span>
            </div>
            <ul class="chips">
                @foreach ($items as $item)<li class="chip">{{ $item }}</li>@endforeach
            </ul>
        </div>
    @endforeach
</div>
