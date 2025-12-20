<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PromoteUserToAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:promote {email} {--role=admin : Role to assign (admin, board, member)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Promote a user to a specific role by their email address';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $roleOption = strtolower($this->option('role'));
        
        // Map role option to role value
        $roleValue = match($roleOption) {
            'admin' => \App\Enums\UserRole::ADMIN->value,
            'board', 'board_member', 'boardmember' => \App\Enums\UserRole::BOARD_MEMBER->value,
            'member' => \App\Enums\UserRole::MEMBER->value,
            default => null,
        };
        
        if ($roleValue === null) {
            $this->error("Invalid role. Use 'admin', 'board', or 'member'.");
            return 1;
        }
        
        $user = \App\Models\User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email {$email} not found.");
            return 1;
        }
        
        if ($user->role === $roleValue) {
            $roleLabel = \App\Enums\UserRole::fromValue($roleValue)->label();
            $this->info("User {$user->name} is already a {$roleLabel}.");
            return 0;
        }
        
        $user->update(['role' => $roleValue]);
        
        $roleLabel = \App\Enums\UserRole::fromValue($roleValue)->label();
        $this->info("User {$user->name} has been promoted to {$roleLabel} successfully!");
        return 0;
    }
}
