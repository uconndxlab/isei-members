<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAuthorizedUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an authorized admin user (interactive)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creating a new authorized user...');
        $this->newLine();

        // Get user information through prompts
        $name = $this->askForName();
        $email = $this->askForEmail();
        $password = $this->askForPassword();
        $isAdmin = $this->askForAdminStatus();

        $this->newLine();
        $this->info('Creating user with the following information:');
        $this->table(
            ['Field', 'Value'],
            [
                ['Name', $name],
                ['Email', $email],
                ['Admin Status', $isAdmin ? 'Yes' : 'No'],
            ]
        );

        if (!$this->confirm('Do you want to create this user?', true)) {
            $this->info('User creation cancelled.');
            return 0;
        }

        try {
            // Create the user
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'is_admin' => $isAdmin,
            ]);

            $userType = $isAdmin ? 'admin' : 'regular';
            $this->info("Successfully created {$userType} user:");
            $this->table(
                ['Field', 'Value'],
                [
                    ['ID', $user->id],
                    ['Name', $user->name],
                    ['Email', $user->email],
                    ['Admin', $user->is_admin ? 'Yes' : 'No'],
                    ['Created', $user->created_at->format('Y-m-d H:i:s')],
                ]
            );

            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to create user: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Prompt for and validate the user's name.
     */
    private function askForName(): string
    {
        do {
            $name = $this->ask('Enter the user\'s full name');
            
            if (empty($name)) {
                $this->error('Name is required. Please try again.');
                continue;
            }
            
            if (strlen($name) > 255) {
                $this->error('Name must not exceed 255 characters. Please try again.');
                continue;
            }
            
            return $name;
        } while (true);
    }

    /**
     * Prompt for and validate the user's email.
     */
    private function askForEmail(): string
    {
        do {
            $email = $this->ask('Enter the user\'s email address');
            
            if (empty($email)) {
                $this->error('Email is required. Please try again.');
                continue;
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->error('Please enter a valid email address.');
                continue;
            }
            
            if (User::where('email', $email)->exists()) {
                $this->error('A user with this email already exists. Please try again.');
                continue;
            }
            
            return $email;
        } while (true);
    }

    /**
     * Prompt for and validate the user's password.
     */
    private function askForPassword(): string
    {
        do {
            $password = $this->secret('Enter password (minimum 8 characters)');
            
            if (empty($password)) {
                $this->error('Password is required. Please try again.');
                continue;
            }
            
            if (strlen($password) < 8) {
                $this->error('Password must be at least 8 characters long. Please try again.');
                continue;
            }
            
            $confirmPassword = $this->secret('Confirm password');
            
            if ($password !== $confirmPassword) {
                $this->error('Passwords do not match. Please try again.');
                continue;
            }
            
            return $password;
        } while (true);
    }

    /**
     * Prompt for admin status.
     */
    private function askForAdminStatus(): bool
    {
        return $this->confirm('Should this user be an admin?', true);
    }
}