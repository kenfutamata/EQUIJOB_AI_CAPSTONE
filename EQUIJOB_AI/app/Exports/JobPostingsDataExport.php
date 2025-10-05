<?php

namespace App\Exports;

use App\Models\JobPosting;
use Maatwebsite\Excel\Concerns\FromCollection;

class JobPostingsDataExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $search;
    protected $sort; 
    protected $direction; 

    public function __construct($search = null, $sort = 'created_at', $direction = 'desc')
    {
        $this->search = $search;
        $this->sort = $sort;
        $this->direction = $direction;    
    }   


    public function collection()
    {
        $query = JobPosting::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('jobProviderID', 'like', "%{$this->search}%")
                        ->orwhere('companyName', 'like', "%{$this->search}%")
                        ->orwhere('sex', 'like', "%{$this->search}%")
                        ->orwhere('disabilityType', 'like', "%{$this->search}%")
                        ->orwhere('educationalAttainment', 'like', "%{$this->search}%")
                        ->orwhere('salaryRange', 'like', "%{$this->search}%")
                        ->orwhere('jobPostingObjectives', 'like', "%{$this->search}%")
                        ->orwhere('experience', 'like', "%{$this->search}%")
                        ->orwhere('skills', 'like', "%{$this->search}%")
                        ->orwhere('requirements', 'like', "%{$this->search}%")
                        ->orwhere('contactPhone', 'like', "%{$this->search}%")
                        ->orwhere('contactEmail', 'like', "%{$this->search}%")
                        ->orwhere('description', 'like', "%{$this->search}%")
                        ->orwhere('requirements', 'like', "%{$this->search}%")
                        ->orwhere('remarks', 'like', "%{$this->search}%")
                        ->orwhere('workEnvironment', 'like', "%{$this->search}%")
                        ->orwhere('category', 'like', "%{$this->search}%")
                        ->orWhere('salaryRange', 'like', "%{$this->search}%")
                        ->orWhere('status', 'like', "%{$this->search}%");
                });
            });
        $sortable = ['jobProviderID', 'companyName', 'salaryRange', 'jobPostingObjectives', 'experience', 'skills', 'requirements', 'contactPhone', 'contactEmail', 'description', 'requirements', 'workEnvironment', 'category', 'status'];
        $sort = in_array($this->sort, $sortable) ? $this->sort : 'created_at';
        $direction = $this->direction === 'desc' ? 'desc' : 'asc';
        $query->orderBy($sort, $direction);
        return $query->get(['jobProviderID', 'companyName', 'salaryRange', 'jobPostingObjectives', 'experience', 'skills', 'requirements', 'contactPhone', 'contactEmail', 'description', 'requirements', 'workEnvironment', 'category',  'status']);
    }
}
