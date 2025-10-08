<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Interfaces\HealthcareProfessionalRepositoryInterface;
use App\Repositories\Eloquent\HealthcareProfessionalRepository;
use App\Repositories\Interfaces\AppointmentRepositoryInterface;
use App\Repositories\Eloquent\AppointmentRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );

        $this->app->bind(
            HealthcareProfessionalRepositoryInterface::class,
            HealthcareProfessionalRepository::class
        );

        $this->app->bind(
            AppointmentRepositoryInterface::class,
            AppointmentRepository::class
        );
    }

    public function boot(): void
    {
        //
    }
}
