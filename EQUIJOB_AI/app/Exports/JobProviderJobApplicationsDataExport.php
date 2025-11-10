<?php

namespace App\Exports;

use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class JobProviderJobApplicationsDataExport implements FromCollection, WithHeadings
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
    public function headings(): array
    {
        return[
            'ID',
            'Job Posting ID', 
            'Apploicant ID', 
            'Resume', 
            'Appication Letter', 
            '',
            '', 
            'Remarks', 
            '', 
            '', 
            'Job Application Number', 
            'Google Meet Link', 
            'Status', 
            'Interview Date', 
            'Interview Time', 

        ];   
    }
    public function collection()
    {
        $user = Auth::guard('job_provider')->user();

        $jobApplicationTable = (new JobApplication())->getTable();
        $applicantTable = 'users';
        $jobPostingTable = 'jobPosting';

        $applicationsQuery = JobApplication::query()
            ->join($applicantTable, "{$jobApplicationTable}.applicantID", '=', "{$applicantTable}.id")
            ->join($jobPostingTable, "{$jobApplicationTable}.jobPostingID", '=', "{$jobPostingTable}.id")
            ->where("{$jobPostingTable}.jobProviderID", $user->id)
            ->with(['applicant', 'jobPosting']);

        if ($this->search) {
            $searchTerm = '%' . $this->search . '%';
            $applicationsQuery->where(function ($q) use ($searchTerm, $jobApplicationTable, $applicantTable, $jobPostingTable) {
                $q->where("{$jobApplicationTable}.jobApplicationNumber", 'like', $searchTerm)
                    ->orWhere("{$jobApplicationTable}.status", 'like', $searchTerm)
                    ->orWhere("{$applicantTable}.firstName", 'like', $searchTerm)
                    ->orWhere("{$applicantTable}.lastName", 'like', $searchTerm)
                    ->orWhere("{$applicantTable}.phoneNumber", 'like', $searchTerm)
                    ->orWhere("{$applicantTable}.gender", 'like', $searchTerm)
                    ->orWhere("{$applicantTable}.address", 'like', $searchTerm)
                    ->orWhere("{$applicantTable}.disabilityType", 'like', $searchTerm)
                    ->orWhere("{$jobPostingTable}.position", 'like', $searchTerm)
                    ->orWhere("{$jobPostingTable}.companyName", 'like', $searchTerm)
                    ->orWhere("{$jobPostingTable}.disabilityType", 'like', $searchTerm);
            });
        }

        $sortable = [
            'jobApplicationNumber' => "{$jobApplicationTable}.jobApplicationNumber",
            'firstName' => "{$applicantTable}.firstName",
            'lastName' => "{$applicantTable}.lastName",
            'phoneNumber' => "{$applicantTable}.phoneNumber",
            'gender' => "{$applicantTable}.gender",
            'address' => "{$applicantTable}.address",
            'emailAddress' => "{$applicantTable}.emailAddress",
            'disabilityType' => "{$applicantTable}.disabilityType",
            'position' => "{$jobPostingTable}.position",
            'companyName' => "{$jobPostingTable}.companyName",
            'status' => "{$jobApplicationTable}.status",
            'created_at' => "{$jobApplicationTable}.created_at",
        ];

        $sortColumn = $sortable[$this->sort] ?? "{$jobApplicationTable}.created_at";
        $direction = strtolower($this->direction) === 'asc' ? 'asc' : 'desc';

        return $applicationsQuery
            ->select("{$jobApplicationTable}.*")
            ->orderBy($sortColumn, $direction)
            ->get(); 
    }
}
