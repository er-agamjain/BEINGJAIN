<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;
use Illuminate\Support\Str;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $featuredCities = [
            [
                'name' => 'Mumbai',
                'image' => 'https://images.unsplash.com/photo-1570168007204-dfb528c6958f?q=80&w=200&auto=format&fit=crop',
                'is_featured' => true
            ],
            [
                'name' => 'Delhi',
                'image' => 'https://images.unsplash.com/photo-1587474260584-136574528ed5?q=80&w=200&auto=format&fit=crop',
                'is_featured' => true
            ],
            [
                'name' => 'Bangalore',
                'image' => 'https://images.unsplash.com/photo-1596176530529-78163a4f7af2?q=80&w=200&auto=format&fit=crop',
                'is_featured' => true
            ],
            [
                'name' => 'Hyderabad',
                'image' => 'https://images.unsplash.com/photo-1605537964076-3cb0ea2e356d?q=80&w=200&auto=format&fit=crop',
                'is_featured' => true
            ],
            [
                'name' => 'Ahmedabad',
                'image' => 'https://images.unsplash.com/photo-1597039536838-8424263725e2?q=80&w=200&auto=format&fit=crop',
                'is_featured' => true
            ],
            [
                'name' => 'Pune',
                'image' => 'https://images.unsplash.com/photo-1628189870577-94a53075c316?q=80&w=200&auto=format&fit=crop',
                'is_featured' => true
            ],
            [
                'name' => 'Chennai',
                'image' => 'https://images.unsplash.com/photo-1582510003544-4d00b7f74220?q=80&w=200&auto=format&fit=crop',
                'is_featured' => true
            ],
            [
                'name' => 'Kolkata',
                'image' => 'https://images.unsplash.com/photo-1558431382-27e30314225d?q=80&w=200&auto=format&fit=crop',
                'is_featured' => true
            ],
        ];

        $otherCities = [
            'Surat', 'Jaipur', 'Lucknow', 'Kanpur', 'Nagpur', 'Indore', 'Thane', 'Bhopal',
            'Visakhapatnam', 'Pimpri-Chinchwad', 'Patna', 'Vadodara', 'Ghaziabad', 'Ludhiana',
            'Agra', 'Nashik', 'Faridabad', 'Gurgaon', 'Meerut', 'Rajkot', 'Kalyan-Dombivali', 'Vasai-Virar',
            'Varanasi', 'Srinagar', 'Aurangabad', 'Dhanbad', 'Amritsar', 'Navi Mumbai', 'Allahabad',
            'Ranchi', 'Howrah', 'Coimbatore', 'Jabalpur', 'Gwalior', 'Vijayawada', 'Jodhpur',
            'Madurai', 'Raipur', 'Kota', 'Chandigarh', 'Guwahati', 'Solapur', 'Hubli-Dharwad',
            'Mysore', 'Tiruchirappalli', 'Bareilly', 'Aligarh', 'Tiruppur', 'Moradabad', 'Jalandhar',
            'Bhubaneswar', 'Salem', 'Warangal', 'Mira-Bhayandar', 'Thiruvananthapuram', 'Bhiwandi',
            'Saharanpur', 'Guntur', 'Amravati', 'Bikaner', 'Noida', 'Jamshedpur', 'Bhilai',
            'Cuttack', 'Firozabad', 'Kochi', 'Nellore', 'Bhavnagar', 'Dehradun', 'Durgapur',
            'Asansol', 'Rourkela', 'Nanded', 'Kolhapur', 'Ajmer', 'Akola'
        ];

        foreach ($featuredCities as $data) {
            City::updateOrCreate(
                ['name' => $data['name']],
                [
                    'slug' => Str::slug($data['name']),
                    'image' => $data['image'],
                    'is_featured' => $data['is_featured'],
                    'is_active' => true
                ]
            );
        }

        foreach ($otherCities as $cityName) {
            City::firstOrCreate(
                ['name' => $cityName],
                [
                    'slug' => Str::slug($cityName),
                    'is_active' => true
                ]
            );
        }
    }
}
