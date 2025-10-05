<?php

namespace App\Exports;

use App\Models\Feedbacks;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class JobProviderApplicantFeedbackDataExport implements FromCollection, WithHeadings
{
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
        $query = Feedbacks::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('applicantID', 'like', "%{$this->search}%")
                        ->orWhere('jobPostingID', 'like', "%{$this->search}%")
                        ->orWhere('firstName', 'like', "%{$this->search}%")
                        ->orWhere('lastName', 'like', "%{$this->search}%")
                        ->orWhere('rating', 'like', "%{$this->search}%")
                        ->orWhere('feedbackType', 'like', "%{$this->search}%")
                        ->orWhere('feedbackText', 'like', "%{$this->search}%")
                        ->orWhere('status', 'like', "%{$this->search}%");
                });
            });

        $sortable = [
            'applicantID',
            'jobPostingID',
            'firstName',
            'lastName',
            'rating',
            'feedbackText',
            'feedbackType',
            'status'
        ];
        $sort = in_array($this->sort, $sortable) ? $this->sort : 'created_at';
        $direction = $this->direction === 'desc' ? 'desc' : 'asc';

        $query->orderBy($sort, $direction);

        return $query->get([
            'applicantID',
            'jobPostingID',
            'firstName',
            'lastName',
            'rating',
            'feedbackText',
            'feedbackType',
            'status'
        ]);
    }

    public function headings(): array
    {
        return [
            'applicantID',
            'jobPostingID',
            'firstName',
            'lastName',
            'rating',
            'feedbackText',
            'feedbackType',
            'status'
        ];
    }
}
