<?php

namespace App\Models\Trading;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExchangeDataset extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'exchange_datasets';

    protected $fillable = [
        'creator_id',
        'exchange_id',
        'symbol_id',
        'interval',
        'started_at',
        'ended_at'
    ];

    protected $hidden = [
    ];

    /**
     * User account that created the dataset
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
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