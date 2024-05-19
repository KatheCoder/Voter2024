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
        if ($this->national === 'CONGRESS OF THE PEOPLE') {
            return 'COPE';
        }
        if ($this->national === 'POWER OF AFRICANS UNITY') {
            return 'PAU';
        }
        if ($this->national === "AZANIAN PEOPLE'S ORGANISATION") {
            return 'AZAPO';
        }
        if ($this->national === "BLACK FIRST LAND FIRST") {
            return 'BLF';
        }
        if ($this->national === "COMPATRIOTS OF SOUTH AFRICA") {
            return 'CSA';
        }
        if ($this->national === "PAN AFRICAN CONGRESS OF AZANIA") {
            return 'PAC';
        }
        if ($this->national === "RISE MZANSI") {
            return 'RISE';
        }
        if ($this->national === 'FRONT NASIONAAL/FRONT NATIONAL') {
            return 'FN';
        }
        if ($this->national === '#MA-AFRICA PARTY') {
            return "#MA-AFRICA";
        }
        if ($this->national === 'ACTION SA') {
            return $this->national;
        }

        if ($this->national === 'GOOD') {
            return $this->national;
        }
        if ($this->national === 'VRYHEIDSFRONT PLUS') {
            return 'VF PLUS';
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
        if ($this->provincial === 'CONGRESS OF THE PEOPLE') {
            return 'COPE';
        }
        if ($this->provincial === 'VRYHEIDSFRONT PLUS') {
            return 'VF PLUS';
        }
        if ($this->provincial === 'POWER OF AFRICANS UNITY') {
            return 'PAU';
        }
        if ($this->provincial === "AZANIAN PEOPLE'S ORGANISATION") {
            return 'AZAPO';
        }
        if ($this->provincial === 'BLACK FIRST LAND FIRST') {
            return 'BLF';
        }
        if ($this->provincial === 'COMPATRIOTS OF SOUTH AFRICA') {
            return 'CSA';
        }
        if ($this->provincial === 'PAN AFRICAN CONGRESS OF AZANIA') {
            return 'PAC';
        }
        if ($this->provincial === 'RISE MZANSI') {
            return 'RISE';
        }
         if ($this->provincial === 'FRONT NASIONAAL/FRONT NATIONAL') {
             return 'FN';
         }
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
