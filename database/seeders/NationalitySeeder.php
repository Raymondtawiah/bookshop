<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NationalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nationalities = [
            // African Countries
            ['name' => 'Ghanaian', 'country' => 'Ghana'],
            ['name' => 'Nigerian', 'country' => 'Nigeria'],
            ['name' => 'Kenyan', 'country' => 'Kenya'],
            ['name' => 'South African', 'country' => 'South Africa'],
            ['name' => 'Togolese', 'country' => 'Togo'],
            ['name' => 'Ivorian', 'country' => "Côte d'Ivoire"],
            ['name' => 'Burkinabe', 'country' => 'Burkina Faso'],
            ['name' => 'Liberian', 'country' => 'Liberia'],
            ['name' => 'Sierra Leonean', 'country' => 'Sierra Leone'],
            ['name' => 'Cameroonian', 'country' => 'Cameroon'],
            ['name' => 'Senegalese', 'country' => 'Senegal'],
            ['name' => 'Malian', 'country' => 'Mali'],
            ['name' => 'Ethiopian', 'country' => 'Ethiopia'],
            ['name' => 'Tanzanian', 'country' => 'Tanzania'],
            ['name' => 'Ugandan', 'country' => 'Uganda'],
            ['name' => 'Zimbabwean', 'country' => 'Zimbabwe'],
            ['name' => 'Zambian', 'country' => 'Zambia'],
            ['name' => 'Mozambican', 'country' => 'Mozambique'],
            ['name' => 'Angolan', 'country' => 'Angola'],
            ['name' => 'Nigerien', 'country' => 'Niger'],
            ['name' => 'Chadian', 'country' => 'Chad'],
            ['name' => 'Congolese', 'country' => 'DR Congo'],
            ['name' => 'Rwandan', 'country' => 'Rwanda'],
            ['name' => 'Burundian', 'country' => 'Burundi'],
            ['name' => 'Beninese', 'country' => 'Benin'],
            ['name' => 'Gambian', 'country' => 'Gambia'],
            ['name' => 'Guinean', 'country' => 'Guinea'],
            ['name' => 'Malawian', 'country' => 'Malawi'],
            ['name' => 'Botswanan', 'country' => 'Botswana'],
            ['name' => 'Namibian', 'country' => 'Namibia'],
            ['name' => 'Lesotho', 'country' => 'Lesotho'],
            ['name' => 'Swazi', 'country' => 'Eswatini'],
            ['name' => 'Mauritian', 'country' => 'Mauritius'],
            ['name' => 'Seychellois', 'country' => 'Seychelles'],
            ['name' => 'Comorian', 'country' => 'Comoros'],
            ['name' => 'Madagascan', 'country' => 'Madagascar'],
            ['name' => 'Djiboutian', 'country' => 'Djibouti'],
            ['name' => 'Eritrean', 'country' => 'Eritrea'],
            ['name' => 'Somali', 'country' => 'Somalia'],
            ['name' => 'Sudanese', 'country' => 'Sudan'],
            ['name' => 'South Sudanese', 'country' => 'South Sudan'],
            ['name' => 'Egyptian', 'country' => 'Egypt'],
            ['name' => 'Moroccan', 'country' => 'Morocco'],
            ['name' => 'Algerian', 'country' => 'Algeria'],
            ['name' => 'Tunisian', 'country' => 'Tunisia'],
            ['name' => 'Libyan', 'country' => 'Libya'],

            // Other Countries
            ['name' => 'American', 'country' => 'United States'],
            ['name' => 'British', 'country' => 'United Kingdom'],
            ['name' => 'Canadian', 'country' => 'Canada'],
            ['name' => 'Australian', 'country' => 'Australia'],
            ['name' => 'Indian', 'country' => 'India'],
            ['name' => 'Chinese', 'country' => 'China'],
            ['name' => 'Japanese', 'country' => 'Japan'],
            ['name' => 'German', 'country' => 'Germany'],
            ['name' => 'French', 'country' => 'France'],
            ['name' => 'Italian', 'country' => 'Italy'],
            ['name' => 'Spanish', 'country' => 'Spain'],
            ['name' => 'Portuguese', 'country' => 'Portugal'],
            ['name' => 'Brazilian', 'country' => 'Brazil'],
            ['name' => 'Mexican', 'country' => 'Mexico'],
            ['name' => 'Other', 'country' => 'Other'],
        ];

        foreach ($nationalities as $nationality) {
            DB::table('nationalities')->insert([
                'name' => $nationality['name'],
                'country' => $nationality['country'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
