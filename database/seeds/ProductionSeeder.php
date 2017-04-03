<?php

use Illuminate\Database\Seeder;
use App\User;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		//make an admin
		$user = new User;
		$user->username = "DietLah!";
		$user->email = "team@dietlah.sg";
		$user->password = Hash::make("!dietlahpassword@");
		$user->profile_pic = "";
		$user->bio = "DietLah! Administrator";
		$user->is_admin = true;
		$user->save();
    }
 
}
