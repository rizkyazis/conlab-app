<?php

use App\Accounts;
use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $username = ['admin', 'teacher', 'reviewer', 'student','testing'];
        $role = ['admin', 'teacher', 'reviewer', 'student','student'];
        $email = ['admin@conlab.com', 'teacher@conlab.com', 'reviewer@conlab.com', 'student@conlab.com','testing@conlab.com'];
        $password = 'conlab123';

        $fullname = ['Admin Conlab', 'Teacher Conlab', 'Reviewer Conlab', 'Student Conlab','Testing Conlab'];
        $birth_place = ['Bandung', 'Jakarta', 'Surabaya', 'Manado','Jogjakarta'];
        $birth_date = ['0001-01-01', '1985-09-22', '1992-12-01', '2003-11-04','1999-12-12'];
        $contact = ['000000001', '0846449241', '0842864104', '0846128641','080204377424'];
        $address = ['Unknown', 'Pink House Sukabirus', 'Roemah Kita Sukapura', 'Puri Kharisma Sukabirus','Pondok 42 Sukabirus'];

        for ($i = 0; $i < 5; $i++) {
            $user = User::create([
                'username' => $username[$i],
                'email' => $email[$i],
                'password'=> Hash::make($password),
                'role'=>$role[$i]
            ]);

            Accounts::create([
                'user_id'=>$user->id,
                'fullname'=>$fullname[$i],
                'birth_place'=>$birth_place[$i],
                'birth_date'=>$birth_date[$i],
                'contact'=>$contact[$i],
                'address'=>$address[$i]
            ]);
        }
    }
}
