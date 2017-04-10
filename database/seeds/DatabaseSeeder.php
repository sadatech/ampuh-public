<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        DB::table('approves')->insert([
            ['id' => 0, 'name' => 'Waiting Arina Approval'],
            ['id' => 1, 'name' => 'Waiting Loreal Approval'],
            ['id' => 2, 'name' => 'Approved'],
            ['id' => 3, 'name' => 'Waiting Arina Resign Approval'],
            ['id' => 4, 'name' => 'Waiting Loreal Resign Approval'],
            ['id' => 5, 'name' => 'Resign'],
            ['id' => 6, 'name' => 'Waiting Arina Maternity Leave Approval '],
            ['id' => 7, 'name' => 'Waiting Loreal Maternity Leave Approval '],
            ['id' => 8, 'name' => 'Maternity Leave'],
            ['id' => 9, 'name' => 'Waiting Arina Rejoin Approval'],
            ['id' => 10, 'name' => 'Waiting Loreal Rejoin Approval'],
        ]);
        DB::table('brands')->insert([
            ['name' => 'CONS'],
            ['name' => 'OAP'],
            ['name' => 'NYX'],
            ['name' => 'GAR'],
            ['name' => 'MYB'],
        ]);
    }
}
