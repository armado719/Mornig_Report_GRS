<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Rig;
use App\Models\Pozo;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Roles
        $adminRole      = Role::firstOrCreate(['name' => 'ADMIN', 'guard_name' => 'web']);
        $rigManagerRole = Role::firstOrCreate(['name' => 'RIG_MANAGER', 'guard_name' => 'web']);

        // Catálogo de RIGs
        Rig::firstOrCreate(['numero' => '158'], ['nombre' => 'RIG 158', 'activo' => true]);
        Rig::firstOrCreate(['numero' => '160'], ['nombre' => 'RIG 160', 'activo' => true]);

        // Catálogo de Pozos
        Pozo::firstOrCreate(['nombre' => 'CASABE 1643'], [
            'operador'     => 'ECOPETROL',
            'municipio'    => 'Cantagallo',
            'departamento' => 'Bolívar',
            'activo'       => true,
        ]);
        Pozo::firstOrCreate(['nombre' => 'CASABE 1644'], [
            'operador'     => 'ECOPETROL',
            'municipio'    => 'Cantagallo',
            'departamento' => 'Bolívar',
            'activo'       => true,
        ]);

        // Usuario ADMIN
        $admin = User::firstOrCreate(
            ['email' => 'admin@grs.com'],
            [
                'nombre'   => 'Administrador GRS',
                'password' => Hash::make('Admin@GRS2026'),
                'rig'      => null,
                'activo'   => true,
            ]
        );
        $admin->assignRole($adminRole);

        // Usuario RIG MANAGER
        $rigManager = User::firstOrCreate(
            ['email' => 'rigmanager@grs.com'],
            [
                'nombre'   => 'Rig Manager RIG 158',
                'password' => Hash::make('Rig@GRS2026'),
                'rig'      => '158',
                'activo'   => true,
            ]
        );
        $rigManager->assignRole($rigManagerRole);
    }
}
