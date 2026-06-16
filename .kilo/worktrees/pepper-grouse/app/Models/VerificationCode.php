<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code',
        'type',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Check if the code is valid (not expired)
     */
    public function isValid(): bool
    {
        return $this->expires_at->isFuture();
    }

    /**
     * Get the user that owns this verification code
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate a new 6-digit code
     */
    public static function generateCode(): string
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Create a new verification code for a user
     */
    public static function createForUser(User $user, string $type, int $expiresInMinutes = 10): self
    {
        // Delete any existing codes of the same type for this user
        self::where('user_id', $user->id)
            ->where('type', $type)
            ->delete();

        return self::create([
            'user_id' => $user->id,
            'code' => self::generateCode(),
            'type' => $type,
            'expires_at' => now()->addMinutes($expiresInMinutes),
        ]);
    }

    /**
     * Verify a code for a user
     */
    public static function verifyForUser(User $user, string $code, string $type): bool
    {
        // Ensure code is exactly 6 digits with leading zeros
        $code = str_pad(preg_replace('/[^0-9]/', '', $code), 6, '0', STR_PAD_LEFT);

        $verificationCode = self::where('user_id', $user->id)
            ->where('type', $type)
            ->where('code', $code)
            ->first();

        if (! $verificationCode || ! $verificationCode->isValid()) {
            return false;
        }

        // Delete the code after successful verification
        $verificationCode->delete();

        return true;
    }
}
