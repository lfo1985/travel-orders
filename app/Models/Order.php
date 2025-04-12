<?php

namespace App\Models;

use App\Enums\StatusOrderEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $table = 'orders';
    protected $fillable = [
        'user_id',
        'costumer_name',
        'destination_name',
        'departure_date',
        'return_date',
        'status',
    ];
    protected $casts = [
        'departure_date' => 'date',
        'return_date' => 'date',
        'status' => StatusOrderEnum::class,
    ];

    /**
     * Get the user associated with the order.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Format the departure_date to 'Y-m-d' when accessed.
     * 
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function departureDate(): Attribute
    {
        return Attribute::make(
            function($value) {
                return $this->asDateTime($value)->format('d/m/Y');
            },
            function($value) {
                return $this->asDateTime($value);
            }
        );
    }

    /**
     * Format the return_date to 'Y-m-d' when accessed.
     * 
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function returnDate(): Attribute
    {
        return Attribute::make(
            function($value) {
                return $this->asDateTime($value)->format('d/m/Y');
            },
            function($value) {
                return $this->asDateTime(dateFormat($value));
            }
        );
    }

    /**
     * Format the status to a human-readable string when accessed.
     */
    public function scopeByStatus($query, StatusOrderEnum $status)
    {
        return $query->where('status', '=', $status);
    }

    /**
     * Format the status to a human-readable string when accessed.
     */
    public function scopeByUserId($query, int $id)
    {
        return $query->where('user_id', $id);
    }
}
