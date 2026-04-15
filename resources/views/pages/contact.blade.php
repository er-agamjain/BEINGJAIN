@extends('layouts.app')

@section('title', 'Contact Us - EventBook')

@section('content')
<div class="relative min-h-screen overflow-hidden bg-slate-950 text-white">

    <!-- 🌌 Animated Luxury Background -->
    <div class="absolute inset-0 -z-10">
        <div class="glow glow1"></div>
        <div class="glow glow2"></div>
        <div class="glow glow3"></div>
    </div>

    <div class="container mx-auto px-6 md:px-12 lg:px-20 py-28">

        <!-- 🔥 HERO -->
        <div class="text-center mb-24">
            <h1 class="hero-title mb-6">Let’s Connect</h1>
            <p class="text-slate-300 text-lg max-w-2xl mx-auto">
                Have questions about our premium events? Our team is ready to assist you.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-14">

            <!-- 💎 FORM -->
            <div class="lg:col-span-3">
                <div class="glass-card">
                    <h2 class="text-3xl font-bold mb-10 text-center">Send a Message</h2>

                    <form action="{{ route('contact.submit') }}" method="POST" class="space-y-8">
                        @csrf

                        <div class="floating-group highlight">
                            <input type="text" name="name" value="{{ old('name') }}" required placeholder=" ">
                            <label>Full Name</label>
                        </div>

                        <div class="grid md:grid-cols-2 gap-8">
                            <div class="floating-group">
                                <input type="text" name="mobile_number" value="{{ old('mobile_number') }}" required placeholder=" ">
                                <label>Mobile Number</label>
                            </div>
                            <div class="floating-group">
                                <input type="email" name="email" value="{{ old('email') }}" required placeholder=" ">
                                <label>Email Address</label>
                            </div>
                        </div>

                        <div class="floating-group">
                            <input type="text" name="city" value="{{ old('city') }}" required placeholder=" ">
                            <label>City</label>
                        </div>

                        <div class="floating-group">
                            <textarea name="message" rows="5" required placeholder=" ">{{ old('message') }}</textarea>
                            <label>Your Message</label>
                        </div>

                        <div class="flex gap-6 pt-4">
                            <button type="submit" class="submit-btn">Send Message</button>
                            <a href="/" class="cancel-btn">Back</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ✨ SIDEBAR -->
            <div class="lg:col-span-2 space-y-10">
                <div class="info-card">24/7 Customer Support</div>
                <div class="info-card">Average Reply Time: 2 Hours</div>
                <div class="info-card">Serving All Major Cities</div>
            </div>

        </div>
    </div>
</div>

<style>

/* 🌌 Background Glow */
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

/* 🌈 Gradient Hero */
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

/* 🧊 Glass Card */
.glass-card {
    background: rgba(15,23,42,0.55);
    backdrop-filter: blur(22px);
    border-radius: 28px;
    padding: 48px;
    border: 1px solid rgba(255,255,255,0.08);
    box-shadow: 0 40px 80px rgba(0,0,0,0.7);
    transition: transform .4s ease;
    border: 1px solid rgba(245,158,11,0.4);
}
.glass-card:hover { transform: rotateX(3deg) rotateY(-3deg) scale(1.02); }

/* ✨ Floating Inputs */
.floating-group { position:relative; }
.floating-group input,
.floating-group textarea {
    width:100%;
    padding:22px 16px 8px;
    background:rgba(30,41,59,0.6);
    /*border:2px solid #334155;*/
    border: 2px solid rgba(245,158,11,0.4);
    border-radius:14px;
    color:white;
    outline:none;
    transition:.3s;
}
.floating-group label {
    position:absolute;
    left:16px;
    top:20px;
    color:#94a3b8;
    transition:.3s;
}
.floating-group input:focus,
.floating-group textarea:focus {
    border-color:#f59e0b;
    box-shadow:0 0 20px rgba(245,158,11,.35);
    transform:scale(1.02);
}
.floating-group input:focus+label,
.floating-group input:not(:placeholder-shown)+label,
.floating-group textarea:focus+label,
.floating-group textarea:not(:placeholder-shown)+label {
    top:6px;
    font-size:12px;
    color:#fbbf24;
}

/* 🔘 Buttons */
.submit-btn {
    flex:1;
    padding:16px;
    border-radius:14px;
    background: linear-gradient(90deg,#f59e0b,#ec4899);
    border: 2px solid rgba(245,158,11,0.4);
    font-weight:bold;
    position:relative;
    overflow:hidden;
    transition:.4s;
}
.submit-btn:hover { transform:scale(1.05); }
.submit-btn::after {
    content:'';
    position:absolute;
    inset:0;
    background:linear-gradient(120deg,transparent,rgba(255,255,255,.5),transparent);
    transform:translateX(-100%);
    transition:.7s;
}
.submit-btn:hover::after { transform:translateX(100%); }

.cancel-btn {
    flex:1;
    padding:16px;
    border-radius:14px;
    background:#1e293b;
    text-align:center;
    border: 2px solid rgba(245,158,11,0.4);
    
}

/* 💎 Sidebar Cards */
.info-card {
    padding:28px;
    border-radius:20px;
    background:linear-gradient(135deg,#1e293b,#0f172a);
    transition:.4s;
    border: 1px solid rgba(245,158,11,0.4);
    background: linear-gradient(90deg, rgba(245,158,11,0.1), rgba(236,72,153,0.08));
}
.info-card:hover { 
    transform: translateY(-6px);
    background: rgba(51,65,85,0.65);
    box-shadow: 0 15px 35px rgba(0,0,0,0.5);
 }
/*.highlight{
    border: 2px solid rgba(245,158,11,0.4);
    background: linear-gradient(90deg, rgba(245,158,11,0.1), rgba(236,72,153,0.08));
    border-radius:14px;
}
*/
</style>

<script>
document.querySelectorAll('.submit-btn').forEach(btn=>{
    btn.addEventListener('mousemove', e=>{
        const rect=btn.getBoundingClientRect();
        const x=e.clientX-rect.left-rect.width/2;
        const y=e.clientY-rect.top-rect.height/2;
        btn.style.transform=`translate(${x*0.15}px,${y*0.15}px)`;
    });
    btn.addEventListener('mouseleave',()=>btn.style.transform='translate(0,0)');
});
</script>

@endsection
