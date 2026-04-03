<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use FurqanSiddiqui\BIP39\BIP39;
use FurqanSiddiqui\BIP39\WordList;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo 'Starting to seed roles and users...' . PHP_EOL;

        // Create admin and vendor roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $vendorRole = Role::firstOrCreate(['name' => 'vendor']);
        echo 'Admin and Vendor roles created successfully.' . PHP_EOL;

        $userCount = 1;
        $successCount = 0;
        $errorCount = 0;

        for ($i = 1; $i <= $userCount; $i++) {
            try {
                $username = $this->generateValidUsername($i);
                $password = $this->generateValidPassword();
                $mnemonic = $this->generateMnemonic();
                $referenceId = $this->generateReferenceId();

                if ($mnemonic === false) {
                    throw new \Exception("Unable to generate mnemonic.");
                }

                $user = User::create([
                    'username' => $username,
                    'password' => Hash::make($password),
                    'mnemonic' => $mnemonic,
                    'reference_id' => $referenceId,
                ]);

                // Assign admin role to the first user
                if ($i === 1) {
                    $user->roles()->attach($adminRole);
                    echo "Admin user created successfully:" . PHP_EOL;
                } 
                // Assign vendor role to the next 3 users
                elseif ($i >= 2 && $i <= 4) {
                    $user->roles()->attach($vendorRole);
                    echo "Vendor user created successfully:" . PHP_EOL;
                }
                else {
                    echo "Regular user created successfully:" . PHP_EOL;
                }

                echo "Username: {$username}" . PHP_EOL;
                echo "Password: {$password}" . PHP_EOL;
                echo "Mnemonic: {$mnemonic}" . PHP_EOL;
                echo "Reference ID: {$referenceId}" . PHP_EOL;
                echo ($i === 1 ? "Role: Admin" : ($i >= 2 && $i <= 4 ? "Role: Vendor" : "Role: Regular User")) . PHP_EOL;
                echo "---" . PHP_EOL;

                $successCount++;
            } catch (\Exception $e) {
                echo "Error creating user{$i}: " . $e->getMessage() . PHP_EOL;
                Log::error("Error seeding user{$i}: " . $e->getMessage());
                $errorCount++;
            }
        }

        echo "Seeding completed." . PHP_EOL;
        echo "Successfully created users: {$successCount}" . PHP_EOL;
        echo "Failed to create users: {$errorCount}" . PHP_EOL;
    }

    /**
     * Generate a valid username based on the AuthController rules.
     */
    private function generateValidUsername($index): string
    {
        $baseUsername = 'user' . $index;
        $username = $baseUsername;
        
        // Ensure username is between 4 and 16 characters
        if (strlen($username) < 4) {
            $username .= Str::random(4 - strlen($username));
        } elseif (strlen($username) > 16) {
            $username = substr($username, 0, 16);
        }

        // Ensure username only contains letters and numbers
        $username = preg_replace('/[^a-zA-Z0-9]/', '', $username);

        // Check if username already exists and modify if necessary
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . Str::random(1);
            $username = substr($username, 0, 16);
        }

        return $username;
    }

    /**
     * Generate a valid password based on the AuthController rules.
     */
    private function generateValidPassword(): string
    {
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        $specialChars = '#$%&@^`~.,:;"\'\\/|_-<>*+!?=[](){}';

        $password = $lowercase[rand(0, strlen($lowercase) - 1)] . 
                    $uppercase[rand(0, strlen($uppercase) - 1)] . 
                    $numbers[rand(0, strlen($numbers) - 1)] . 
                    $specialChars[rand(0, strlen($specialChars) - 1)];

        for ($i = strlen($password); $i < 8; $i++) {
            $allChars = $lowercase . $uppercase . $numbers . $specialChars;
            $password .= $allChars[rand(0, strlen($allChars) - 1)];
        }

        return str_shuffle($password);
    }

    /**
     * Generate a secure mnemonic phrase using BIP39.
     *
     * @return string|false
     */
    protected function generateMnemonic()
    {
        try {
            // For version 0.1.7, use the Generate method with WordList::English()
            $mnemonic = BIP39::Generate(
                12,
                WordList::English()
            );
            
            if ($mnemonic === null) {
                throw new \Exception("Unable to generate mnemonic.");
            }
            
            return implode(' ', $mnemonic->words);
        } catch (\Exception $e) {
            Log::error('Failed to generate mnemonic: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate a unique reference ID.
     */
    protected function generateReferenceId(): string
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $referenceId = '';
        $characterCount = strlen($characters);
        
        for ($i = 0; $i < 16; $i++) {
            $referenceId .= $characters[random_int(0, $characterCount - 1)];
        }
        
        // Ensure there are exactly 8 letters and 8 digits
        $letters = preg_replace('/[^A-Z]/', '', $referenceId);
        $digits = preg_replace('/[^0-9]/', '', $referenceId);
        
        while (strlen($letters) < 8) {
            $letters .= $characters[random_int(0, 25)];
        }
        while (strlen($digits) < 8) {
            $digits .= $characters[random_int(26, 35)];
        }
        
        // Trim excess characters if necessary
        $letters = substr($letters, 0, 8);
        $digits = substr($digits, 0, 8);
        
        // Combine and shuffle
        $referenceId = str_shuffle($letters . $digits);
        
        return $referenceId;
    }
}
