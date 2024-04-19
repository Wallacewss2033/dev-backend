<?php 

namespace App\Repositories;

use App\Interfaces\LeadInterface;
use App\Models\Lead;

class LeadRepository implements LeadInterface {
    
    public function create(array $data)
    {
        return Lead::create($data);    
    }
}