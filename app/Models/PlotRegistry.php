<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlotRegistry extends Model
{
    protected $guarded = [];
    protected $hidden = ['id', 'created_at', 'updated_at', '_links'];
    protected $casts = [
        '_links' => 'array'
    ];

    public function scopeSearchData($query, array $ids)
    {
        return $query->whereIn('id', $ids);
    }

    public function getFormatPriceAttribute()
    {
        return format_money_value($this->price);
    }

    public function getFormatAreaAttribute()
    {
        return format_area_value($this->area);
    }
}
