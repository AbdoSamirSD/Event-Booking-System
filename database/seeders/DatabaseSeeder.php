<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->count(2)->state(['role' => 'admin'])->create();
        User::factory()->count(3)->state(['role' => 'organizer'])->create();
        User::factory()->count(10)->state(['role' => 'customer'])->create();
        Event::factory(5)->create();
        Ticket::factory(15)->create();
        Booking::factory(20)->create();
    }
}
