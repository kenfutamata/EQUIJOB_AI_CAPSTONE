<?php

namespace App\Exports;

use App\Models\users;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class JobProviderUsersExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $search;
    protected $sort;
    protected $direction;
    public function __construct($search = null , $sort = 'userID', $direction = 'asc')
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
            'Company Name',
            'Status', 
        ];
        
    }
    public function collection()
    {
        $query = users::query()->where('role', 'Job Provider')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereRaw("LOWER(CONCAT(firstName, ' ', lastName)) LIKE ?", ['%' . strtolower($this->search) . '%'])
                        ->orWhere('userID', 'like', "%{$this->search}%")
                        ->orWhere('firstName', 'like', "%{$this->search}%")
                        ->orWhere('firstName', 'like', "%{$this->search}%")
                        ->orWhere('lastName', 'like', "%{$this->search}%")
                        ->orWhere('address', 'like', "%{$this->search}%")
                        ->orWhere('phoneNumber', 'like', "%{$this->search}%")
                        ->orWhere('companyName', 'like', "%{$this->search}%")
                        ->orWhere('status', 'like', "%{$this->search}%");
                });
            });

        $sortable = ['userID', 'firstName', 'lastName', 'email', 'phoneNumber', 'companyName', 'status' ];
        $sort = in_array($this->sort, $sortable) ? $this->sort : 'userID';
        $direction = $this->direction === 'desc' ? 'desc' : 'asc';

        $query->orderBy($sort, $direction);

        return $query->get(['userID', 'firstName', 'lastName', 'email', 'phoneNumber', 'companyName', 'status']);
    }
}
