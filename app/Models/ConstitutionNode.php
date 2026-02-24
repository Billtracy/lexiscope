<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConstitutionNode extends Model
{
    protected $guarded = [];

    protected $casts = [
        'keywords' => 'array',
        'verified_at' => 'datetime',
        'chapter_sort' => 'float',
        'section_sort' => 'float',
        'subsection_sort' => 'float',
    ];

    protected static function booted()
    {
        static::saving(function ($node) {
            $node->chapter_sort = static::parseSortValue($node->chapter_number, true);
            $node->section_sort = static::parseSortValue($node->section_number);
            $node->subsection_sort = static::parseSortValue($node->subsection_number);
        });
    }

    public static function parseSortValue($val, $isRoman = false)
    {
        if (empty($val)) return 0;

        $val = strtoupper(trim($val));
        $val = str_replace(['(', ')'], '', $val);

        if ($isRoman && preg_match('/^[IVXLCDM]+$/', $val)) {
            $romans = ['M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1];
            $result = 0;
            foreach ($romans as $key => $value) {
                while (strpos($val, $key) === 0) {
                    $result += $value;
                    $val = substr($val, strlen($key));
                }
            }
            return (float) $result;
        }

        if (preg_match('/^(\d+)(.*)/', $val, $matches)) {
            $num = (float) $matches[1];
            if (!empty($matches[2])) {
                $chars = str_split($matches[2]);
                $modifier = 0;
                foreach($chars as $i => $char) {
                    $alphaVal = ord($char) - 64;
                    if ($alphaVal > 0 && $alphaVal <= 26) {
                        $modifier += $alphaVal / pow(100, $i + 1);
                    }
                }
                $num += $modifier;
            }
            return $num;
        }

        if (preg_match('/^[A-Z]$/', $val)) {
            return (float) (ord($val[0]) - 64);
        }

        return 0.0;
    }

    public function caseLaws()
    {
        return $this->hasMany(CaseLawReference::class);
    }

    public function internationalComparisons()
    {
        return $this->hasMany(InternationalComparison::class);
    }

    public function parent()
    {
        return $this->belongsTo(ConstitutionNode::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ConstitutionNode::class, 'parent_id');
    }

    public function lockedBy()
    {
        return $this->belongsTo(User::class, 'locked_by');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
