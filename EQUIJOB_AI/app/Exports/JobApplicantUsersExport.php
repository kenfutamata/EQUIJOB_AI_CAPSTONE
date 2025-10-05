<?php

namespace App\Exports;

use App\Models\Users;
use Illuminate\Container\Attributes\DB;
use Illuminate\Support\Facades\DB as FacadesDB;
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
        $query = users::query()->where('role', 'Applicant')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereRaw("LOWER(CONCAT(firstName, ' ', lastName)) LIKE ?", ['%' . strtolower($this->search) . '%'])
                        ->orWhere('userID', 'like', "%{$this->search}%")
                        ->orWhere('firstName', 'like', "%{$this->search}%")
                        ->orWhere('lastName', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%")
                        ->orWhere('dateOfBirth', 'like', "%{$this->search}%")
                        ->orWhere('address', 'like', "%{$this->search}%")
                        ->orWhere('typeOfDisability', 'like', "%{$this->search}%")
                        ->orWhere('pwdId', 'like', "%{$this->search}%")
                        ->orWhere('status', 'like', "%{$this->search}%");
                });
            });

        $sortable = ['userID', 'firstName', 'lastName', 'email', 'phoneNumber', 'dateOfBirth', 'address', 'typeOfDisability', 'pwdId', 'status'];
        $sort = in_array($this->sort, $sortable) ? $this->sort : 'userID';
        $direction = $this->direction === 'desc' ? 'desc' : 'asc';

        $query->orderBy($sort, $direction);

        return $query->get(['userID', 'firstName', 'lastName', 'email', 'phoneNumber', 'dateOfBirth', 'address', 'typeOfDisability', 'pwdId', 'status']);
    }
}
