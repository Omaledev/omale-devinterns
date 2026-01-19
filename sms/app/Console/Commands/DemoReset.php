<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DemoReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Wipes the database and seeds fresh demo data for presentation.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Safety Check: Ask for confirmation
        if (!$this->confirm('⚠️  DANGER: This will ERASE ALL DATA in the database. Are you sure you want to proceed?')) {
            $this->info('Operation cancelled.');
            return;
        }

        $this->info('🔄 Starting Demo Reset...');

        // Wipe Database & Re-run Migrations
        // This deletes all tables and creates them fresh/empty
        $this->info('Wiping database...');
        $this->call('migrate:fresh');

        // Run Standard Seeders (Roles, Permissions, SuperAdmin)
        // This ensures the "System" is ready
        $this->info('Seeding roles and permissions...');
        $this->call('db:seed'); 

        // Run Demo Seeders (Fake Schools, Students, Teachers)
        // This ensures the "Content" is ready
        $this->info('Populating demo data...');
        $this->call('db:seed', [
            '--class' => 'DemoDataSeeder'
        ]);

        // Clear Caches
        // Ensures no old configuration or views are stuck
        $this->info('Clearing caches...');
        $this->call('optimize:clear');

        // Final Success Message
        $this->newLine();
        $this->info('---------------------------------------');
        $this->info('SYSTEM RESET COMPLETE!');
        $this->info('---------------------------------------');
        $this->info('Login Details:');
        $this->info('Principal: principal@demo.com / password');
        $this->info('Bursar:    bursar@demo.com    / password');
        $this->info('---------------------------------------');
    
    }
}
