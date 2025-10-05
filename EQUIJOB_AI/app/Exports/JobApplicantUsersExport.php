<?php

namespace App\Exports;

use App\Models\Users;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class JobApplicantUsersExport implements FromCollection, WithHeadings
{
    protected $search;
    protected $sort;
    protected $direction;

    public function __construct($search = null, $sort = 'userID', $direction = 'asc')
    {
        $this->search = $search;
        $this->sort = $sort;
        $this->direction = $direction;
    }

    public function headings(): array
    {
        return [
            'User ID',
            'First Name',
            'Last Name',
            'Email',
            'Phone Number',
            'Date of Birth',
            'Address', 
            'Type of Disability',
            'PWD ID',
            'Status',
        ];
    }

public function collection()
{
    $query = \DB::table('job_applications as ja')
        ->join('users as u', 'ja.userID', '=', 'u.userID')
        ->where('u.role', 'Applicant')
        ->when($this->search, function ($query) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->whereRaw("LOWER(CONCAT(u.firstName, ' ', u.lastName)) LIKE ?", ['%' . strtolower($search) . '%'])
                    ->orWhere('u.userID', 'like', "%{$search}%")
                    ->orWhere('u.firstName', 'like', "%{$search}%")
                    ->orWhere('u.lastName', 'like', "%{$search}%")
                    ->orWhere('u.email', 'like', "%{$search}%")
                    ->orWhere('u.address', 'like', "%{$search}%")
                    ->orWhere('u.phoneNumber', 'like', "%{$search}%")
                    ->orWhere('u.typeOfDisability', 'like', "%{$search}%")
                    ->orWhere('u.pwdId', 'like', "%{$search}%")
                    ->orWhere('u.status', 'like', "%{$search}%");
            });
        });

    $sortable = ['userID', 'firstName', 'lastName', 'email', 'phoneNumber', 'dateOfBirth', 'typeOfDisability', 'role', 'status'];
    $sort = in_array($this->sort, $sortable) ? $this->sort : 'userID';
    $direction = $this->direction === 'desc' ? 'desc' : 'asc';

    $query->orderBy("u.$sort", $direction);

    return $query->get([
        'u.userID',
        'u.firstName',
        'u.lastName',
        'u.email',
        'u.phoneNumber',
        'u.dateOfBirth',
        'u.address',
        'u.typeOfDisability',
        'u.pwdId',
        'u.status',
    ]);
}
}
