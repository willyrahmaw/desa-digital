<?php

namespace App\Services;

use App\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthService
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login(array $credentials, string $ipAddress, string $userAgent): bool
    {
        $email = $credentials['email'] ?? '';
        $password = $credentials['password'] ?? '';

        $user = $this->userRepository->findByEmail($email);

        if ($user && Hash::check($password, $user->password)) {
            if (!$user->status_aktif) {
                $this->logLogin($user->id, $email, $ipAddress, $userAgent, 'failed');
                return false;
            }

            Auth::login($user, $credentials['remember'] ?? false);
            $this->logLogin($user->id, $email, $ipAddress, $userAgent, 'success');
            return true;
        }

        $this->logLogin($user?->id, $email, $ipAddress, $userAgent, 'failed');
        return false;
    }

    public function logout(): void
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }

    private function logLogin(?int $userId, string $email, string $ipAddress, string $userAgent, string $status): void
    {
        try {
            DB::table('login_log')->insert([
                'user_id' => $userId,
                'email' => $email,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'status' => $status,
                'login_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Silently catch
        }
    }
}
