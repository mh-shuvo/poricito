<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\District;
use App\Models\Thana;
use App\Models\Union;
use App\Models\Ward;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $data = json_decode(file_get_contents(database_path('seeders/data/feni_district_seed.json')), true);

        $district = District::create([
            'name' => $data['district']['name'],
            'slug' => $data['district']['slug']
        ]);

        foreach ($data['district']['thanas'] as $thanaData) {

            $thana = Thana::create([
                'district_id' => $district->id,
                'name' => $thanaData['name'],
                'slug' => $thanaData['slug'],
            ]);

            foreach ($thanaData['unions'] as $unionData) {

                $union = Union::create([
                    'thana_id' => $thana->id,
                    'name' => $unionData['name'],
                    'slug' => $unionData['slug'],
                ]);

                foreach ($unionData['wards'] as $wardData) {

                    Ward::create([
                        'union_id' => $union->id,
                        'name' => $wardData['name'],
                        'slug' => $wardData['slug'],
                    ]);
                }
            }
        }
    }
}
