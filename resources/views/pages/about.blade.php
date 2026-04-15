@extends('layouts.app')

@section('title', 'About Us - EventBook')

@section('content')
<div class="relative min-h-screen overflow-hidden bg-slate-950 text-white">

    <!-- 🌌 Animated Background Glow -->
    <div class="absolute inset-0 -z-10">
        <div class="glow glow1"></div>
        <div class="glow glow2"></div>
        <div class="glow glow3"></div>
    </div>

    <div class="container mx-auto px-6 md:px-12 lg:px-20 py-24">

        <!-- 🔥 HERO -->
        <div class="text-center mb-20">
            <h1 class="hero-title mb-6">About EventBook</h1>
            <p class="text-slate-300 text-lg max-w-2xl mx-auto">
                Experience the elegance of premium Jain religious events with seamless technology.
            </p>
        </div>

        <!-- SECTIONS -->
        <div class="max-w-5xl mx-auto space-y-10">

        <div class="tagline-box mt-10">
    <p class="tagline-main">
        “A digital platform dedicated to Jain values, events, and community service.”
    </p>
    <div class="tagline-sub">
        <span>🛕 Technology in service of Dharma</span>
        <span>💻 Digital India possible through Digital Jains</span>
    </div>
</div>

            <!-- Our Purpose -->
<div class="glass-card purpose-card">
    <h2 class="section-title">
        <i class="fas fa-bullseye text-amber-400"></i> Our Purpose
    </h2>

    <div class="purpose-grid mt-8">

        <div class="purpose-item purpose-highlight">
            <div class="purpose-icon">📅</div>
            <p>To simplify planning and coordination of Jain religious & cultural events</p>
        </div>

        <div class="purpose-item purpose-highlight">
            <div class="purpose-icon">🛕</div>
            <p>To support <strong>धर्म प्रभावना</strong> through structured, respectful event management</p>
        </div>

        <div class="purpose-item purpose-highlight">
            <div class="purpose-icon">🤝</div>
            <p>To connect organizers, speakers, volunteers, and families on one unified platform</p>
        </div>

        <div class="purpose-item purpose-highlight">
            <div class="purpose-icon">🙏</div>
            <p>To ensure events are conducted with discipline, dignity, and devotion</p>
        </div>

        <div class="purpose-item purpose-highlight">
            <div class="purpose-icon">🇮🇳</div>
            <p>Supporting Jain contribution to <strong>Viksit Bharat 2047</strong> vision</p>
        </div>

    </div>
</div>




            <!-- Our Values -->
<div class="glass-card">
    <h2 class="section-title">
        <i class="fas fa-dove text-amber-400"></i> Our Values
    </h2>

    <div class="grid md:grid-cols-2 gap-6 mt-6">

        <div class="value-item purpose-highlight">
            <span class="value-emoji">🕊️</span>
            <div>
                <h3>अहिंसा</h3>
                <p>Non-violence in thought & action</p>
            </div>
        </div>

        <div class="value-item purpose-highlight">
            <span class="value-emoji">🤍</span>
            <div>
                <h3>सत्य और पारदर्शिता</h3>
                <p>Truth & Transparency</p>
            </div>
        </div>

        <div class="value-item purpose-highlight">
            <span class="value-emoji">🙏</span>
            <div>
                <h3>सेवा भाव</h3>
                <p>Spirit of Service</p>
            </div>
        </div>

        <div class="value-item purpose-highlight">
            <span class="value-emoji">📜</span>
            <div>
                <h3>परंपरा का सम्मान</h3>
                <p>Respect for Tradition</p>
            </div>
        </div>

        <div class="value-item  purpose-highlight md:col-span-2">
            <span class="value-emoji">🤝</span>
            <div>
                <h3>समुदाय सहयोग</h3>
                <p>Community Collaboration</p>
            </div>
        </div>

    </div>
</div>

            <!-- Who This Platform Is For -->
<div class="glass-card">
    <h2 class="section-title">
        <i class="fas fa-users text-emerald-400"></i> Who This Platform Is For
    </h2>

    <div class="audience-grid mt-8">

        <div class="audience-item purpose-highlight">
            <div class="audience-icon bg-amber-500/20 text-amber-400">
                <i class="fas fa-landmark"></i>
            </div>
            <p>Jain Trusts & Organizations</p>
        </div>

        <div class="audience-item purpose-highlight">
            <div class="audience-icon bg-blue-500/20 text-blue-400">
                <i class="fas fa-calendar-check"></i>
            </div>
            <p>Event Organizers & Committees</p>
        </div>

        <div class="audience-item purpose-highlight">
            <div class="audience-icon bg-pink-500/20 text-pink-400">
                <i class="fas fa-home"></i>
            </div>
            <p>Parents & Families</p>
        </div>

        <div class="audience-item purpose-highlight">
            <div class="audience-icon bg-purple-500/20 text-purple-400">
                <i class="fas fa-child"></i>
            </div>
            <p>Children & Youth Groups</p>
        </div>

        <div class="audience-item purpose-highlight">
            <div class="audience-icon bg-emerald-500/20 text-emerald-400">
                <i class="fas fa-hands-helping"></i>
            </div>
            <p>Volunteers & Seva Teams</p>
        </div>

    </div>
</div>


            <!-- Team -->
            <div class="glass-card">
                <h2 class="section-title"><i class="fas fa-users text-sky-400"></i> Our Team</h2>
                <p class="section-text">
                    Behind EventBook is a passionate team of professionals dedicated to excellence...
                </p>
            </div>
        </div>

        <!-- CTA -->
        <div class="text-center mt-20">
            <a href="/" class="submit-btn">Browse Events</a>
        </div>

    </div>
</div>

<style>

/* 🌌 Glow Background */
.glow {
    position:absolute;
    width:600px;
    height:600px;
    border-radius:50%;
    filter: blur(120px);
    opacity:.25;
    animation: floatGlow 12s infinite ease-in-out alternate;
}
.glow1 { background:#f59e0b; top:-100px; left:-100px; }
.glow2 { background:#ec4899; bottom:-120px; right:-120px; animation-delay:3s; }
.glow3 { background:#8b5cf6; top:40%; left:30%; animation-delay:6s; }
@keyframes floatGlow { from{transform:translateY(-40px);} to{transform:translateY(40px);} }

/* 🌈 Hero */
.hero-title {
    font-size: clamp(48px,6vw,72px);
    font-weight:900;
    background: linear-gradient(90deg,#f59e0b,#ec4899,#8b5cf6,#f59e0b);
    background-size:400%;
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
    animation: gradientMove 8s linear infinite;
}
@keyframes gradientMove { 0%{background-position:0%} 100%{background-position:400%} }

/* 🧊 Glass Cards */
.glass-card {
    background: rgba(15,23,42,0.55);
    backdrop-filter: blur(22px);
    border-radius: 28px;
    padding: 42px;
    border: 1px solid rgba(255,255,255,0.08);
    box-shadow: 0 40px 80px rgba(0,0,0,0.7);
    transition:.4s;
}
.glass-card:hover { transform:translateY(-8px) scale(1.02); }

/* Section Text */
.section-title {
    font-size:26px;
    font-weight:700;
    margin-bottom:14px;
    display:flex;
    gap:10px;
    align-items:center;
}
.section-text { color:#cbd5e1; line-height:1.8; }

/* Features */
.feature-item {
    display:flex;
    gap:16px;
    align-items:flex-start;
}
.feature-icon {
    width:42px;
    height:42px;
    display:flex;
    align-items:center;
    justify-content:center;
    border-radius:12px;
}

/* Button */
.submit-btn {
    padding:16px 40px;
    border-radius:14px;
    background: linear-gradient(90deg,#f59e0b,#ec4899);
    font-weight:bold;
    transition:.4s;
}
.submit-btn:hover { transform:scale(1.08); }

/* Our Values */

.value-item {
    display:flex;
    gap:18px;
    align-items:flex-start;
    padding:18px;
    border-radius:16px;
    background: rgba(30,41,59,0.5);
    transition:.3s;
}
.value-item:hover {
    transform:translateY(-6px);
    background: rgba(51,65,85,0.6);
}

.value-emoji {
    font-size:28px;
}

.value-item h3 {
    font-size:18px;
    font-weight:600;
    color:white;
}

.value-item p {
    color:#cbd5e1;
    font-size:14px;
}

/* Our Purpose */

.purpose-card {
    background: linear-gradient(135deg, rgba(15,23,42,0.7), rgba(30,41,59,0.6));
    border: 1px solid rgba(245,158,11,0.2);
}

.purpose-grid {
    display: grid;
    gap: 22px;
}

.purpose-item {
    display: flex;
    gap: 18px;
    align-items: center;
    padding: 18px 22px;
    border-radius: 18px;
    background: rgba(30,41,59,0.55);
    transition: .35s;
}

.purpose-item:hover {
    transform: translateY(-6px);
    background: rgba(51,65,85,0.65);
    box-shadow: 0 15px 35px rgba(0,0,0,0.5);
}

.purpose-icon {
    font-size: 26px;
}

.purpose-item p {
    color: #e2e8f0;
    line-height: 1.7;
}

.purpose-highlight {
    border: 1px solid rgba(245,158,11,0.4);
    background: linear-gradient(90deg, rgba(245,158,11,0.1), rgba(236,72,153,0.08));
}

/*why choose us*/
.audience-grid {
    display:grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap:22px;
}

.audience-item {
    display:flex;
    align-items:center;
    gap:18px;
    padding:18px 22px;
    border-radius:18px;
    /*background: rgba(30,41,59,0.55);*/
    transition:.35s;
}

.audience-item:hover {
    transform:translateY(-6px) scale(1.03);
    background: rgba(51,65,85,0.65);
    box-shadow:0 15px 35px rgba(0,0,0,0.5);
}

.audience-icon {
    width:44px;
    height:44px;
    display:flex;
    align-items:center;
    justify-content:center;
    border-radius:12px;
    font-size:18px;
}

.audience-item p {
    color:#e2e8f0;
    font-weight:500;
}

/* Tagline */

.tagline-box {
    margin: 40px auto 0;
    max-width: 850px;
    padding: 28px 32px;
    border-radius: 22px;
    background: linear-gradient(135deg, rgba(245,158,11,0.08), rgba(139,92,246,0.08));
    border: 1px solid rgba(245,158,11,0.25);
    backdrop-filter: blur(10px);
    text-align: center;
    box-shadow: 0 20px 50px rgba(0,0,0,0.4);
}

.tagline-main {
    font-size: 20px;
    font-weight: 600;
    color: #f8fafc;
    margin-bottom: 16px;
    line-height: 1.6;
}

.tagline-sub {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 18px;
    font-size: 14px;
    color: #fbbf24;
    font-weight: 500;
}

.tagline-box {
    margin-top:42px;
    padding:22px 32px;
    border-radius:18px;
    background: rgba(30,41,59,0.55);
    border:1px solid rgba(255,255,255,0.08);
}

.tagline-main {
    font-size:24px;
    font-weight:700;
    margin-bottom:12px;
}

.tagline-sub {
    display:flex;
    gap:24px;
    font-size:16px;
    color:#a0aec0;
}

/* Ruff Code */

.tagline-box {
    position: relative;
    margin: 40px auto 0;
    max-width: 850px;
    padding: 30px 34px;
    border-radius: 22px;
    background: linear-gradient(135deg, rgba(245,158,11,0.08), rgba(139,92,246,0.08));
    border: 1px solid rgba(245,158,11,0.25);
    backdrop-filter: blur(10px);
    text-align: center;
    box-shadow: 0 20px 50px rgba(0,0,0,0.4);
    overflow: hidden;
}

/* 🌸 Spiritual Watermark */
.spiritual-bg {
    position: absolute;
    inset: 0;
    background: url('https://upload.wikimedia.org/wikipedia/commons/thumb/3/3c/Om_symbol.svg/512px-Om_symbol.svg.png') center/280px no-repeat;
    opacity: 0.04;
    filter: blur(1px);
    pointer-events: none;
}

/* Text stays above */
.tagline-main, .tagline-sub {
    position: relative;
    z-index: 2;
}

.tagline-main {
    font-size: 20px;
    font-weight: 600;
    color: #f8fafc;
    margin-bottom: 16px;
    line-height: 1.6;
}

.tagline-sub {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 18px;
    font-size: 14px;
    color: #fbbf24;
    font-weight: 500;
}

/* Aimation css for about us page */

/* 🌸 Animated Spiritual Watermark */
.spiritual-bg {
    position: absolute;
    inset: 0;
    background: url('https://upload.wikimedia.org/wikipedia/commons/thumb/3/3c/Om_symbol.svg/512px-Om_symbol.svg.png') center/260px no-repeat;
    opacity: 0.05;
    pointer-events: none;
    animation: spiritualFloat 14s ease-in-out infinite alternate,
               spiritualGlow 6s ease-in-out infinite;
}

/* Floating motion */
@keyframes spiritualFloat {
    0%   { transform: translateY(-12px) scale(1); }
    100% { transform: translateY(12px) scale(1.05); }
}

/* Gentle glow pulse */
@keyframes spiritualGlow {
    0%,100% { filter: blur(1px) brightness(1); }
    50%     { filter: blur(2px) brightness(1.3); }
}

.spiritual-bg {
    position: absolute;
    inset: 0;
    background: url('https://upload.wikimedia.org/wikipedia/commons/thumb/3/3c/Om_symbol.svg/512px-Om_symbol.svg.png') center/260px no-repeat;
    opacity: 0.05;
    pointer-events: none;
    will-change: transform, filter;
    animation: spiritualFloat 14s ease-in-out infinite alternate,
               spiritualGlow 6s ease-in-out infinite;
}


</style>

<script>
window.addEventListener('scroll', () => {
    const symbol = document.querySelector('.spiritual-bg');
    if (!symbol) return;

    const scrollY = window.scrollY;
    const move = scrollY * 0.02;      // movement strength
    const glow = 1 + (scrollY / 2000); // brightness boost

    symbol.style.transform = `translateY(${move}px) scale(${1 + scrollY/5000})`;
    symbol.style.filter = `blur(1.5px) brightness(${glow})`;
});
</script>

@endsection
