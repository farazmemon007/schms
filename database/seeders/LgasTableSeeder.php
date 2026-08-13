<?php
namespace Database\Seeders;

use App\Models\Lga;
use App\Models\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class LgasTableSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('lgas')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // State IDs: 1=Punjab, 2=Sindh, 3=KPK, 4=Balochistan, 5=AJK, 6=GB, 7=Islamabad
        $cities = [

            // ===== 1. PUNJAB =====
            ['state_id' => 1, 'name' => 'Lahore'],
            ['state_id' => 1, 'name' => 'Faisalabad'],
            ['state_id' => 1, 'name' => 'Rawalpindi'],
            ['state_id' => 1, 'name' => 'Gujranwala'],
            ['state_id' => 1, 'name' => 'Multan'],
            ['state_id' => 1, 'name' => 'Sialkot'],
            ['state_id' => 1, 'name' => 'Bahawalpur'],
            ['state_id' => 1, 'name' => 'Sargodha'],
            ['state_id' => 1, 'name' => 'Sheikhupura'],
            ['state_id' => 1, 'name' => 'Jhang'],
            ['state_id' => 1, 'name' => 'Rahim Yar Khan'],
            ['state_id' => 1, 'name' => 'Gujrat'],
            ['state_id' => 1, 'name' => 'Kasur'],
            ['state_id' => 1, 'name' => 'Okara'],
            ['state_id' => 1, 'name' => 'Sahiwal'],
            ['state_id' => 1, 'name' => 'Wah Cantt'],
            ['state_id' => 1, 'name' => 'Dera Ghazi Khan'],
            ['state_id' => 1, 'name' => 'Mianwali'],
            ['state_id' => 1, 'name' => 'Jhelum'],
            ['state_id' => 1, 'name' => 'Attock'],
            ['state_id' => 1, 'name' => 'Chakwal'],
            ['state_id' => 1, 'name' => 'Khanewal'],
            ['state_id' => 1, 'name' => 'Hafizabad'],
            ['state_id' => 1, 'name' => 'Chiniot'],
            ['state_id' => 1, 'name' => 'Pakpattan'],
            ['state_id' => 1, 'name' => 'Muzaffargarh'],
            ['state_id' => 1, 'name' => 'Lodhran'],
            ['state_id' => 1, 'name' => 'Vehari'],
            ['state_id' => 1, 'name' => 'Narowal'],
            ['state_id' => 1, 'name' => 'Mandi Bahauddin'],
            ['state_id' => 1, 'name' => 'Nankana Sahib'],
            ['state_id' => 1, 'name' => 'Toba Tek Singh'],
            ['state_id' => 1, 'name' => 'Bahawalnagar'],
            ['state_id' => 1, 'name' => 'Layyah'],
            ['state_id' => 1, 'name' => 'Khushab'],
            ['state_id' => 1, 'name' => 'Bhakkar'],
            ['state_id' => 1, 'name' => 'Rajanpur'],

            // ===== 2. SINDH =====
            ['state_id' => 2, 'name' => 'Karachi'],
            ['state_id' => 2, 'name' => 'Hyderabad'],
            ['state_id' => 2, 'name' => 'Sukkur'],
            ['state_id' => 2, 'name' => 'Larkana'],
            ['state_id' => 2, 'name' => 'Nawabshah'],
            ['state_id' => 2, 'name' => 'Mirpur Khas'],
            ['state_id' => 2, 'name' => 'Shaheed Benazirabad'],
            ['state_id' => 2, 'name' => 'Jacobabad'],
            ['state_id' => 2, 'name' => 'Shikarpur'],
            ['state_id' => 2, 'name' => 'Khairpur'],
            ['state_id' => 2, 'name' => 'Dadu'],
            ['state_id' => 2, 'name' => 'Sanghar'],
            ['state_id' => 2, 'name' => 'Badin'],
            ['state_id' => 2, 'name' => 'Tharparkar'],
            ['state_id' => 2, 'name' => 'Umerkot'],
            ['state_id' => 2, 'name' => 'Thatta'],
            ['state_id' => 2, 'name' => 'Matiari'],
            ['state_id' => 2, 'name' => 'Jamshoro'],
            ['state_id' => 2, 'name' => 'Ghotki'],
            ['state_id' => 2, 'name' => 'Kashmore'],
            ['state_id' => 2, 'name' => 'Tando Allahyar'],
            ['state_id' => 2, 'name' => 'Tando Muhammad Khan'],
            ['state_id' => 2, 'name' => 'Qambar Shahdadkot'],

            // ===== 3. KHYBER PAKHTUNKHWA (KPK) =====
            ['state_id' => 3, 'name' => 'Peshawar'],
            ['state_id' => 3, 'name' => 'Mardan'],
            ['state_id' => 3, 'name' => 'Mingora (Swat)'],
            ['state_id' => 3, 'name' => 'Abbottabad'],
            ['state_id' => 3, 'name' => 'Mansehra'],
            ['state_id' => 3, 'name' => 'Kohat'],
            ['state_id' => 3, 'name' => 'Nowshera'],
            ['state_id' => 3, 'name' => 'Charsadda'],
            ['state_id' => 3, 'name' => 'Bannu'],
            ['state_id' => 3, 'name' => 'Dera Ismail Khan'],
            ['state_id' => 3, 'name' => 'Swabi'],
            ['state_id' => 3, 'name' => 'Haripur'],
            ['state_id' => 3, 'name' => 'Battagram'],
            ['state_id' => 3, 'name' => 'Hangu'],
            ['state_id' => 3, 'name' => 'Lakki Marwat'],
            ['state_id' => 3, 'name' => 'Malakand'],
            ['state_id' => 3, 'name' => 'Chitral'],
            ['state_id' => 3, 'name' => 'Dir Upper'],
            ['state_id' => 3, 'name' => 'Dir Lower'],
            ['state_id' => 3, 'name' => 'Tank'],
            ['state_id' => 3, 'name' => 'Buner'],
            ['state_id' => 3, 'name' => 'Shangla'],
            ['state_id' => 3, 'name' => 'Torghar'],
            ['state_id' => 3, 'name' => 'Kohistan'],

            // ===== 4. BALOCHISTAN =====
            ['state_id' => 4, 'name' => 'Quetta'],
            ['state_id' => 4, 'name' => 'Turbat'],
            ['state_id' => 4, 'name' => 'Khuzdar'],
            ['state_id' => 4, 'name' => 'Gwadar'],
            ['state_id' => 4, 'name' => 'Hub'],
            ['state_id' => 4, 'name' => 'Chaman'],
            ['state_id' => 4, 'name' => 'Zhob'],
            ['state_id' => 4, 'name' => 'Kharan'],
            ['state_id' => 4, 'name' => 'Sibi'],
            ['state_id' => 4, 'name' => 'Loralai'],
            ['state_id' => 4, 'name' => 'Mastung'],
            ['state_id' => 4, 'name' => 'Kalat'],
            ['state_id' => 4, 'name' => 'Nushki'],
            ['state_id' => 4, 'name' => 'Panjgur'],
            ['state_id' => 4, 'name' => 'Washuk'],
            ['state_id' => 4, 'name' => 'Lasbela'],
            ['state_id' => 4, 'name' => 'Awaran'],
            ['state_id' => 4, 'name' => 'Dera Bugti'],
            ['state_id' => 4, 'name' => 'Kech'],
            ['state_id' => 4, 'name' => 'Musakhel'],

            // ===== 5. AZAD JAMMU & KASHMIR (AJK) =====
            ['state_id' => 5, 'name' => 'Muzaffarabad'],
            ['state_id' => 5, 'name' => 'Mirpur'],
            ['state_id' => 5, 'name' => 'Rawalakot'],
            ['state_id' => 5, 'name' => 'Bagh'],
            ['state_id' => 5, 'name' => 'Kotli'],
            ['state_id' => 5, 'name' => 'Bhimber'],
            ['state_id' => 5, 'name' => 'Pallandri'],
            ['state_id' => 5, 'name' => 'Haveli'],
            ['state_id' => 5, 'name' => 'Neelum'],
            ['state_id' => 5, 'name' => 'Hattian Bala'],

            // ===== 6. GILGIT-BALTISTAN (GB) =====
            ['state_id' => 6, 'name' => 'Gilgit'],
            ['state_id' => 6, 'name' => 'Skardu'],
            ['state_id' => 6, 'name' => 'Hunza'],
            ['state_id' => 6, 'name' => 'Ghanche'],
            ['state_id' => 6, 'name' => 'Ghizer'],
            ['state_id' => 6, 'name' => 'Astore'],
            ['state_id' => 6, 'name' => 'Diamer'],
            ['state_id' => 6, 'name' => 'Shigar'],
            ['state_id' => 6, 'name' => 'Kharmang'],
            ['state_id' => 6, 'name' => 'Nagar'],

            // ===== 7. ISLAMABAD CAPITAL TERRITORY =====
            ['state_id' => 7, 'name' => 'Islamabad (F-6)'],
            ['state_id' => 7, 'name' => 'Islamabad (G-6)'],
            ['state_id' => 7, 'name' => 'Islamabad (G-7)'],
            ['state_id' => 7, 'name' => 'Islamabad (G-8)'],
            ['state_id' => 7, 'name' => 'Islamabad (G-9)'],
            ['state_id' => 7, 'name' => 'Islamabad (G-10)'],
            ['state_id' => 7, 'name' => 'Islamabad (G-11)'],
            ['state_id' => 7, 'name' => 'Islamabad (I-8)'],
            ['state_id' => 7, 'name' => 'Islamabad (I-9)'],
            ['state_id' => 7, 'name' => 'Islamabad (I-10)'],
            ['state_id' => 7, 'name' => 'Blue Area'],
            ['state_id' => 7, 'name' => 'Rawat'],
            ['state_id' => 7, 'name' => 'Kahuta'],
            ['state_id' => 7, 'name' => 'Taxila'],

        ];

        foreach ($cities as $city) {
            Lga::create(['state_id' => $city['state_id'], 'name' => $city['name']]);
        }
    }

}
