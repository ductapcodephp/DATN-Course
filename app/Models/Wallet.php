<?php
// === FILE: app/Models/Wallet.php ===

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $user_id
 * @property float $balance
 * @property User $user
 * @property \Illuminate\Database\Eloquent\Collection $transactions
 */
class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    // ===== RELATIONSHIPS =====

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    // ===== SCOPES =====

    public function scopeWithBalance($query, $minBalance)
    {
        return $query->where('balance', '>=', $minBalance);
    }

    // ===== HELPERS =====

    public function deposit($amount, $description = null, $referenceCode = null)
    {
        return $this->addTransaction('deposit', $amount, $description, 'completed', $referenceCode);
    }

    public function withdraw($amount, $description = null)
    {
        if ($this->balance < $amount) {
            throw new \Exception('Insufficient balance');
        }

        return $this->addTransaction('purchase', $amount, $description, 'completed');
    }

    public function addTransaction($type, $amount, $description = null, $status = 'pending', $referenceCode = null)
    {
        $balanceBefore = $this->balance;
        $balanceAfter = $balanceBefore + ($type === 'purchase' ? -$amount : $amount);

        if ($type === 'purchase' || $type === 'refund') {
            $balanceAfter = $balanceBefore - $amount;
        } elseif ($type === 'refund') {
            $balanceAfter = $balanceBefore + $amount;
        } else {
            $balanceAfter = $balanceBefore + $amount;
        }

        $this->update(['balance' => $balanceAfter]);

        return WalletTransaction::create([
            'wallet_id' => $this->id,
            'user_id' => $this->user_id,
            'type' => $type,
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'description' => $description,
            'reference_code' => $referenceCode,
            'status' => $status,
        ]);
    }
}
