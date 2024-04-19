<?php 

namespace App\Services;

use App\Repositories\LeadRepository;
use Illuminate\Support\Arr;

class LeadService {
    protected LeadRepository $lead;

    public function __construct(LeadRepository $lead)
    {
        $this->lead = $lead;
    }

    public function setLead(array $data) 
    {
        return $this->lead->create($data);
    }
}