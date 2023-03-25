<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(PlanSeeder::class);
        // \App\Models\User::factory(10)->create();

        User::create([
            'name' => 'admin',
            'user_type' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('adminadmin')
        ]);
        // Schema::create('oauth_auth_codes', function(Blueprint $table)
        // {
        //     $table->increments('id');
        //     $table->unsignedBigInteger('user_id');
        //     $table->unsignedBigInteger('client_id');
        //     $table->text('scopes');
        //     $table->boolean('revoked ');
        //     $table->timestamp('expires_at')->nullable();
            
        // });
        //   Schema::create('oauth_access_tokens', function(Blueprint $table)
        // {
        //     $table->increments('id');
        //     $table->unsignedBigInteger('user_id');
        //     $table->unsignedBigInteger('client_id');
        //     $table->string('name');
        //     $table->text('scopes');
        //     $table->boolean('revoked');
        //     $table->timestamp('created_at')->nullable();
        //     $table->timestamp('expires_at')->nullable();
            
        // });
        // Schema::create('oauth_clients', function(Blueprint $table)
        // {
        //     $table->increments('id');
        //     $table->unsignedBigInteger('user_id')->nullable();
        //     $table->string('name');
        //     $table->string('secret')->nullable();
        //     $table->string('provider')->nullable();
        //     $table->text('redirect');
        //     $table->boolean('personal_access_client');
        //     $table->boolean('password_client');
        //     $table->boolean('revoked');
        //     $table->timestamp('created_at')->nullable();
        //     $table->timestamp('updated_at')->nullable();
            
        // });
        // Schema::create('oauth_personal_access_clients', function(Blueprint $table)
        // {
        //     $table->increments('id');
        //     $table->unsignedBigInteger('client_id');
        //     $table->timestamp('created_at')->nullable();
        //     $table->timestamp('updated_at')->nullable();
            
        // });
        //   Schema::create('oauth_refresh_tokens', function(Blueprint $table)
        // {
        //     $table->string('id');
        //     $table->string('access_token_id');
        //     $table->boolean('revoked');
        //     $table->timestamp('expires_at')->nullable();
            
        // });
        // \App\Models\User::factory(10)->create();
        $this->call(CitySeeder::class);
    }
}
