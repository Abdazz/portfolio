import Lenis from 'lenis';
import gsap from 'gsap';

const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

let lenis = null;
let rafId = null;
let observer = null;

// Fail-safe: clear any GSAP-applied inline styles so content is never left
// hidden if the animation pipeline throws or the browser lacks the APIs we use.
function revealAll() {
    document.querySelectorAll('[data-reveal]').forEach((el) => {
        el.style.removeProperty('opacity');
        el.style.removeProperty('transform');
    });
}

// Optional smooth scrolling. Kept independent of the reveal system so a Lenis
// failure never blocks content from appearing.
function initSmoothScroll() {
    if (lenis) {
        lenis.destroy?.();
        if (rafId) { cancelAnimationFrame(rafId); }
    }

    try {
        lenis = new Lenis({ duration: 1.1, smoothWheel: true });
        const raf = (time) => { lenis.raf(time); rafId = requestAnimationFrame(raf); };
        rafId = requestAnimationFrame(raf);
    } catch (e) {
        lenis = null;
    }
}

// Scroll-reveal driven by IntersectionObserver — fires on native scroll and is
// decoupled from Lenis/ScrollTrigger, so smooth-scroll issues can't hide content.
function initReveals() {
    if (observer) { observer.disconnect(); }

    const els = document.querySelectorAll('[data-reveal]');

    if (!('IntersectionObserver' in window)) {
        revealAll();
        return;
    }

    try {
        observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) { return; }
                gsap.to(entry.target, { opacity: 1, y: 0, duration: 0.7, ease: 'power2.out' });
                observer.unobserve(entry.target);
            });
        }, { rootMargin: '0px 0px -10% 0px' });

        els.forEach((el) => {
            gsap.set(el, { opacity: 0, y: 24 });
            observer.observe(el);
        });
    } catch (e) {
        revealAll();
    }
}

function initMotion() {
    if (reduced) {
        revealAll();
        return;
    }

    initSmoothScroll();
    initReveals();
}

document.addEventListener('DOMContentLoaded', initMotion);
document.addEventListener('livewire:navigated', initMotion);
