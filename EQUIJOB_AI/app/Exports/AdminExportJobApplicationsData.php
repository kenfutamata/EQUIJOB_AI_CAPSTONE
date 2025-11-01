<?php

namespace App\Exports;

use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;

class AdminExportJobApplicationsData implements FromCollection
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
        $admin = Auth::guard('admin')->user();

        $jobApplicationTable = (new JobApplication())->getTable();
        $applicantTable = 'users';
        $jobPostingTable = 'jobPosting';

        $applicationsQuery = JobApplication::query()
            ->join($applicantTable, "{$jobApplicationTable}.applicantID", '=', "{$applicantTable}.id")
            ->join($jobPostingTable, "{$jobApplicationTable}.jobPostingID", '=', "{$jobPostingTable}.id")
            ->with(['applicant', 'jobPosting']);

        if ($this->search) {
            $searchTerm = '%' . $this->search . '%';
            $applicationsQuery->where(function ($q) use ($searchTerm, $jobApplicationTable, $applicantTable, $jobPostingTable) { // Added tables to use() for clarity
                $q->where("{$jobApplicationTable}.jobApplicationNumber", 'like', $searchTerm)
                    ->orWhere("{$jobApplicationTable}.status", 'like', $searchTerm)
                    ->orWhereHas('jobPosting', function ($q2) use ($searchTerm) {
                        $q2->where('position', 'like', 'searchTerm')
                            ->orWhere('companyName', 'like', $searchTerm)
                            ->orWhere('disabilityType', 'like', $searchTerm);
                    })
                    ->orWhereHas('applicant', function ($q3) use ($searchTerm) {
                        $q3->where('firstName', 'like', $searchTerm)
                            ->orWhere('lastName', 'like', $searchTerm)
                            ->orWhere('phoneNumber', 'like', $searchTerm)
                            ->orWhere('gender', 'like', $searchTerm)
                            ->orWhere('address', 'like', $searchTerm)
                            ->orWhere('disabilityType', 'like', $searchTerm);
                    });
            });
        }

        $sortable = [
            'jobApplicationNumber' => "{$jobApplicationTable}.jobApplicationNumber",
            'firstName' => "{$applicantTable}.firstName",
            'lastName' => "{$applicantTable}.lastName",
            'phoneNumber' => "{$applicantTable}.phoneNumber",
            'gender' => "{$applicantTable}.sex",
            'address' => "{$applicantTable}.address",
            'emailAddress' => "{$applicantTable}.email",
            'disabilityType' => "{$applicantTable}.disabilityType",
            'position' => "{$jobPostingTable}.position",
            'companyName' => "{$jobPostingTable}.companyName",
            'status' => "{$jobApplicationTable}.status"
        ];
        $sortColumn = $sortable[$this->sort] ?? "{$jobApplicationTable}.created_at";
        $direction = strtolower($this->direction) === 'asc' ? 'asc' : 'desc';
        return $applicationsQuery
            ->select("{$jobApplicationTable}.*")
            ->orderBy($sortColumn, $direction)
            ->get();
    }
}
