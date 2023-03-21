<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Travel extends Model
{
    protected $fillable = [
        'traveler_id',
        'department',
        'reporting_user_id',
        'approval_user_id',
        'client_id',
        'departure_branch_id',
        'destination_branch_id',
        'project_name',
        'project_manager',
        'purpose',
        'date_from',
        'date_to',
        'start_visa',
        'requirements',
        'pocket_money'
    ];
    public function traveler()
    {
        return $this->belongsTo(User::class);
    }
    public function reportingTo()
    {
        return $this->belongsTo(User::class, 'reporting_user_id', 'id');
    }
    public function approval()
    {
        return $this->belongsTo(User::class, 'approval_user_id', 'id');
    }
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }
    public function departure()
    {
        return $this->belongsTo(Branch::class, 'departure_branch_id', 'id');
    }
    public function destination()
    {
        return $this->belongsTo(Branch::class, 'destination_branch_id', 'id');
    }

    protected $casts = [
        'date_from' => 'date',
        'date_to' => 'date',
        'start_visa' => 'date',
    ];
}
