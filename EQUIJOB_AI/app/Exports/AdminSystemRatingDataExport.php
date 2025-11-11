<?php

namespace App\Exports;

use App\Models\Feedbacks;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AdminSystemRatingDataExport implements FromCollection, WithHeadings
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
            'First Name',
            'Last Name',
            'Rating',
            'Feedback Text',
            'Feedback Type',
            'Status',
        ];
        
    }
    public function collection()
    {
        
        $sortColumn = request('sort', 'created_at');
        $sortDirection = request('direction', 'desc');
        $query = Feedbacks::whereIn('feedbackType', ['Job Application Issues', 'AI-Job Matching Issues', 'Resume Builder Problems', 'Other']);
        $query->orderBy($sortColumn, $sortDirection);
        return $query->get(['firstName', 'lastName', 'rating', 'feedbackText', 'feedbackType', 'status']);


    }
}
