<script setup>
import { ref, onMounted, onUnmounted, nextTick } from 'vue';

const props = defineProps({
    /** Element whose bounds define the interactive / drawable area */
    container: {
        type: Object,
        default: null,
    },
});

const canvasRef = ref(null);
let ctx = null;
let raf = 0;
let w = 0;
let h = 0;
let dpr = 1;

const particles = [];
const attractors = [];
const repulsors = [];
const stars = [];

const MAX_PARTICLES = 2800;
const MAX_ATTRACTORS = 6;
const MAX_REPULSORS = 6;
let mx = 0;
let my = 0;
let lastSpawn = 0;
let moveThrottle = 0;
let pointerInHero = false;

function rand(a, b) {
    return a + Math.random() * (b - a);
}

function initStars() {
    stars.length = 0;
    const n = Math.floor((w * h) / 18000);
    for (let i = 0; i < n; i++) {
        stars.push({
            x: Math.random() * w,
            y: Math.random() * h,
            r: Math.random() * 1.2 + 0.2,
            a: rand(0.15, 0.55),
        });
    }
}

function seedOpeningBlob() {
    const cx = w * 0.72;
    const cy = h * 0.42;
    const count = 900;
    for (let i = 0; i < count; i++) {
        const ang = Math.random() * Math.PI * 2;
        const rad = Math.pow(Math.random(), 0.45) * Math.min(w, h) * 0.28;
        particles.push(makeParticle(cx + Math.cos(ang) * rad, cy + Math.sin(ang) * rad * 0.9, true));
    }
}

function makeParticle(x, y, fromBlob = false) {
    const speed = fromBlob ? rand(0.15, 1.2) : rand(0.8, 3.2);
    const ang = Math.random() * Math.PI * 2;
    return {
        x,
        y,
        px: x,
        py: y,
        vx: Math.cos(ang) * speed * (fromBlob ? 0.4 : 1),
        vy: Math.sin(ang) * speed * (fromBlob ? 0.4 : 1),
        life: fromBlob ? rand(0.7, 1) : 1,
        decay: fromBlob ? rand(0.0008, 0.002) : rand(0.003, 0.009),
        size: fromBlob ? rand(0.6, 2.2) : rand(0.8, 2.8),
        hue: rand(38, 52),
        sat: rand(88, 100),
    };
}

function spawnBurst(x, y, n = 5) {
    for (let i = 0; i < n; i++) {
        if (particles.length >= MAX_PARTICLES) break;
        particles.push(makeParticle(x + rand(-4, 4), y + rand(-4, 4), false));
    }
}

function pushForce(arr, maxLen, x, y, kind) {
    arr.push({ x, y, kind, t: 1 });
    while (arr.length > maxLen) arr.shift();
}

function resize() {
    const el = props.container;
    const canvas = canvasRef.value;
    if (!el || !canvas) return;
    const rect = el.getBoundingClientRect();
    dpr = Math.min(window.devicePixelRatio || 1, 2);
    w = Math.max(1, Math.floor(rect.width));
    h = Math.max(1, Math.floor(rect.height));
    canvas.width = Math.floor(w * dpr);
    canvas.height = Math.floor(h * dpr);
    canvas.style.width = `${w}px`;
    canvas.style.height = `${h}px`;
    ctx = canvas.getContext('2d', { alpha: true });
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    initStars();
}

function clientToLocal(clientX, clientY) {
    const el = props.container;
    if (!el) return { x: 0, y: 0 };
    const rect = el.getBoundingClientRect();
    return {
        x: clientX - rect.left,
        y: clientY - rect.top,
    };
}

function isInside(clientX, clientY) {
    const el = props.container;
    if (!el) return false;
    const rect = el.getBoundingClientRect();
    return (
        clientX >= rect.left &&
        clientX <= rect.right &&
        clientY >= rect.top &&
        clientY <= rect.bottom
    );
}

function onMove(e) {
    pointerInHero = isInside(e.clientX, e.clientY);
    if (!pointerInHero) return;
    const { x, y } = clientToLocal(e.clientX, e.clientY);
    mx = x;
    my = y;
    moveThrottle++;
    if (moveThrottle % 2 !== 0) return;
    const now = performance.now();
    if (now - lastSpawn < 12) return;
    lastSpawn = now;
    spawnBurst(x, y, 6);
}

function onClick(e) {
    if (!isInside(e.clientX, e.clientY)) return;
    const t = e.target;
    if (t.closest && t.closest('button, a, input, textarea, select, [role="button"]')) return;
    const { x, y } = clientToLocal(e.clientX, e.clientY);
    pushForce(attractors, MAX_ATTRACTORS, x, y, 'attract');
}

function onKeyDown(e) {
    if (e.code !== 'Space') return;
    const ae = document.activeElement;
    if (ae && (ae.tagName === 'INPUT' || ae.tagName === 'TEXTAREA' || ae.isContentEditable)) return;
    if (!props.container) return;
    const rect = props.container.getBoundingClientRect();
    const vis =
        rect.bottom > 0 &&
        rect.top < window.innerHeight;
    if (!vis || !pointerInHero) return;
    e.preventDefault();
    pushForce(repulsors, MAX_REPULSORS, mx, my, 'repulse');
}

function applyForces(p) {
    for (const a of attractors) {
        const dx = a.x - p.x;
        const dy = a.y - p.y;
        const d2 = dx * dx + dy * dy + 80;
        const f = (420 * a.t) / d2;
        p.vx += dx * f * 0.02;
        p.vy += dy * f * 0.02;
    }
    for (const r of repulsors) {
        const dx = p.x - r.x;
        const dy = p.y - r.y;
        const d2 = dx * dx + dy * dy + 120;
        const f = (380 * r.t) / d2;
        p.vx += dx * f * 0.018;
        p.vy += dy * f * 0.018;
    }
}

function tick() {
    if (!ctx || !w) {
        raf = requestAnimationFrame(tick);
        return;
    }

    for (let i = attractors.length - 1; i >= 0; i--) {
        attractors[i].t *= 0.992;
        if (attractors[i].t < 0.02) attractors.splice(i, 1);
    }
    for (let i = repulsors.length - 1; i >= 0; i--) {
        repulsors[i].t *= 0.992;
        if (repulsors[i].t < 0.02) repulsors.splice(i, 1);
    }

    ctx.fillStyle = 'rgba(2, 3, 6, 0.22)';
    ctx.fillRect(0, 0, w, h);

    ctx.globalCompositeOperation = 'lighter';

    for (const s of stars) {
        ctx.fillStyle = `rgba(255,255,255,${s.a})`;
        ctx.beginPath();
        ctx.arc(s.x, s.y, s.r, 0, Math.PI * 2);
        ctx.fill();
    }

    for (let i = particles.length - 1; i >= 0; i--) {
        const p = particles[i];
        p.px = p.x;
        p.py = p.y;
        applyForces(p);
        p.vx *= 0.985;
        p.vy *= 0.985;
        p.x += p.vx;
        p.y += p.vy;
        p.life -= p.decay;

        if (p.life <= 0 || p.x < -40 || p.y < -40 || p.x > w + 40 || p.y > h + 40) {
            particles.splice(i, 1);
            continue;
        }

        const alpha = Math.min(1, p.life) * 0.85;
        ctx.strokeStyle = `hsla(${p.hue}, ${p.sat}%, 58%, ${alpha * 0.12})`;
        ctx.lineWidth = 0.6;
        ctx.beginPath();
        ctx.moveTo(p.px, p.py);
        ctx.lineTo(p.x, p.y);
        ctx.stroke();

        const r = p.size * (0.4 + p.life * 0.6);
        const g = ctx.createRadialGradient(p.x, p.y, 0, p.x, p.y, r * 3);
        g.addColorStop(0, `hsla(${p.hue}, ${p.sat}%, 92%, ${alpha * 0.9})`);
        g.addColorStop(0.35, `hsla(${p.hue - 4}, ${p.sat}%, 62%, ${alpha * 0.45})`);
        g.addColorStop(1, 'hsla(30, 90%, 50%, 0)');
        ctx.fillStyle = g;
        ctx.beginPath();
        ctx.arc(p.x, p.y, r * 3, 0, Math.PI * 2);
        ctx.fill();
    }

    ctx.globalCompositeOperation = 'source-over';
    raf = requestAnimationFrame(tick);
}

let ro = null;

function onBlur() {
    pointerInHero = false;
}

function bind() {
    window.addEventListener('mousemove', onMove);
    window.addEventListener('click', onClick, true);
    window.addEventListener('keydown', onKeyDown, { passive: false });
    window.addEventListener('blur', onBlur);
}

function unbind() {
    window.removeEventListener('mousemove', onMove);
    window.removeEventListener('click', onClick, true);
    window.removeEventListener('keydown', onKeyDown);
    window.removeEventListener('blur', onBlur);
}

onMounted(() => {
    bind();
    ro = new ResizeObserver(() => resize());
    const boot = () => {
        resize();
        if (w && h && particles.length === 0) {
            seedOpeningBlob();
        }
    };
    nextTick(() => {
        boot();
        nextTick(boot);
        if (props.container) {
            ro.observe(props.container);
        }
        raf = requestAnimationFrame(tick);
    });
});

onUnmounted(() => {
    unbind();
    cancelAnimationFrame(raf);
    if (ro) {
        if (props.container) {
            ro.unobserve(props.container);
        }
        ro.disconnect();
    }
    ro = null;
});
</script>

<template>
    <canvas
        ref="canvasRef"
        class="absolute inset-0 block h-full w-full touch-none select-none"
        aria-hidden="true"
    />
</template>
