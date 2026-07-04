@props(['value', 'label', 'suffix' => ''])

@php($target = (int) $value)

<div
    class="flex flex-col gap-1"
    x-data="{
        n: 0,
        target: {{ $target }},
        count() {
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) { this.n = this.target; return; }
            const duration = 1400, start = performance.now();
            const tick = (now) => {
                const p = Math.min((now - start) / duration, 1);
                this.n = Math.round((1 - Math.pow(1 - p, 3)) * this.target);
                if (p < 1) requestAnimationFrame(tick);
            };
            requestAnimationFrame(tick);
        },
    }"
    x-init="
        const io = new IntersectionObserver((entries) => {
            entries.forEach((e) => { if (e.isIntersecting) { count(); io.disconnect(); } });
        }, { threshold: 0.4 });
        io.observe($el);
    "
>
    <div class="flex items-baseline gap-1 font-medium leading-none">
        <span class="text-4xl md:text-5xl gradient-text" x-text="n">{{ $target }}</span>
        @if ($suffix)
            <span class="text-2xl md:text-3xl text-accent-content">{{ $suffix }}</span>
        @endif
    </div>
    <span class="text-sm text-text-muted">{{ $label }}</span>
</div>
