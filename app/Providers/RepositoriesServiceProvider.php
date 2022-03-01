<?php

namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use App\Repositories\RepositoryInterfaces\UserRepositoryInterface;
use App\Repositories\RepositoryInterfaces\InvestorRepositoryInterface;
use App\Repositories\RepositoryInterfaces\AdminRepositoryInterface;
use App\Repositories\RepositoryInterfaces\EmployeeRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\InvestorRepository;
use App\Repositories\AdminRepository;
use App\Repositories\EmployeeRepository;

class RepositoriesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        $this->app->bind( UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind( InvestorRepositoryInterface::class, InvestorRepository::class);
        $this->app->bind( AdminRepositoryInterface::class, AdminRepository::class);
        $this->app->bind( EmployeeRepositoryInterface::class, EmployeeRepository::class);

    }
}
