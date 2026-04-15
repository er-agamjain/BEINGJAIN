<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class EventFilterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organiser = User::whereHas('role', function($q) {
            $q->where('name', 'organiser');
        })->first();

        if (!$organiser) {
            // Fallback to first user if no organiser found
            $organiser = User::first();
        }

        if (!$organiser) {
            $this->command->error('No users found. Please seed users first.');
            return;
        }

        $categories = EventCategory::all();
        if ($categories->isEmpty()) {
            $this->command->error('No categories found. Please seed categories first.');
            return;
        }

        $communities = ['Jain', 'Vaishnav', 'Brahmin', 'Patel', 'Lohana'];
        $gacchhs = ['Tapagacch', 'Khartargacch', 'Anchalgacch', 'Tristutik', 'Purnimayak'];
        
        $events = [
            [
                'title' => 'Jain Meditation Camp',
                'description' => 'A peaceful meditation camp for the Jain community.',
                'location' => 'Mumbai, India',
                'is_free' => true,
                'community' => 'Jain',
                'gacchh' => 'Tapagacch',
            ],
            [
                'title' => 'Online Vedic Workshop',
                'description' => 'Learn Vedic wisdom from the comfort of your home.',
                'location' => 'Online',
                'is_free' => false,
                'base_price' => 500,
                'community' => 'Brahmin',
                'gacchh' => null,
            ],
            [
                'title' => 'Vaishnav Kirtan Night',
                'description' => 'Evening of devotional songs.',
                'location' => 'Ahmedabad, India',
                'is_free' => true,
                'community' => 'Vaishnav',
                'gacchh' => 'Purnimayak',
            ],
            [
                'title' => 'Jain Philosophy Talk (Online)',
                'description' => 'Deep dive into Jain philosophy.',
                'location' => 'Online via Zoom',
                'is_free' => true,
                'community' => 'Jain',
                'gacchh' => 'Khartargacch',
            ],
            [
                'title' => 'Community Gathering',
                'description' => 'Annual gathering for community members.',
                'location' => 'Surat, India',
                'is_free' => false,
                'base_price' => 200,
                'community' => 'Patel',
                'gacchh' => null,
            ],
             [
                'title' => 'Anchalgacch Mahotsav',
                'description' => 'Grand celebration.',
                'location' => 'Rajasthan, India',
                'is_free' => true,
                'community' => 'Jain',
                'gacchh' => 'Anchalgacch',
            ],
        ];

        foreach ($events as $eventData) {
            Event::create([
                'organiser_id' => $organiser->id,
                'category_id' => $categories->random()->id,
                'title' => $eventData['title'],
                'description' => $eventData['description'],
                'event_date' => Carbon::now()->addDays(rand(1, 30)),
                'start_time' => '10:00:00',
                'end_time' => '12:00:00',
                'location' => $eventData['location'],
                'base_price' => $eventData['base_price'] ?? 0,
                'capacity' => 100,
                'status' => 'published', // Assuming 'published' or similar status exists
                'is_free' => $eventData['is_free'],
                'community' => $eventData['community'],
                'gacchh' => $eventData['gacchh'],
            ]);
        }

        $this->command->info('Created ' . count($events) . ' dummy events with filter data.');
    }
}
