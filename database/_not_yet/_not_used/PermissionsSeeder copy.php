<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Module, Permission};

class PermissionsSeeder extends Seeder {
    public function run() {
        $modules = Module::with('actions')->get();

        foreach ($modules as $module) {
            foreach ($module->actions as $action) {
                Permission::firstOrCreate([
                    'permission_key' => "{$module->key}.{$action->action_key}"
                ], [
                    'module_id'        => $module->id,
                    'module_action_id' => $action->id,
                    'name'=>$action->name
                ]);
            }
        }
    }
}