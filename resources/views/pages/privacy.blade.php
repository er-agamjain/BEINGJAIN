@extends('layouts.app')

@section('title', 'Privacy Policy - EventBook')

@section('content')
<div class="relative min-h-screen bg-slate-950 text-white overflow-hidden">

    <!-- 🌌 Background Glow -->
    <div class="absolute inset-0 -z-10">
        <div class="glow glow1"></div>
        <div class="glow glow2"></div>
    </div>

    <div class="container mx-auto px-6 md:px-12 lg:px-20 py-24 max-w-5xl">

        <!-- 🔥 HERO -->
        <div class="text-center mb-16">
            <h1 class="hero-title mb-4">
                <i class="fas fa-shield-alt text-amber-400"></i> Privacy Policy
            </h1>
            <p class="text-slate-300 text-lg">Your privacy is important to us</p>
        </div>

        <!-- Last Updated -->
        <div class="policy-card text-sm text-slate-400 mb-8">
            Last Updated: {{ date('F d, Y') }}
        </div>

        <!-- Sections -->
        <div class="space-y-8">

            @php
            $sections = [
                ['icon'=>'fa-info-circle','color'=>'text-amber-400','title'=>'Introduction','text'=>'EventBook operates the EventBook website and app. This page informs you about how we collect, use, and protect your data.'],
                ['icon'=>'fa-database','color'=>'text-blue-400','title'=>'Information Collection','text'=>'We collect name, email, phone, billing details, preferences, device info and usage data.'],
                ['icon'=>'fa-lock','color'=>'text-emerald-400','title'=>'Use of Data','text'=>'We use data to provide services, support users, improve systems, detect issues and enhance experience.'],
                ['icon'=>'fa-eye-slash','color'=>'text-purple-400','title'=>'Security','text'=>'We apply industry-level protection but cannot guarantee 100% internet security.'],
                ['icon'=>'fa-share-alt','color'=>'text-rose-400','title'=>'Disclosure','text'=>'We do not sell data. Information is shared only with processors, providers or when legally required.'],
                ['icon'=>'fa-cookie-bite','color'=>'text-yellow-400','title'=>'Cookies','text'=>'Cookies help track usage and improve performance. Users can disable cookies via browser settings.'],
                ['icon'=>'fa-edit','color'=>'text-pink-400','title'=>'Policy Changes','text'=>'We may update this policy. The latest version will always be available here.'],
            ];
            @endphp

            @foreach($sections as $section)
            <div class="policy-card">
                <h2 class="policy-title">
                    <i class="fas {{ $section['icon'] }} {{ $section['color'] }}"></i>
                    {{ $section['title'] }}
                </h2>
                <p class="policy-text">{{ $section['text'] }}</p>
            </div>
            @endforeach

            <!-- Contact -->
            <div class="policy-card">
                <h2 class="policy-title">
                    <i class="fas fa-envelope text-amber-400"></i> Contact Us
                </h2>
                <p class="policy-text">
                    Email: privacy@eventbook.com<br>
                    <a href="{{ route('contact') }}" class="text-amber-400 hover:text-amber-300">Contact Form</a>
                </p>
            </div>

        </div>

        <!-- CTA -->
        <div class="text-center mt-16">
            <a href="/" class="submit-btn">Back to Home</a>
        </div>
    </div>
</div>

<style>

/* Glow BG */
.glow {
    position:absolute;
    width:600px;
    height:600px;
    border-radius:50%;
    filter:blur(120px);
    opacity:.25;
    animation: floatGlow 20s ease-in-out infinite alternate;
}
.glow1 { background:#f59e0b; top:-100px; left:-100px; }
.glow2 { background:#8b5cf6; bottom:-120px; right:-120px; }
.glow3 { background:#ec4899; top:40%; left:30%; }

/* Hero Gradient */
.hero-title {
    font-size: clamp(40px,5vw,60px);
    font-weight:900;
    background: linear-gradient(90deg,#f59e0b,#ec4899,#8b5cf6);
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
}

/* Cards */
.policy-card {
    background: rgba(15,23,42,0.55);
    backdrop-filter: blur(18px);
    border-radius: 22px;
    padding: 32px;
    border: 1px solid rgba(255,255,255,0.08);
    transition:.35s;
}
.policy-card:hover {
    transform: translateY(-6px);
    border-color: rgba(245,158,11,0.4);
    border: 2px solid  rgba(245,158,11,0.4);
}
    
}

/* Titles */
.policy-title {
    font-size: 22px;
    font-weight: 700;
    margin-bottom: 12px;
    display:flex;
    gap:10px;
    align-items:center;
}

/* Text */
.policy-text {
    color:#cbd5e1;
    line-height:1.7;
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

.policy-card {
    /*background: linear-gradient(135deg, rgba(15,23,42,0.7), rgba(30,41,59,0.6));*/
    border: 1px solid rgba(245,158,11,0.2);
    background: linear-gradient(90deg, rgba(245,158,11,0.1), rgba(236,72,153,0.08));
}

</style>
@endsection
