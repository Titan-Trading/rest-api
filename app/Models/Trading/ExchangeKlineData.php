<?php

namespace App\Models\Trading;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExchangeKlineData extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'exchange_dataset_klines';

    protected $fillable = [
        'creator_id',
        'exchange_dataset_id',
        'exchange_id',
        'symbol_id',
        'interval',
        'open',
        'high',
        'low',
        'close',
        'volume',
        'base_volume',
        'timestamp',
        'date'
    ];

    protected $hidden = [

    ];

    /**
     * User account that created the dataset data
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Dataset the data is for
     */
    public function dataset()
    {
        return $this->belongsTo(ExchangeDataset::class, 'exchange_dataset_id');
    }

    /**
     * Exchange that the data is for
     */
    public function exchange()
    {
        return $this->belongsTo(Exchange::class);
    }

    /**
     * Symbol that the data is found
     *
     * @return void
     */
    public function symbol()
    {
        return $this->belongsTo(Symbol::class);
    }
}