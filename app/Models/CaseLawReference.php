<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseLawReference extends Model
{
    protected $guarded = [];

    public function constitutionNode()
    {
        return $this->belongsTo(ConstitutionNode::class);
    }
}
