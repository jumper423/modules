<?php

namespace Caffeinated\Modules\Traits;

trait MigrationTrait
{
    /**
     * Require (once) all migration files for the supplied module.
     *
     * @param string $module
     */
    protected function requireMigrations($module = null)
    {
        if (is_null($module)) {
            $modules = $this->module->all();
            if (count($modules) == 0) {
                return $this->error("Your application doesn't have any modules.");
            }
            $migrationsAll = [];
            foreach ($modules as $module) {
                $path = $this->getMigrationPath($module['slug']);
                $migrations = $this->laravel['files']->glob($path . '*_*.php');
                $migrationsAll = array_merge($migrationsAll, $migrations);
            }
            sort($migrationsAll);

            foreach ($migrationsAll as $migration) {
                $this->laravel['files']->requireOnce($migration);
            }
        } else {
            $path = $this->getMigrationPath($module);

            $migrations = $this->laravel['files']->glob($path . '*_*.php');

            foreach ($migrations as $migration) {
                $this->laravel['files']->requireOnce($migration);
            }
        }
    }

    /**
     * Get migration directory path.
     *
     * @param string $module
     *
     * @return string
     */
    protected function getMigrationPath($module)
    {
        $path = $this->laravel['modules']->getModulePath($module);

        return $path.'Database/Migrations/';
    }
}
