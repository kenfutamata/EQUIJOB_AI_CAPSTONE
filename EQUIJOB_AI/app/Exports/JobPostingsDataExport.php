<?php

namespace App\Exports;

use App\Models\JobPosting;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class JobPostingsDataExport implements FromCollection, WithHeadings
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

    public function headings(): array
    {
        return [
            'ID',
            'Job Provider ID',
            'Company Name',
            'Age',
            'Disability Type',
            'Educational Attainment',
            'Salary Range',
            'Job Posting Objectives',
            'Experience',
            'Skills',
            'Description',
            'Requirements',
            'Created At',
            'Updated At',
            'Contact Phone',
            'Contact Email',
            'Remarks',
            'Position',
            'Status',
            'Work Environment',
            'Sex',
            'Category',
            'Company Address',
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection(): Collection
    {

        /** @var \App\Models\User|\App\Models\JobProvider $user */
        $user = Auth::user();
        if (!$user) {
            return collect([]);
        }

        $query = JobPosting::query(); 
        $query->when($this->search, function ($query) {
            $query->where(function ($q) {
                $q->where('companyName', 'like', "%{$this->search}%")
                    ->orWhere('position', 'like', "%{$this->search}%")
                    ->orWhere('sex', 'like', "%{$this->search}%")
                    ->orWhere('disabilityType', 'like', "%{$this->search}%")
                    ->orWhere('educationalAttainment', 'like', "%{$this->search}%")
                    ->orWhere('salaryRange', 'like', "%{$this->search}%")
                    ->orWhere('jobPostingObjectives', 'like', "%{$this->search}%")
                    ->orWhere('experience', 'like', "%{$this->search}%")
                    ->orWhere('skills', 'like', "%{$this->search}%")
                    ->orWhere('requirements', 'like', "%{$this->search}%")
                    ->orWhere('contactPhone', 'like', "%{$this->search}%")
                    ->orWhere('contactEmail', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%")
                    ->orWhere('workEnvironment', 'like', "%{$this->search}%")
                    ->orWhere('category', 'like', "%{$this->search}%")
                    ->orWhere('status', 'like', "%{$this->search}%");
            });
        });

        $sortable = [
            'id',
            'companyName',
            'position',
            'status',
            'created_at'
        ];

        $sort = in_array($this->sort, $sortable) ? $this->sort : 'created_at';
        $direction = in_array(strtolower($this->direction), ['asc', 'desc']) ? $this->direction : 'desc';

        $query->orderBy($sort, $direction);

        return $query->get([
            'id',
            'jobProviderID',
            'companyName',
            'age',
            'disabilityType',
            'educationalAttainment',
            'salaryRange',
            'jobPostingObjectives',
            'experience',
            'skills',
            'description',
            'requirements',
            'created_at',
            'updated_at',
            'contactPhone',
            'contactEmail',
            'remarks',
            'position',
            'status',
            'workEnvironment',
            'sex',
            'category',
            'companyAddress'
        ]);
    }
}
