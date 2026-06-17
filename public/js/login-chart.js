/**
 * login-chart.js
 * Gráfico 3D isométrico animado — Canvas puro, sem dependências
 * Recriação fiel do Modern3DChartBackground.tsx do Figma
 */
(function () {
    const canvas = document.getElementById('chart3d');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');

    // Dados (mesmos do Figma)
    const DATA = [30, 50, 40, 80, 60, 110, 90, 140, 120, 190, 160, 240];

    // Timing (mesmos do Figma)
    const DURATION_DRAW  = 4500;
    const DURATION_PAUSE = 1500;
    const CYCLE          = DURATION_DRAW + DURATION_PAUSE;

    // Paleta
    const C = {
        pillarTop:        'rgba(255,255,255,0.95)',
        pillarTopStroke:  'rgba(203,213,225,0.8)',
        pillarFront:      'rgba(248,250,252,0.9)',
        pillarFrontStroke:'rgba(203,213,225,0.5)',
        pillarRight:      'rgba(241,245,249,0.9)',
        pillarRightStroke:'rgba(203,213,225,0.4)',
        pillarHoverTop:   '#22c55e',
        pillarHoverFront: '#16a34a',
        pillarHoverRight: '#15803d',
        shadow:           'rgba(100,116,139,0.25)',
        floor:            'rgba(255,255,255,0.5)',
        floorBorder:      'rgba(255,255,255,0.8)',
        gridLine:         'rgba(0,0,0,0.03)',
        connector:        'rgba(0,0,0,0.08)',
        wallLine:         '#94a3b8',
        wallArea:         'rgba(148,163,184,0.18)',
        dot:              '#22c55e',
        dotFill:          '#fff',
    };

    let W, H, startTime = null, hoveredBar = -1;

    // Projeção isométrica (rotateX:60 rotateZ:-45 do Figma)
    function iso(x, y, z) {
        const a = Math.PI / 4;
        return {
            x: (x - y) * Math.cos(a),
            y: (x + y) * Math.sin(a) * 0.5 - z * 0.866
        };
    }

    function resize() {
        W = canvas.offsetWidth;
        H = canvas.offsetHeight;
        canvas.width  = W * devicePixelRatio;
        canvas.height = H * devicePixelRatio;
        ctx.scale(devicePixelRatio, devicePixelRatio);
    }

    function getBars(scaleX, scaleH) {
        const cols = DATA.length;
        return DATA.map((h, i) => ({
            x:    (i * (800 / cols) + (800 / (cols * 2))) * scaleX,
            h:    h * scaleH,
            isUp: i === 0 || h >= DATA[i - 1],
        }));
    }

    function easeOut(t) { return 1 - Math.pow(1 - t, 3); }

    function drawPillar(cx, cy, barW, barH, progress, hovered, originX, originY) {
        if (progress <= 0) return;
        const h  = barH * Math.min(1, easeOut(progress));
        const hw = barW / 2;
        const ox = originX + cx;
        const oy = originY + cy;

        const mapPts = arr => arr.map(p => ({ x: ox + p.x, y: oy + p.y }));

        const topP   = mapPts([iso(-hw,-hw,h), iso(hw,-hw,h), iso(hw,hw,h),  iso(-hw,hw,h)]);
        const frontP = mapPts([iso(-hw,hw,0),  iso(hw,hw,0),  iso(hw,hw,h),  iso(-hw,hw,h)]);
        const rightP = mapPts([iso(hw,-hw,0),  iso(hw,hw,0),  iso(hw,hw,h),  iso(hw,-hw,h)]);

        // Sombra
        ctx.save();
        ctx.globalAlpha = 0.3 * Math.min(1, progress * 2);
        ctx.filter = 'blur(6px)';
        ctx.fillStyle = C.shadow;
        const shadowP = mapPts([iso(-hw,-hw,0), iso(hw,-hw,0), iso(hw,hw,0), iso(-hw,hw,0)]);
        ctx.beginPath();
        shadowP.forEach((p, i) => i === 0 ? ctx.moveTo(p.x, p.y+4) : ctx.lineTo(p.x, p.y+4));
        ctx.closePath();
        ctx.fill();
        ctx.filter = 'none';
        ctx.restore();

        function face(pts, fill, stroke) {
            ctx.beginPath();
            pts.forEach((p, i) => i === 0 ? ctx.moveTo(p.x, p.y) : ctx.lineTo(p.x, p.y));
            ctx.closePath();
            ctx.fillStyle = fill; ctx.fill();
            ctx.strokeStyle = stroke; ctx.lineWidth = 1; ctx.stroke();
        }

        if (hovered) {
            face(frontP, C.pillarHoverFront, '#15803d');
            face(rightP, C.pillarHoverRight, '#14532d');
            face(topP,   C.pillarHoverTop,   '#4ade80');
        } else {
            face(frontP, C.pillarFront, C.pillarFrontStroke);
            face(rightP, C.pillarRight, C.pillarRightStroke);
            face(topP,   C.pillarTop,   C.pillarTopStroke);
            // Reflexo
            ctx.save();
            ctx.globalAlpha = 0.4;
            const g = ctx.createLinearGradient(topP[0].x, topP[0].y, topP[2].x, topP[2].y);
            g.addColorStop(0, 'rgba(255,255,255,0.8)');
            g.addColorStop(1, 'rgba(255,255,255,0)');
            ctx.beginPath();
            topP.forEach((p, i) => i === 0 ? ctx.moveTo(p.x, p.y) : ctx.lineTo(p.x, p.y));
            ctx.closePath();
            ctx.fillStyle = g; ctx.fill();
            ctx.restore();
        }
    }

    function drawWallChart(bars, drawProgress, originX, originY, floorD, scaleX) {
        const pts = bars.map(b => {
            const p = iso(b.x - 400 * scaleX, -floorD, b.h);
            return { x: originX + p.x, y: originY + p.y };
        });
        const visible = Math.max(1, Math.floor(pts.length * Math.min(1, drawProgress)));

        if (visible >= 2) {
            const bL = iso(bars[0].x - 400*scaleX, -floorD, 0);
            const bR = iso(bars[visible-1].x - 400*scaleX, -floorD, 0);
            ctx.beginPath();
            ctx.moveTo(pts[0].x, pts[0].y);
            for (let i = 1; i < visible; i++) ctx.lineTo(pts[i].x, pts[i].y);
            ctx.lineTo(originX+bR.x, originY+bR.y);
            ctx.lineTo(originX+bL.x, originY+bL.y);
            ctx.closePath();
            ctx.fillStyle = C.wallArea;
            ctx.fill();

            ctx.beginPath();
            ctx.moveTo(pts[0].x, pts[0].y);
            for (let i = 1; i < visible; i++) ctx.lineTo(pts[i].x, pts[i].y);
            ctx.strokeStyle = C.wallLine; ctx.lineWidth = 2;
            ctx.lineCap = 'round'; ctx.lineJoin = 'round';
            ctx.stroke();
        }

        // Pontos nos picos
        bars.forEach((b, i) => {
            if (i === 0 || i >= visible) return;
            const isPeak = i > 0 && i < bars.length-1 && b.h > bars[i-1].h && b.h > bars[i+1].h;
            if (!isPeak) return;
            const t = i / (bars.length - 1);
            const dp = Math.max(0, (drawProgress - t) / (1 / bars.length));
            const scale = Math.min(1, easeOut(dp) * 1.3);
            if (scale <= 0) return;
            ctx.save();
            ctx.translate(pts[i].x, pts[i].y);
            ctx.scale(scale, scale);
            ctx.beginPath(); ctx.arc(0, 0, 4.5, 0, Math.PI*2);
            ctx.fillStyle = C.dotFill; ctx.fill();
            ctx.strokeStyle = C.dot; ctx.lineWidth = 2; ctx.stroke();
            ctx.restore();
        });
    }

    function render(ts) {
        if (!startTime) startTime = ts;
        const elapsed     = (ts - startTime) % CYCLE;
        const drawProgress = Math.min(1, elapsed / DURATION_DRAW);

        ctx.clearRect(0, 0, W, H);

        const scaleX  = Math.min(1, W / 900);
        const scaleH  = Math.min(1, H / 500) * 0.85;
        const bars    = getBars(scaleX, scaleH);
        const floorW  = 800 * scaleX;
        const floorD  = 250 * scaleX;
        const originX = W * 0.5;
        const originY = H * 0.62;

        // Chão
        const floorPts = [iso(0,0,0), iso(floorW,0,0), iso(floorW,floorD,0), iso(0,floorD,0)]
            .map(p => ({ x: originX+p.x, y: originY+p.y }));
        ctx.beginPath();
        floorPts.forEach((p,i) => i===0 ? ctx.moveTo(p.x,p.y) : ctx.lineTo(p.x,p.y));
        ctx.closePath();
        ctx.fillStyle = C.floor; ctx.fill();
        ctx.strokeStyle = C.floorBorder; ctx.lineWidth = 1.5; ctx.stroke();

        // Grade
        ctx.strokeStyle = C.gridLine; ctx.lineWidth = 1;
        bars.forEach(b => {
            const a = iso(b.x,0,0), c = iso(b.x,floorD,0);
            ctx.beginPath();
            ctx.moveTo(originX+a.x, originY+a.y);
            ctx.lineTo(originX+c.x, originY+c.y);
            ctx.stroke();
        });

        // Conectores pontilhados
        bars.forEach((b, i) => {
            const t = i / (bars.length - 1);
            const cp = Math.max(0, (drawProgress - t*0.85) * 3);
            if (cp <= 0) return;
            const a = iso(b.x, floorD/2, 0);
            const c = iso(b.x, floorD/2, b.h * Math.min(1, cp));
            ctx.save();
            ctx.setLineDash([4,4]);
            ctx.strokeStyle = C.connector; ctx.lineWidth = 1;
            ctx.globalAlpha = Math.min(1, cp);
            ctx.beginPath();
            ctx.moveTo(originX+a.x, originY+a.y);
            ctx.lineTo(originX+c.x, originY+c.y);
            ctx.stroke();
            ctx.setLineDash([]);
            ctx.restore();
        });

        // Parede de fundo
        drawWallChart(bars, drawProgress, originX, originY, floorD, scaleX);

        // Pilares
        const barW = 28 * scaleX;
        bars.forEach((bar, i) => {
            const t = i / (bars.length - 1);
            const progress = Math.max(0, (drawProgress - t*0.7) * 2.5);
            drawPillar(bar.x, floorD/2, barW, bar.h, progress, hoveredBar===i, originX, originY);
        });

        // Respiração suave (replicando o animate do Figma)
        const breathe = Math.sin(ts / 10000) * 0.008;
        canvas.style.transform = `perspective(1200px) rotateX(${60 + breathe*10}deg) rotateZ(${-45 + breathe*5}deg) scale(0.72)`;

        requestAnimationFrame(render);
    }

    // Hover interativo
    canvas.addEventListener('mousemove', function(e) {
        const rect = canvas.getBoundingClientRect();
        const mx = e.clientX - rect.left;
        const my = e.clientY - rect.top;
        const scaleX  = Math.min(1, W/900);
        const scaleH  = Math.min(1, H/500) * 0.85;
        const bars    = getBars(scaleX, scaleH);
        const barW    = 28 * scaleX;
        const originX = W * 0.5;
        const originY = H * 0.62;
        const floorD  = 250 * scaleX;

        hoveredBar = -1;
        bars.forEach((bar, i) => {
            const top  = iso(bar.x, floorD/2, bar.h);
            const px   = originX + top.x;
            const py   = originY + top.y;
            if (Math.sqrt((mx-px)**2 + (my-py)**2) < barW*1.8) hoveredBar = i;
        });
        canvas.style.cursor = hoveredBar >= 0 ? 'pointer' : 'default';
    });

    canvas.addEventListener('mouseleave', () => { hoveredBar = -1; });

    // Init
    resize();
    window.addEventListener('resize', () => { resize(); startTime = null; });
    canvas.style.transformOrigin = 'center 70%';
    canvas.style.transform = 'perspective(1200px) rotateX(60deg) rotateZ(-45deg) scale(0.72)';
    canvas.style.transition = 'transform 0.1s ease';

    requestAnimationFrame(render);
})();