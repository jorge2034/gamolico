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
        // \App\Models\User::factory(10)->create();
        $this->call([
           UserSeeder::class,
            PermisoSeeder::class,
            PermisoUser::class,
            ContribuyenteSeeder::class,
            CasoSeeder::class,
            TramiteSeeder::class,
            SeguimientoSeeder::class,
            RequisitoSeeder::class,
            RequisitoTramiteSeeder::class,
            MultaSeeder::class,
            DetallemultaSeeder::class,
            SectorSeeder::class,
            ActividadSeeder::class,
            
        ]);
    }
}
