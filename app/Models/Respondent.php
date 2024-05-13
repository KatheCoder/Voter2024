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
        if ($this->national === '#MA-AFRICA PARTY') {
            return "#MA-AFRICA";
        }
        if ($this->national === 'ACTION SA') {
            return $this->national;
        }

        if ($this->national === 'GOOD') {
            return $this->national;
        }

        // Check if the provincial name is "Umkhonto weff"
        if ($this->national === 'UMKHONTO WESIZWE') {
            return 'MK';
        }
        $words = explode(' ', $this->national);
        $abbreviatedTitle = '';
        foreach ($words as $word) {
            $abbreviatedTitle .= strtoupper(substr($word, 0, 1));
        }
        return $abbreviatedTitle;
    }

    public function getAbbreviatedProvincialAttribute()
    {
         if ($this->provincial === '#MA-AFRICA PARTY') {
             return '#MA-AFRICA';
         }
         if ($this->provincial === 'ACTION SA') {
            return $this->provincial; // Return unchanged
        }
         if ($this->provincial === 'GOOD') {
            return $this->provincial; // Return unchanged
        }

        // Check if the provincial name is "Umkhonto weff"
        if ($this->provincial === 'UMKHONTO WESIZWE') {
            return 'MK'; // Return unique abbreviation
        }

        // Otherwise, generate abbreviation as before
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
