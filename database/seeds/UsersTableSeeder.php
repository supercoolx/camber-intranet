<?php
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(!\App\User::where('name', 'admin')->get()->first()) {
            // $admin = factory(App\User::class)->create([
            //     'name' => 'admin',
            //     'email' => 'nutscracker_i@yahoo.com',
            //     'password' => bcrypt('admin'),
            // ]);
            $admin = factory(App\User::class)->create([
                'name' => 'admin',
                'email' => 'Assistant@CamberRealty.com',
                'password' => bcrypt('admin'),
            ]);

            $admin->addRole('admin');
            $admin->addRole('agent', $admin->getEncodeId());

            //assistants
            $assistant = factory(\App\User::class)->create([
                'name' => 'Mark',
                'email' => 'Mark@CamberRealty.com',
                'password' => '$2y$10$2PrgXCRk2QFVXIOcZjth6ObwURII2Uwr6USohjNG8P5fgblRRLayS',
            ]);

            $assistant->addRole('assistant');

            // $assistant = factory(\App\User::class)->create([
            //     'name' => 'Dmitry Assistant',
            //     'email' => 'nutscracker2009@gmail.com',
            //     'password' => bcrypt('some'),
            // ]);

            // $assistant->addRole('assistant');

            //agents
            $agent = factory(\App\User::class)->create([
                'name' => 'Nicole Benedict',
                'email' => 'Nicole@CamberRealty.com',
                'password' => '$2y$10$OfELlkali7FpeH9xBdeBhOoEZZmUID0hsnvft3BohG4069KTpl6wO',
            ]);

            $agent->addRole('agent', $agent->getEncodeId());

            $agent = factory(\App\User::class)->create([
                'name' => 'Mark Cramer',
                'email' => 'mark@markcramer.com',
                'password' => '$2y$10$KkWxdu/CcmgpN8ED9ISjK.o1uMfQAXr1TMeLi0COvhN/jDPIY7ROS',
            ]);

            $agent->addRole('agent', $agent->getEncodeId());

            // $agent = factory(\App\User::class)->create([
            //     'name' => 'Dmitry Agent',
            //     'email' => 'nutscracker87@gmail.com',
            //     'password' => bcrypt('some'),
            // ]);

            // $agent->addRole('agent');
        }
    }
}
