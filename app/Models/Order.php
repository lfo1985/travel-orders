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
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param StatusOrderEnum $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByStatus($query, StatusOrderEnum $status)
    {
        return $query->where('status', '=', $status);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByUserId($query, int $id)
    {
        return $query->where('user_id', $id);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $destinationName
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByDestinationName($query, string $destinationName)
    {
        return $query->where('destination_name', 'LIKE', '%' . Str::lower($destinationName) . '%');
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $dateStart
     * @param string $dateEnd
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByDepartureDate($query, string $dateStart, string $dateEnd)
    {
        return $query
            ->where('departure_date', '>=', $dateStart)
            ->where('departure_date', '<=', $dateEnd);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $dateStart
     * @param string $dateEnd
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByReturnDate($query, string $dateStart, string $dateEnd)
    {
        return $query
            ->where('return_date', '>=', $dateStart)
            ->where('return_date', '<=', $dateEnd);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $departureDate
     * @param string $returnDate
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByTravelDateRange($query, string $departureDate, string $returnDate)
    {
        return $query
            ->where('departure_date', '>=', $departureDate)
            ->where('return_date', '<=', $returnDate);
    }
}
