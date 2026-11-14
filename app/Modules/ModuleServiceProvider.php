<?php

namespace App\Modules;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use ReflectionClass;

/**
 * Basis provider modul (modular monolith, vertical slice).
 * Menyatukan route, view, dan komponen Livewire milik modul.
 */
abstract class ModuleServiceProvider extends ServiceProvider
{
    abstract protected function moduleAlias(): string;

    public function boot(): void
    {
        $path = $this->modulePath();

        // 1. Load Views (Contoh: view('catalog::...'))
        $this->loadViewsFrom($path.'/resources/views', $this->moduleAlias());

        // 2. Load Livewire (Contoh: <livewire:catalog::menu-manager />)
        Livewire::addNamespace(
            namespace: $this->moduleAlias(),
            viewPath: $path.'/resources/views/livewire',
        );

        // 3. Load Routes Otomatis dari folder modul
        foreach (glob($path.'/routes/*.php') ?: [] as $routeFile) {
            $this->loadRoutesFrom($routeFile);
        }
    }

    protected function modulePath(): string
    {
        return dirname((string) (new ReflectionClass($this))->getFileName());
    }
}