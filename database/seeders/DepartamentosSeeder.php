<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartamentosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::update("INSERT INTO departamentos VALUES (null, 'Alta Verapaz');");
        DB::update("INSERT INTO departamentos VALUES (null, 'Baja Verapaz');");
        DB::update("INSERT INTO departamentos VALUES (null, 'Chimaltenango');");
        DB::update("INSERT INTO departamentos VALUES (null, 'Chiquimula');");
        DB::update("INSERT INTO departamentos VALUES (null, 'El Progreso');");
        DB::update("INSERT INTO departamentos VALUES (null, 'Escuintla');");
        DB::update("INSERT INTO departamentos VALUES (null, 'Guatemala');");
        DB::update("INSERT INTO departamentos VALUES (null, 'Huehuetenango');");
        DB::update("INSERT INTO departamentos VALUES (null, 'Izabal');");
        DB::update("INSERT INTO departamentos VALUES (null, 'Jalapa');");
        DB::update("INSERT INTO departamentos VALUES (null, 'Jutiapa');");
        DB::update("INSERT INTO departamentos VALUES (null, 'Petén');");
        DB::update("INSERT INTO departamentos VALUES (null, 'Quetzaltenango');");
        DB::update("INSERT INTO departamentos VALUES (null, 'Quiché');");
        DB::update("INSERT INTO departamentos VALUES (null, 'Retalhuleu');");
        DB::update("INSERT INTO departamentos VALUES (null, 'Sacatepéquez');");
        DB::update("INSERT INTO departamentos VALUES (null, 'San Marcos');");
        DB::update("INSERT INTO departamentos VALUES (null, 'Santa Rosa');");
        DB::update("INSERT INTO departamentos VALUES (null, 'Solola');");
        DB::update("INSERT INTO departamentos VALUES (null, 'Suchitepéquez');");
        DB::update("INSERT INTO departamentos VALUES (null, 'Totonicapán');");
        DB::update("INSERT INTO departamentos VALUES (null, 'Zacapa');");
    }
}
