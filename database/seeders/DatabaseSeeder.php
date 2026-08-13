<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // ===== ZARURI SEEDERS (System in ke bagair nahi chalta) =====
        $this->call(UserTypesTableSeeder::class);     // User roles (admin, teacher, etc.)
        $this->call(SettingsTableSeeder::class);      // System settings
        $this->call(UsersTableSeeder::class);         // Sirf admin accounts
        $this->call(BloodGroupsTableSeeder::class);   // Blood groups dropdown
        $this->call(NationalitiesTableSeeder::class); // Nationality dropdown
        $this->call(GradesTableSeeder::class);        // Grading system (A,B,C,D,E,F)
        $this->call(StatesTableSeeder::class);        // Pakistan Provinces
        $this->call(LgasTableSeeder::class);          // Pakistan Cities/Areas


        // ===== HATAYI GYI SEEDERS (Dummy data - aap khud add karain) =====
        // ClassTypesTableSeeder   - Class types (Nursery, Primary, etc.)
        // MyClassesTableSeeder    - Classes
        // SectionsTableSeeder     - Sections
        // SubjectsTableSeeder     - Subjects
        // SkillsTableSeeder       - Skills
        // StudentRecordsTableSeeder - Student records
        // DormsTableSeeder        - Dormitories
        // StatesTableSeeder       - States
        // LgasTableSeeder         - LGAs
    }
}
