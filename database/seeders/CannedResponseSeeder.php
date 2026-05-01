<?php

namespace Database\Seeders;

use App\Models\CannedResponse;
use Illuminate\Database\Seeder;

class CannedResponseSeeder extends Seeder
{
    public function run(): void
    {
       if (CannedResponse::count() > 0) return;

       $responses = [
           [
               'title' => 'Escalated to Technician',
               'content' => "We have escalated your ticket to our technical department. We will provide updates as soon as possible. Thank you for your patience."
           ],
           [
               'title' => 'Request More Info',
               'content' => "Could you please provide more details or a screenshot of the error you are experiencing? This will help us diagnose the issue faster."
           ],
           [
               'title' => 'Resolved & Closing',
               'content' => "The issue has been resolved. If you do not have any further questions, we will proceed to close this ticket. Thank you!"
           ],
           [
               'title' => 'Maintenance Notice',
               'content' => "We are currently undergoing scheduled maintenance that may affect this service. The estimated completion time is 2 hours."
           ],
       ];

       foreach ($responses as $response) {
           CannedResponse::create($response);
       }
    }
}
