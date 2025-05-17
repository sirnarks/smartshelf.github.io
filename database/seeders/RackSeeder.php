<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rack;

class RackSeeder extends Seeder
{
    public function run(): void
    {
        $racks = ['A–E', 'F–J', 'K–O', 'P–T', 'U–Z', 'General'];

        foreach ($racks as $rackName) {
            Rack::firstOrCreate(['name' => $rackName]);
        }
    }
}
