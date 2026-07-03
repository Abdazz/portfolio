import Lenis from 'lenis';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

function initMotion() {
    if (reduced) return;

    const lenis = new Lenis({ duration: 1.1, smoothWheel: true });
    function raf(time) { lenis.raf(time); requestAnimationFrame(raf); }
    requestAnimationFrame(raf);
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
