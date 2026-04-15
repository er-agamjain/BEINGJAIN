@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-6 text-center">Register</h1>

        <form action="{{ route('register') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-bold mb-2">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="phone" class="block text-gray-700 font-bold mb-2">Mobile</label>
                <div class="flex">
                    <select name="country_code" id="country_code" class="px-2 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50 text-gray-700 text-sm" style="min-width:100px">
                        <option value="+93"  {{ old('country_code') == '+93'  ? 'selected' : '' }}>🇦🇫 +93</option>
                        <option value="+355" {{ old('country_code') == '+355' ? 'selected' : '' }}>🇦🇱 +355</option>
                        <option value="+213" {{ old('country_code') == '+213' ? 'selected' : '' }}>🇩🇿 +213</option>
                        <option value="+376" {{ old('country_code') == '+376' ? 'selected' : '' }}>🇦🇩 +376</option>
                        <option value="+244" {{ old('country_code') == '+244' ? 'selected' : '' }}>🇦🇴 +244</option>
                        <option value="+54"  {{ old('country_code') == '+54'  ? 'selected' : '' }}>🇦🇷 +54</option>
                        <option value="+374" {{ old('country_code') == '+374' ? 'selected' : '' }}>🇦🇲 +374</option>
                        <option value="+61"  {{ old('country_code') == '+61'  ? 'selected' : '' }}>🇦🇺 +61</option>
                        <option value="+43"  {{ old('country_code') == '+43'  ? 'selected' : '' }}>🇦🇹 +43</option>
                        <option value="+994" {{ old('country_code') == '+994' ? 'selected' : '' }}>🇦🇿 +994</option>
                        <option value="+1242" {{ old('country_code') == '+1242' ? 'selected' : '' }}>🇧🇸 +1242</option>
                        <option value="+973" {{ old('country_code') == '+973' ? 'selected' : '' }}>🇧🇭 +973</option>
                        <option value="+880" {{ old('country_code') == '+880' ? 'selected' : '' }}>🇧🇩 +880</option>
                        <option value="+375" {{ old('country_code') == '+375' ? 'selected' : '' }}>🇧🇾 +375</option>
                        <option value="+32"  {{ old('country_code') == '+32'  ? 'selected' : '' }}>🇧🇪 +32</option>
                        <option value="+501" {{ old('country_code') == '+501' ? 'selected' : '' }}>🇧🇿 +501</option>
                        <option value="+229" {{ old('country_code') == '+229' ? 'selected' : '' }}>🇧🇯 +229</option>
                        <option value="+975" {{ old('country_code') == '+975' ? 'selected' : '' }}>🇧🇹 +975</option>
                        <option value="+591" {{ old('country_code') == '+591' ? 'selected' : '' }}>🇧🇴 +591</option>
                        <option value="+387" {{ old('country_code') == '+387' ? 'selected' : '' }}>🇧🇦 +387</option>
                        <option value="+267" {{ old('country_code') == '+267' ? 'selected' : '' }}>🇧🇼 +267</option>
                        <option value="+55"  {{ old('country_code') == '+55'  ? 'selected' : '' }}>🇧🇷 +55</option>
                        <option value="+673" {{ old('country_code') == '+673' ? 'selected' : '' }}>🇧🇳 +673</option>
                        <option value="+359" {{ old('country_code') == '+359' ? 'selected' : '' }}>🇧🇬 +359</option>
                        <option value="+226" {{ old('country_code') == '+226' ? 'selected' : '' }}>🇧🇫 +226</option>
                        <option value="+257" {{ old('country_code') == '+257' ? 'selected' : '' }}>🇧🇮 +257</option>
                        <option value="+855" {{ old('country_code') == '+855' ? 'selected' : '' }}>🇰🇭 +855</option>
                        <option value="+237" {{ old('country_code') == '+237' ? 'selected' : '' }}>🇨🇲 +237</option>
                        <option value="+1"   {{ old('country_code') == '+1'   ? 'selected' : '' }}>🇨🇦 +1</option>
                        <option value="+238" {{ old('country_code') == '+238' ? 'selected' : '' }}>🇨🇻 +238</option>
                        <option value="+236" {{ old('country_code') == '+236' ? 'selected' : '' }}>🇨🇫 +236</option>
                        <option value="+235" {{ old('country_code') == '+235' ? 'selected' : '' }}>🇹🇩 +235</option>
                        <option value="+56"  {{ old('country_code') == '+56'  ? 'selected' : '' }}>🇨🇱 +56</option>
                        <option value="+86"  {{ old('country_code') == '+86'  ? 'selected' : '' }}>🇨🇳 +86</option>
                        <option value="+57"  {{ old('country_code') == '+57'  ? 'selected' : '' }}>🇨🇴 +57</option>
                        <option value="+269" {{ old('country_code') == '+269' ? 'selected' : '' }}>🇰🇲 +269</option>
                        <option value="+242" {{ old('country_code') == '+242' ? 'selected' : '' }}>🇨🇬 +242</option>
                        <option value="+506" {{ old('country_code') == '+506' ? 'selected' : '' }}>🇨🇷 +506</option>
                        <option value="+385" {{ old('country_code') == '+385' ? 'selected' : '' }}>🇭🇷 +385</option>
                        <option value="+53"  {{ old('country_code') == '+53'  ? 'selected' : '' }}>🇨🇺 +53</option>
                        <option value="+357" {{ old('country_code') == '+357' ? 'selected' : '' }}>🇨🇾 +357</option>
                        <option value="+420" {{ old('country_code') == '+420' ? 'selected' : '' }}>🇨🇿 +420</option>
                        <option value="+45"  {{ old('country_code') == '+45'  ? 'selected' : '' }}>🇩🇰 +45</option>
                        <option value="+253" {{ old('country_code') == '+253' ? 'selected' : '' }}>🇩🇯 +253</option>
                        <option value="+1809" {{ old('country_code') == '+1809' ? 'selected' : '' }}>🇩🇴 +1809</option>
                        <option value="+593" {{ old('country_code') == '+593' ? 'selected' : '' }}>🇪🇨 +593</option>
                        <option value="+20"  {{ old('country_code') == '+20'  ? 'selected' : '' }}>🇪🇬 +20</option>
                        <option value="+503" {{ old('country_code') == '+503' ? 'selected' : '' }}>🇸🇻 +503</option>
                        <option value="+240" {{ old('country_code') == '+240' ? 'selected' : '' }}>🇬🇶 +240</option>
                        <option value="+291" {{ old('country_code') == '+291' ? 'selected' : '' }}>🇪🇷 +291</option>
                        <option value="+372" {{ old('country_code') == '+372' ? 'selected' : '' }}>🇪🇪 +372</option>
                        <option value="+268" {{ old('country_code') == '+268' ? 'selected' : '' }}>🇸🇿 +268</option>
                        <option value="+251" {{ old('country_code') == '+251' ? 'selected' : '' }}>🇪🇹 +251</option>
                        <option value="+679" {{ old('country_code') == '+679' ? 'selected' : '' }}>🇫🇯 +679</option>
                        <option value="+358" {{ old('country_code') == '+358' ? 'selected' : '' }}>🇫🇮 +358</option>
                        <option value="+33"  {{ old('country_code') == '+33'  ? 'selected' : '' }}>🇫🇷 +33</option>
                        <option value="+241" {{ old('country_code') == '+241' ? 'selected' : '' }}>🇬🇦 +241</option>
                        <option value="+220" {{ old('country_code') == '+220' ? 'selected' : '' }}>🇬🇲 +220</option>
                        <option value="+995" {{ old('country_code') == '+995' ? 'selected' : '' }}>🇬🇪 +995</option>
                        <option value="+49"  {{ old('country_code') == '+49'  ? 'selected' : '' }}>🇩🇪 +49</option>
                        <option value="+233" {{ old('country_code') == '+233' ? 'selected' : '' }}>🇬🇭 +233</option>
                        <option value="+30"  {{ old('country_code') == '+30'  ? 'selected' : '' }}>🇬🇷 +30</option>
                        <option value="+502" {{ old('country_code') == '+502' ? 'selected' : '' }}>🇬🇹 +502</option>
                        <option value="+224" {{ old('country_code') == '+224' ? 'selected' : '' }}>🇬🇳 +224</option>
                        <option value="+245" {{ old('country_code') == '+245' ? 'selected' : '' }}>🇬🇼 +245</option>
                        <option value="+592" {{ old('country_code') == '+592' ? 'selected' : '' }}>🇬🇾 +592</option>
                        <option value="+509" {{ old('country_code') == '+509' ? 'selected' : '' }}>🇭🇹 +509</option>
                        <option value="+504" {{ old('country_code') == '+504' ? 'selected' : '' }}>🇭🇳 +504</option>
                        <option value="+36"  {{ old('country_code') == '+36'  ? 'selected' : '' }}>🇭🇺 +36</option>
                        <option value="+354" {{ old('country_code') == '+354' ? 'selected' : '' }}>🇮🇸 +354</option>
                        <option value="+91"  {{ old('country_code', '+91')  == '+91'  ? 'selected' : '' }}>🇮🇳 +91</option>
                        <option value="+62"  {{ old('country_code') == '+62'  ? 'selected' : '' }}>🇮🇩 +62</option>
                        <option value="+98"  {{ old('country_code') == '+98'  ? 'selected' : '' }}>🇮🇷 +98</option>
                        <option value="+964" {{ old('country_code') == '+964' ? 'selected' : '' }}>🇮🇶 +964</option>
                        <option value="+353" {{ old('country_code') == '+353' ? 'selected' : '' }}>🇮🇪 +353</option>
                        <option value="+972" {{ old('country_code') == '+972' ? 'selected' : '' }}>🇮🇱 +972</option>
                        <option value="+39"  {{ old('country_code') == '+39'  ? 'selected' : '' }}>🇮🇹 +39</option>
                        <option value="+1876" {{ old('country_code') == '+1876' ? 'selected' : '' }}>🇯🇲 +1876</option>
                        <option value="+81"  {{ old('country_code') == '+81'  ? 'selected' : '' }}>🇯🇵 +81</option>
                        <option value="+962" {{ old('country_code') == '+962' ? 'selected' : '' }}>🇯🇴 +962</option>
                        <option value="+7"   {{ old('country_code') == '+7'   ? 'selected' : '' }}>🇰🇿 +7</option>
                        <option value="+254" {{ old('country_code') == '+254' ? 'selected' : '' }}>🇰🇪 +254</option>
                        <option value="+686" {{ old('country_code') == '+686' ? 'selected' : '' }}>🇰🇮 +686</option>
                        <option value="+850" {{ old('country_code') == '+850' ? 'selected' : '' }}>🇰🇵 +850</option>
                        <option value="+82"  {{ old('country_code') == '+82'  ? 'selected' : '' }}>🇰🇷 +82</option>
                        <option value="+965" {{ old('country_code') == '+965' ? 'selected' : '' }}>🇰🇼 +965</option>
                        <option value="+996" {{ old('country_code') == '+996' ? 'selected' : '' }}>🇰🇬 +996</option>
                        <option value="+856" {{ old('country_code') == '+856' ? 'selected' : '' }}>🇱🇦 +856</option>
                        <option value="+371" {{ old('country_code') == '+371' ? 'selected' : '' }}>🇱🇻 +371</option>
                        <option value="+961" {{ old('country_code') == '+961' ? 'selected' : '' }}>🇱🇧 +961</option>
                        <option value="+266" {{ old('country_code') == '+266' ? 'selected' : '' }}>🇱🇸 +266</option>
                        <option value="+231" {{ old('country_code') == '+231' ? 'selected' : '' }}>🇱🇷 +231</option>
                        <option value="+218" {{ old('country_code') == '+218' ? 'selected' : '' }}>🇱🇾 +218</option>
                        <option value="+423" {{ old('country_code') == '+423' ? 'selected' : '' }}>🇱🇮 +423</option>
                        <option value="+370" {{ old('country_code') == '+370' ? 'selected' : '' }}>🇱🇹 +370</option>
                        <option value="+352" {{ old('country_code') == '+352' ? 'selected' : '' }}>🇱🇺 +352</option>
                        <option value="+261" {{ old('country_code') == '+261' ? 'selected' : '' }}>🇲🇬 +261</option>
                        <option value="+265" {{ old('country_code') == '+265' ? 'selected' : '' }}>🇲🇼 +265</option>
                        <option value="+60"  {{ old('country_code') == '+60'  ? 'selected' : '' }}>🇲🇾 +60</option>
                        <option value="+960" {{ old('country_code') == '+960' ? 'selected' : '' }}>🇲🇻 +960</option>
                        <option value="+223" {{ old('country_code') == '+223' ? 'selected' : '' }}>🇲🇱 +223</option>
                        <option value="+356" {{ old('country_code') == '+356' ? 'selected' : '' }}>🇲🇹 +356</option>
                        <option value="+692" {{ old('country_code') == '+692' ? 'selected' : '' }}>🇲🇭 +692</option>
                        <option value="+222" {{ old('country_code') == '+222' ? 'selected' : '' }}>🇲🇷 +222</option>
                        <option value="+230" {{ old('country_code') == '+230' ? 'selected' : '' }}>🇲🇺 +230</option>
                        <option value="+52"  {{ old('country_code') == '+52'  ? 'selected' : '' }}>🇲🇽 +52</option>
                        <option value="+691" {{ old('country_code') == '+691' ? 'selected' : '' }}>🇫🇲 +691</option>
                        <option value="+373" {{ old('country_code') == '+373' ? 'selected' : '' }}>🇲🇩 +373</option>
                        <option value="+377" {{ old('country_code') == '+377' ? 'selected' : '' }}>🇲🇨 +377</option>
                        <option value="+976" {{ old('country_code') == '+976' ? 'selected' : '' }}>🇲🇳 +976</option>
                        <option value="+382" {{ old('country_code') == '+382' ? 'selected' : '' }}>🇲🇪 +382</option>
                        <option value="+212" {{ old('country_code') == '+212' ? 'selected' : '' }}>🇲🇦 +212</option>
                        <option value="+258" {{ old('country_code') == '+258' ? 'selected' : '' }}>🇲🇿 +258</option>
                        <option value="+264" {{ old('country_code') == '+264' ? 'selected' : '' }}>🇳🇦 +264</option>
                        <option value="+674" {{ old('country_code') == '+674' ? 'selected' : '' }}>🇳🇷 +674</option>
                        <option value="+977" {{ old('country_code') == '+977' ? 'selected' : '' }}>🇳🇵 +977</option>
                        <option value="+31"  {{ old('country_code') == '+31'  ? 'selected' : '' }}>🇳🇱 +31</option>
                        <option value="+64"  {{ old('country_code') == '+64'  ? 'selected' : '' }}>🇳🇿 +64</option>
                        <option value="+505" {{ old('country_code') == '+505' ? 'selected' : '' }}>🇳🇮 +505</option>
                        <option value="+227" {{ old('country_code') == '+227' ? 'selected' : '' }}>🇳🇪 +227</option>
                        <option value="+234" {{ old('country_code') == '+234' ? 'selected' : '' }}>🇳🇬 +234</option>
                        <option value="+389" {{ old('country_code') == '+389' ? 'selected' : '' }}>🇲🇰 +389</option>
                        <option value="+47"  {{ old('country_code') == '+47'  ? 'selected' : '' }}>🇳🇴 +47</option>
                        <option value="+968" {{ old('country_code') == '+968' ? 'selected' : '' }}>🇴🇲 +968</option>
                        <option value="+92"  {{ old('country_code') == '+92'  ? 'selected' : '' }}>🇵🇰 +92</option>
                        <option value="+680" {{ old('country_code') == '+680' ? 'selected' : '' }}>🇵🇼 +680</option>
                        <option value="+507" {{ old('country_code') == '+507' ? 'selected' : '' }}>🇵🇦 +507</option>
                        <option value="+675" {{ old('country_code') == '+675' ? 'selected' : '' }}>🇵🇬 +675</option>
                        <option value="+595" {{ old('country_code') == '+595' ? 'selected' : '' }}>🇵🇾 +595</option>
                        <option value="+51"  {{ old('country_code') == '+51'  ? 'selected' : '' }}>🇵🇪 +51</option>
                        <option value="+63"  {{ old('country_code') == '+63'  ? 'selected' : '' }}>🇵🇭 +63</option>
                        <option value="+48"  {{ old('country_code') == '+48'  ? 'selected' : '' }}>🇵🇱 +48</option>
                        <option value="+351" {{ old('country_code') == '+351' ? 'selected' : '' }}>🇵🇹 +351</option>
                        <option value="+974" {{ old('country_code') == '+974' ? 'selected' : '' }}>🇶🇦 +974</option>
                        <option value="+40"  {{ old('country_code') == '+40'  ? 'selected' : '' }}>🇷🇴 +40</option>
                        <option value="+7"   {{ old('country_code') == '+7'   ? 'selected' : '' }}>🇷🇺 +7</option>
                        <option value="+250" {{ old('country_code') == '+250' ? 'selected' : '' }}>🇷🇼 +250</option>
                        <option value="+1869" {{ old('country_code') == '+1869' ? 'selected' : '' }}>🇰🇳 +1869</option>
                        <option value="+1758" {{ old('country_code') == '+1758' ? 'selected' : '' }}>🇱🇨 +1758</option>
                        <option value="+1784" {{ old('country_code') == '+1784' ? 'selected' : '' }}>🇻🇨 +1784</option>
                        <option value="+685" {{ old('country_code') == '+685' ? 'selected' : '' }}>🇼🇸 +685</option>
                        <option value="+378" {{ old('country_code') == '+378' ? 'selected' : '' }}>🇸🇲 +378</option>
                        <option value="+239" {{ old('country_code') == '+239' ? 'selected' : '' }}>🇸🇹 +239</option>
                        <option value="+966" {{ old('country_code') == '+966' ? 'selected' : '' }}>🇸🇦 +966</option>
                        <option value="+221" {{ old('country_code') == '+221' ? 'selected' : '' }}>🇸🇳 +221</option>
                        <option value="+381" {{ old('country_code') == '+381' ? 'selected' : '' }}>🇷🇸 +381</option>
                        <option value="+232" {{ old('country_code') == '+232' ? 'selected' : '' }}>🇸🇱 +232</option>
                        <option value="+65"  {{ old('country_code') == '+65'  ? 'selected' : '' }}>🇸🇬 +65</option>
                        <option value="+421" {{ old('country_code') == '+421' ? 'selected' : '' }}>🇸🇰 +421</option>
                        <option value="+386" {{ old('country_code') == '+386' ? 'selected' : '' }}>🇸🇮 +386</option>
                        <option value="+677" {{ old('country_code') == '+677' ? 'selected' : '' }}>🇸🇧 +677</option>
                        <option value="+252" {{ old('country_code') == '+252' ? 'selected' : '' }}>🇸🇴 +252</option>
                        <option value="+27"  {{ old('country_code') == '+27'  ? 'selected' : '' }}>🇿🇦 +27</option>
                        <option value="+211" {{ old('country_code') == '+211' ? 'selected' : '' }}>🇸🇸 +211</option>
                        <option value="+34"  {{ old('country_code') == '+34'  ? 'selected' : '' }}>🇪🇸 +34</option>
                        <option value="+94"  {{ old('country_code') == '+94'  ? 'selected' : '' }}>🇱🇰 +94</option>
                        <option value="+249" {{ old('country_code') == '+249' ? 'selected' : '' }}>🇸🇩 +249</option>
                        <option value="+597" {{ old('country_code') == '+597' ? 'selected' : '' }}>🇸🇷 +597</option>
                        <option value="+46"  {{ old('country_code') == '+46'  ? 'selected' : '' }}>🇸🇪 +46</option>
                        <option value="+41"  {{ old('country_code') == '+41'  ? 'selected' : '' }}>🇨🇭 +41</option>
                        <option value="+963" {{ old('country_code') == '+963' ? 'selected' : '' }}>🇸🇾 +963</option>
                        <option value="+886" {{ old('country_code') == '+886' ? 'selected' : '' }}>🇹🇼 +886</option>
                        <option value="+992" {{ old('country_code') == '+992' ? 'selected' : '' }}>🇹🇯 +992</option>
                        <option value="+255" {{ old('country_code') == '+255' ? 'selected' : '' }}>🇹🇿 +255</option>
                        <option value="+66"  {{ old('country_code') == '+66'  ? 'selected' : '' }}>🇹🇭 +66</option>
                        <option value="+670" {{ old('country_code') == '+670' ? 'selected' : '' }}>🇹🇱 +670</option>
                        <option value="+228" {{ old('country_code') == '+228' ? 'selected' : '' }}>🇹🇬 +228</option>
                        <option value="+676" {{ old('country_code') == '+676' ? 'selected' : '' }}>🇹🇴 +676</option>
                        <option value="+1868" {{ old('country_code') == '+1868' ? 'selected' : '' }}>🇹🇹 +1868</option>
                        <option value="+216" {{ old('country_code') == '+216' ? 'selected' : '' }}>🇹🇳 +216</option>
                        <option value="+90"  {{ old('country_code') == '+90'  ? 'selected' : '' }}>🇹🇷 +90</option>
                        <option value="+993" {{ old('country_code') == '+993' ? 'selected' : '' }}>🇹🇲 +993</option>
                        <option value="+688" {{ old('country_code') == '+688' ? 'selected' : '' }}>🇹🇻 +688</option>
                        <option value="+256" {{ old('country_code') == '+256' ? 'selected' : '' }}>🇺🇬 +256</option>
                        <option value="+380" {{ old('country_code') == '+380' ? 'selected' : '' }}>🇺🇦 +380</option>
                        <option value="+971" {{ old('country_code') == '+971' ? 'selected' : '' }}>🇦🇪 +971</option>
                        <option value="+44"  {{ old('country_code') == '+44'  ? 'selected' : '' }}>🇬🇧 +44</option>
                        <option value="+1"   {{ old('country_code', '+91') == '+1' ? 'selected' : '' }}>🇺🇸 +1</option>
                        <option value="+598" {{ old('country_code') == '+598' ? 'selected' : '' }}>🇺🇾 +598</option>
                        <option value="+998" {{ old('country_code') == '+998' ? 'selected' : '' }}>🇺🇿 +998</option>
                        <option value="+678" {{ old('country_code') == '+678' ? 'selected' : '' }}>🇻🇺 +678</option>
                        <option value="+379" {{ old('country_code') == '+379' ? 'selected' : '' }}>🇻🇦 +379</option>
                        <option value="+58"  {{ old('country_code') == '+58'  ? 'selected' : '' }}>🇻🇪 +58</option>
                        <option value="+84"  {{ old('country_code') == '+84'  ? 'selected' : '' }}>🇻🇳 +84</option>
                        <option value="+967" {{ old('country_code') == '+967' ? 'selected' : '' }}>🇾🇪 +967</option>
                        <option value="+260" {{ old('country_code') == '+260' ? 'selected' : '' }}>🇿🇲 +260</option>
                        <option value="+263" {{ old('country_code') == '+263' ? 'selected' : '' }}>🇿🇼 +263</option>
                    </select>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Enter mobile number" class="flex-1 px-4 py-2 border border-l-0 border-gray-300 rounded-r-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <p id="phone-hint" class="text-gray-500 text-xs mt-1"></p>
                <p id="phone-error" class="text-red-500 text-sm mt-1 hidden"></p>
                @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                @error('country_code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            
            <script>
const phoneRules = {
    '+1':    { min:10, max:10, hint:'10 digits' },
    '+7':    { min:10, max:10, hint:'10 digits' },
    '+20':   { min:10, max:10, hint:'10 digits' },
    '+27':   { min:9,  max:9,  hint:'9 digits' },
    '+30':   { min:10, max:10, hint:'10 digits' },
    '+31':   { min:9,  max:9,  hint:'9 digits' },
    '+32':   { min:8,  max:9,  hint:'8–9 digits' },
    '+33':   { min:9,  max:9,  hint:'9 digits' },
    '+34':   { min:9,  max:9,  hint:'9 digits' },
    '+36':   { min:9,  max:9,  hint:'9 digits' },
    '+39':   { min:9,  max:10, hint:'9–10 digits' },
    '+40':   { min:9,  max:9,  hint:'9 digits' },
    '+41':   { min:9,  max:9,  hint:'9 digits' },
    '+43':   { min:10, max:11, hint:'10–11 digits' },
    '+44':   { min:10, max:10, hint:'10 digits' },
    '+45':   { min:8,  max:8,  hint:'8 digits' },
    '+46':   { min:9,  max:9,  hint:'9 digits' },
    '+47':   { min:8,  max:8,  hint:'8 digits' },
    '+48':   { min:9,  max:9,  hint:'9 digits' },
    '+49':   { min:10, max:11, hint:'10–11 digits' },
    '+51':   { min:9,  max:9,  hint:'9 digits' },
    '+52':   { min:10, max:10, hint:'10 digits' },
    '+53':   { min:8,  max:8,  hint:'8 digits' },
    '+54':   { min:10, max:11, hint:'10–11 digits' },
    '+55':   { min:10, max:11, hint:'10–11 digits' },
    '+56':   { min:9,  max:9,  hint:'9 digits' },
    '+57':   { min:10, max:10, hint:'10 digits' },
    '+58':   { min:10, max:10, hint:'10 digits' },
    '+60':   { min:9,  max:10, hint:'9–10 digits' },
    '+61':   { min:9,  max:9,  hint:'9 digits' },
    '+62':   { min:9,  max:12, hint:'9–12 digits' },
    '+63':   { min:10, max:10, hint:'10 digits' },
    '+64':   { min:8,  max:10, hint:'8–10 digits' },
    '+65':   { min:8,  max:8,  hint:'8 digits' },
    '+66':   { min:9,  max:9,  hint:'9 digits' },
    '+81':   { min:10, max:10, hint:'10 digits' },
    '+82':   { min:9,  max:10, hint:'9–10 digits' },
    '+84':   { min:9,  max:10, hint:'9–10 digits' },
    '+86':   { min:11, max:11, hint:'11 digits' },
    '+90':   { min:10, max:10, hint:'10 digits' },
    '+91':   { min:10, max:10, hint:'10 digits' },
    '+92':   { min:10, max:10, hint:'10 digits' },
    '+93':   { min:9,  max:9,  hint:'9 digits' },
    '+94':   { min:9,  max:9,  hint:'9 digits' },
    '+98':   { min:10, max:10, hint:'10 digits' },
    '+212':  { min:9,  max:9,  hint:'9 digits' },
    '+213':  { min:9,  max:9,  hint:'9 digits' },
    '+216':  { min:8,  max:8,  hint:'8 digits' },
    '+218':  { min:9,  max:10, hint:'9–10 digits' },
    '+220':  { min:7,  max:7,  hint:'7 digits' },
    '+221':  { min:9,  max:9,  hint:'9 digits' },
    '+222':  { min:8,  max:8,  hint:'8 digits' },
    '+223':  { min:8,  max:8,  hint:'8 digits' },
    '+224':  { min:9,  max:9,  hint:'9 digits' },
    '+225':  { min:10, max:10, hint:'10 digits' },
    '+226':  { min:8,  max:8,  hint:'8 digits' },
    '+227':  { min:8,  max:8,  hint:'8 digits' },
    '+228':  { min:8,  max:8,  hint:'8 digits' },
    '+229':  { min:8,  max:8,  hint:'8 digits' },
    '+230':  { min:8,  max:8,  hint:'8 digits' },
    '+231':  { min:7,  max:8,  hint:'7–8 digits' },
    '+232':  { min:8,  max:8,  hint:'8 digits' },
    '+233':  { min:9,  max:9,  hint:'9 digits' },
    '+234':  { min:10, max:10, hint:'10 digits' },
    '+237':  { min:9,  max:9,  hint:'9 digits' },
    '+249':  { min:9,  max:9,  hint:'9 digits' },
    '+250':  { min:9,  max:9,  hint:'9 digits' },
    '+251':  { min:9,  max:9,  hint:'9 digits' },
    '+252':  { min:7,  max:8,  hint:'7–8 digits' },
    '+253':  { min:8,  max:8,  hint:'8 digits' },
    '+254':  { min:9,  max:9,  hint:'9 digits' },
    '+255':  { min:9,  max:9,  hint:'9 digits' },
    '+256':  { min:9,  max:9,  hint:'9 digits' },
    '+257':  { min:8,  max:8,  hint:'8 digits' },
    '+258':  { min:9,  max:9,  hint:'9 digits' },
    '+260':  { min:9,  max:9,  hint:'9 digits' },
    '+261':  { min:9,  max:9,  hint:'9 digits' },
    '+263':  { min:9,  max:9,  hint:'9 digits' },
    '+264':  { min:9,  max:9,  hint:'9 digits' },
    '+265':  { min:9,  max:9,  hint:'9 digits' },
    '+266':  { min:8,  max:8,  hint:'8 digits' },
    '+267':  { min:8,  max:8,  hint:'8 digits' },
    '+268':  { min:8,  max:8,  hint:'8 digits' },
    '+269':  { min:7,  max:7,  hint:'7 digits' },
    '+351':  { min:9,  max:9,  hint:'9 digits' },
    '+352':  { min:9,  max:9,  hint:'9 digits' },
    '+353':  { min:9,  max:9,  hint:'9 digits' },
    '+354':  { min:7,  max:7,  hint:'7 digits' },
    '+355':  { min:9,  max:9,  hint:'9 digits' },
    '+356':  { min:8,  max:8,  hint:'8 digits' },
    '+357':  { min:8,  max:8,  hint:'8 digits' },
    '+358':  { min:9,  max:10, hint:'9–10 digits' },
    '+359':  { min:9,  max:9,  hint:'9 digits' },
    '+370':  { min:8,  max:8,  hint:'8 digits' },
    '+371':  { min:8,  max:8,  hint:'8 digits' },
    '+372':  { min:7,  max:8,  hint:'7–8 digits' },
    '+373':  { min:8,  max:8,  hint:'8 digits' },
    '+374':  { min:8,  max:8,  hint:'8 digits' },
    '+375':  { min:9,  max:9,  hint:'9 digits' },
    '+376':  { min:6,  max:6,  hint:'6 digits' },
    '+377':  { min:8,  max:9,  hint:'8–9 digits' },
    '+378':  { min:10, max:10, hint:'10 digits' },
    '+380':  { min:9,  max:9,  hint:'9 digits' },
    '+381':  { min:8,  max:9,  hint:'8–9 digits' },
    '+382':  { min:8,  max:8,  hint:'8 digits' },
    '+385':  { min:8,  max:9,  hint:'8–9 digits' },
    '+386':  { min:8,  max:8,  hint:'8 digits' },
    '+387':  { min:8,  max:8,  hint:'8 digits' },
    '+389':  { min:8,  max:8,  hint:'8 digits' },
    '+420':  { min:9,  max:9,  hint:'9 digits' },
    '+421':  { min:9,  max:9,  hint:'9 digits' },
    '+423':  { min:7,  max:9,  hint:'7–9 digits' },
    '+593':  { min:9,  max:9,  hint:'9 digits' },
    '+595':  { min:9,  max:9,  hint:'9 digits' },
    '+597':  { min:7,  max:7,  hint:'7 digits' },
    '+598':  { min:8,  max:8,  hint:'8 digits' },
    '+670':  { min:7,  max:8,  hint:'7–8 digits' },
    '+673':  { min:7,  max:7,  hint:'7 digits' },
    '+675':  { min:8,  max:8,  hint:'8 digits' },
    '+676':  { min:7,  max:7,  hint:'7 digits' },
    '+677':  { min:7,  max:7,  hint:'7 digits' },
    '+678':  { min:7,  max:7,  hint:'7 digits' },
    '+679':  { min:7,  max:7,  hint:'7 digits' },
    '+680':  { min:7,  max:7,  hint:'7 digits' },
    '+685':  { min:7,  max:7,  hint:'7 digits' },
    '+686':  { min:8,  max:8,  hint:'8 digits' },
    '+688':  { min:5,  max:6,  hint:'5–6 digits' },
    '+691':  { min:7,  max:7,  hint:'7 digits' },
    '+692':  { min:7,  max:7,  hint:'7 digits' },
    '+850':  { min:9,  max:10, hint:'9–10 digits' },
    '+855':  { min:9,  max:9,  hint:'9 digits' },
    '+856':  { min:9,  max:10, hint:'9–10 digits' },
    '+880':  { min:10, max:10, hint:'10 digits' },
    '+886':  { min:9,  max:9,  hint:'9 digits' },
    '+960':  { min:7,  max:7,  hint:'7 digits' },
    '+961':  { min:7,  max:8,  hint:'7–8 digits' },
    '+962':  { min:9,  max:9,  hint:'9 digits' },
    '+963':  { min:9,  max:9,  hint:'9 digits' },
    '+964':  { min:10, max:10, hint:'10 digits' },
    '+965':  { min:8,  max:8,  hint:'8 digits' },
    '+966':  { min:9,  max:9,  hint:'9 digits' },
    '+967':  { min:9,  max:9,  hint:'9 digits' },
    '+968':  { min:8,  max:8,  hint:'8 digits' },
    '+971':  { min:9,  max:9,  hint:'9 digits' },
    '+972':  { min:9,  max:9,  hint:'9 digits' },
    '+973':  { min:8,  max:8,  hint:'8 digits' },
    '+974':  { min:8,  max:8,  hint:'8 digits' },
    '+975':  { min:8,  max:8,  hint:'8 digits' },
    '+976':  { min:8,  max:8,  hint:'8 digits' },
    '+977':  { min:10, max:10, hint:'10 digits' },
    '+992':  { min:9,  max:9,  hint:'9 digits' },
    '+993':  { min:8,  max:8,  hint:'8 digits' },
    '+994':  { min:9,  max:9,  hint:'9 digits' },
    '+995':  { min:9,  max:9,  hint:'9 digits' },
    '+996':  { min:9,  max:9,  hint:'9 digits' },
    '+998':  { min:9,  max:9,  hint:'9 digits' },
    '+1242': { min:7,  max:7,  hint:'7 digits' },
    '+1758': { min:7,  max:7,  hint:'7 digits' },
    '+1784': { min:7,  max:7,  hint:'7 digits' },
    '+1809': { min:7,  max:7,  hint:'7 digits' },
    '+1868': { min:7,  max:7,  hint:'7 digits' },
    '+1869': { min:7,  max:7,  hint:'7 digits' },
    '+1876': { min:7,  max:7,  hint:'7 digits' },
};

function validatePhone() {
    const code  = document.getElementById('country_code').value;
    const phone = document.getElementById('phone');
    const hint  = document.getElementById('phone-hint');
    const err   = document.getElementById('phone-error');
    const rule  = phoneRules[code] || { min:6, max:15, hint:'6–15 digits' };
    const digits = phone.value.replace(/\D/g, '');

    hint.textContent = 'Expected: ' + rule.hint + ' (digits only, no country code)';

    if (phone.value === '') {
        err.classList.add('hidden');
        phone.classList.remove('border-red-500', 'border-green-500');
        return true;
    }

    if (!/^\d+$/.test(phone.value)) {
        err.textContent = 'Phone number must contain digits only.';
        err.classList.remove('hidden');
        phone.classList.add('border-red-500');
        phone.classList.remove('border-green-500');
        return false;
    }

    if (digits.length < rule.min || digits.length > rule.max) {
        err.textContent = 'Invalid number for selected country. Expected ' + rule.hint + '.';
        err.classList.remove('hidden');
        phone.classList.add('border-red-500');
        phone.classList.remove('border-green-500');
        return false;
    }

    err.classList.add('hidden');
    phone.classList.remove('border-red-500');
    phone.classList.add('border-green-500');
    return true;
}

document.getElementById('country_code').addEventListener('change', validatePhone);
document.getElementById('phone').addEventListener('input', validatePhone);

document.querySelector('form').addEventListener('submit', function(e) {
    if (!validatePhone()) {
        e.preventDefault();
    }
});

// Init hint on page load
document.addEventListener('DOMContentLoaded', function() {
    const code = document.getElementById('country_code').value;
    const rule = phoneRules[code] || { min:6, max:15, hint:'6–15 digits' };
    document.getElementById('phone-hint').textContent = 'Expected: ' + rule.hint + ' (digits only, no country code)';
});
</script>

            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-bold mb-2">Password</label>
                <input type="password" id="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block text-gray-700 font-bold mb-2">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                @error('password_confirmation') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="w-full bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-600">Register</button>
        </form>

        <div class="mt-4 text-center">
            <p class="text-gray-600">Or register with: <a href="{{ route('auth.google') }}" class="text-blue-500 hover:underline">Google</a></p>
        </div>

        <div class="mt-4 text-center">
            <p class="text-gray-600">Already have an account? <a href="{{ route('login') }}" class="text-blue-500 hover:underline">Login</a></p>
        </div>
    </div>
</div>
@endsection
