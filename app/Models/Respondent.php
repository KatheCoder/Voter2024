<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Respondent extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $timestamps = true;
    protected $table = 'respondents';

    public function getAbbreviatedNationalAttribute()
    {
        $words = explode(' ', $this->national);
        $abbreviatedTitle = '';
        foreach ($words as $word) {
            $abbreviatedTitle .= strtoupper(substr($word, 0, 1));
        }
        return $abbreviatedTitle;
    }
    public function getAbbreviatedProvincialAttribute()
    {
        $words = explode(' ', $this->provincial);
        $abbreviatedTitle = '';
        foreach ($words as $word) {
            $abbreviatedTitle .= strtoupper(substr($word, 0, 1));
        }
        return $abbreviatedTitle;
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = now();
            $model->updated_at = now();
        });

        static::updating(function ($model) {
            $model->updated_at = now();
        });
    }
}
