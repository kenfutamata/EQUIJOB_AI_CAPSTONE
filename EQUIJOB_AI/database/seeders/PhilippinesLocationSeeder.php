<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PhilippinesLocationSeeder extends Seeder
{
    public function run(): void
    {
        // Provinces + Cities
        $locations = [
            "Ilocos Norte" => ["Adams", "Bacarra", "Badoc", "Bangui", "Currimao", "Dingras", "Laoag City", "Pagudpud", "San Nicolas", "Sarrat"],
            "Ilocos Sur" => ["Alilem", "Banayoyo", "Bantay", "Candon City", "Caoayan", "Cervantes", "Galimuyod", "Narvacan", "San Juan", "Santa"],
            "La Union" => ["Agoo", "Aringay", "Bacnotan", "Balaoan", "Bangar", "Bauang", "Naguilian", "San Fernando City"],
            "Pangasinan" => ["Alaminos City", "Basista", "Binmaley", "Dagupan City", "Lingayen", "Manaoag", "Mangaldan", "San Carlos City", "Urdaneta City"],

            "Batanes" => ["Basco", "Itbayat", "Ivana", "Mahatao", "Sabtang", "Uyugan"],
            "Cagayan" => ["Aparri", "Baggao", "Gonzaga", "Iguig", "Lal-lo", "Peñablanca", "Tuguegarao City"],
            "Isabela" => ["Alicia", "Angadanan", "Aurora", "Cabatuan", "Cauayan City", "Ilagan City", "Santiago City"],
            "Quirino" => ["Aglipay", "Cabarroguis", "Diffun", "Maddela"],
            "Nueva Vizcaya" => ["Ambaguio", "Aritao", "Bagabag", "Bayombong", "Solano", "Kasibu"],

            "Aurora" => ["Baler", "Casiguran", "Dilasag", "Dinalungan", "Dipaculao", "Maria Aurora"],
            "Bataan" => ["Abucay", "Bagac", "Balanga City", "Dinalupihan", "Hermosa", "Limay", "Mariveles", "Orani", "Samal"],
            "Bulacan" => ["Angat", "Baliuag", "Bocaue", "Bulakan", "Guiguinto", "Malolos City", "Meycauayan City", "San Jose del Monte City"],
            "Nueva Ecija" => ["Aliaga", "Bongabon", "Cabanatuan City", "Gapan City", "Guimba", "San Jose City", "Science City of Muñoz"],
            "Pampanga" => ["Angeles City", "Apalit", "Arayat", "Bacolor", "Floridablanca", "Guagua", "Lubao", "San Fernando City"],
            "Tarlac" => ["Bamban", "Camiling", "Capas", "Concepcion", "Paniqui", "Tarlac City"],
            "Zambales" => ["Botolan", "Castillejos", "Iba", "Masinloc", "Olongapo City", "San Marcelino"],
            "Cavite" => ["Alfonso", "Amadeo", "Bacoor City", "Carmona", "Dasmariñas City", "General Trias City", "Imus City", "Tagaytay City", "Tanza"],
            "Laguna" => ["Alaminos", "Bay", "Biñan City", "Calamba City", "San Pablo City", "Santa Cruz", "Santa Rosa City"],
            "Batangas" => ["Agoncillo", "Alitagtag", "Balayan", "Batangas City", "Lemery", "Lipa City", "Nasugbu", "San Jose", "Tanauan City"],
            "Rizal" => ["Angono", "Antipolo City", "Binangonan", "Cainta", "Morong", "Taytay", "Tanay"],
            "Quezon" => ["Agdangan", "Burdeos", "Candelaria", "Lucena City", "Mauban", "Sariaya", "Tayabas City"],

            "Marinduque" => ["Boac", "Buenavista", "Gasan", "Mogpog", "Santa Cruz", "Torrijos"],
            "Occidental Mindoro" => ["Abra de Ilog", "Calintaan", "Lubang", "Mamburao", "Sablayan", "San Jose"],
            "Oriental Mindoro" => ["Baco", "Bansud", "Bongabong", "Calapan City", "Pinamalayan", "Roxas"],
            "Palawan" => ["Aborlan", "Brooke’s Point", "Coron", "El Nido", "Puerto Princesa City", "Roxas", "Taytay"],
            "Romblon" => ["Alcantara", "Banton", "Cajidiocan", "Corcuera", "Odiongan", "Romblon"],

            "Aklan" => ["Altavas", "Banga", "Batan", "Kalibo", "Malay", "New Washington"],
            "Antique" => ["Anini-y", "Barbaza", "Belison", "Culasi", "Hamtic", "San Jose"],
            "Capiz" => ["Cuartero", "Dao", "Ivisan", "Panay", "Pontevedra", "Roxas City"],
            "Guimaras" => ["Buenavista", "Jordan", "Nueva Valencia", "San Lorenzo", "Sibunag"],
            "Iloilo" => ["Ajuy", "Alimodian", "Dingle", "Janiuay", "Passi City", "Pototan", "San Joaquin"],
            "Negros Occidental" => ["Bago City", "Bacolod City", "Cadiz City", "Himamaylan City", "Kabankalan City", "Talisay City", "Victorias City"],
            "Negros Oriental" => ["Amlan", "Bacong", "Bais City", "Dumaguete City", "Guihulngan City", "Tanjay City"],

            "Cebu" => ["Alcantara", "Bantayan", "Bogo City", "Carcar City", "Cebu City", "Danao City", "Lapu-Lapu City", "Mandaue City", "Naga City", "Toledo City"],
            "Bohol" => ["Alburquerque", "Anda", "Baclayon", "Balilihan", "Carmen", "Jagna", "Tagbilaran City"],
            "Siquijor" => ["Enrique Villanueva", "Larena", "Lazi", "Maria", "San Juan", "Siquijor"],

            "Leyte" => ["Abuyog", "Alangalang", "Babatngon", "Baybay City", "Tacloban City", "Tanauan"],
            "Southern Leyte" => ["Anahawan", "Hinunangan", "Maasin City", "Macrohon", "Saint Bernard", "San Juan"],
            "Northern Samar" => ["Allen", "Catarman", "Laoang", "San Antonio"],
            "Eastern Samar" => ["Arteche", "Balangiga", "Borongan City", "Can-avid"],
            "Samar" => ["Almagro", "Basey", "Calbayog City", "Catbalogan City"],

            "Zamboanga del Norte" => ["Bacungan", "Dipolog City", "Dapitan City", "Katipunan", "Polanco"],
            "Zamboanga del Sur" => ["Bayog", "Pagadian City", "Zamboanga City"],
            "Zamboanga Sibugay" => ["Alicia", "Diplahan", "Imelda", "Ipil"],

            "Bukidnon" => ["Baungon", "Damulog", "Don Carlos", "Malaybalay City", "Manolo Fortich", "Valencia City"],
            "Camiguin" => ["Catarman", "Guinsiliban", "Mahinog", "Mambajao"],
            "Lanao del Norte" => ["Bacolod", "Kapatagan", "Iligan City", "Tubod"],
            "Misamis Occidental" => ["Aloran", "Baliangao", "Oroquieta City", "Ozamiz City"],
            "Misamis Oriental" => ["Alubijid", "Balingasag", "Cagayan de Oro City", "El Salvador City"],

            "Davao del Norte" => ["Asuncion", "Island Garden City of Samal", "Panabo City", "Tagum City"],
            "Davao del Sur" => ["Bansalan", "Davao City", "Digos City", "Hagonoy"],
            "Davao Oriental" => ["Baganga", "Banaybanay", "Mati City"],
            "Davao Occidental" => ["Don Marcelino", "Jose Abad Santos", "Malita"],
            "Davao de Oro" => ["Compostela", "Maco", "Monkayo", "Nabunturan"],

            "Cotabato" => ["Alamada", "Aleosan", "Kabacan", "Kidapawan City", "Matalam"],
            "Sarangani" => ["Alabel", "Glan", "Kiamba", "Maasim"],
            "South Cotabato" => ["Banga", "General Santos City", "Koronadal City", "Tupi"],
            "Sultan Kudarat" => ["Bagumbayan", "Columbio", "Isulan", "Tacurong City"],

            "Agusan del Norte" => ["Buenavista", "Butuan City", "Cabadbaran City", "Las Nieves"],
            "Agusan del Sur" => ["Bayugan City", "Bunawan", "San Francisco", "Trento"],
            "Surigao del Norte" => ["Alegria", "Bacuag", "Dapa", "Surigao City"],
            "Surigao del Sur" => ["Barobo", "Bislig City", "Tandag City"],
        ];

        foreach ($locations as $province => $cities) {

            // Insert PROVINCE using your actual column name
            $provinceId = DB::table('provinces')->insertGetId([
                'provinceName' => $province
            ]);

            // Insert CITIES using your actual column names
            foreach ($cities as $city) {
                DB::table('cities')->insert([
                    'provinceId' => $provinceId,
                    'cityName' => $city
                ]);
            }
        }
    }
}
