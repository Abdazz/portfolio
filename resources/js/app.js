import Lenis from 'lenis';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

let lenis = null;
let rafId = null;

function initMotion() {
    if (reduced) return;

    // Tear down a previous run (Livewire re-navigation) before re-initialising.
    if (lenis) {
        lenis.destroy?.();
        if (rafId) cancelAnimationFrame(rafId);
        ScrollTrigger.getAll().forEach((t) => t.kill());
    }

    lenis = new Lenis({ duration: 1.1, smoothWheel: true });
    function raf(time) { lenis.raf(time); rafId = requestAnimationFrame(raf); }
    rafId = requestAnimationFrame(raf);
    lenis.on('scroll', ScrollTrigger.update);

    document.querySelectorAll('[data-reveal]').forEach((el) => {
        gsap.from(el, {
            opacity: 0, y: 24, duration: 0.7, ease: 'power2.out',
            scrollTrigger: { trigger: el, start: 'top 85%' },
        });
    });
}

document.addEventListener('DOMContentLoaded', initMotion);
document.addEventListener('livewire:navigated', () => { ScrollTrigger.refresh(); initMotion(); });
