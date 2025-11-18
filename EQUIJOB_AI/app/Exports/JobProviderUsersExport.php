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
            'Company Address',
            'Province',
            'City',
            'Status', 
        ];
        
    }
    public function collection()
    {
        $query = users::with(['province', 'city'])->where('role', 'Job Provider')
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
                        ->orWhere('companyAddress', 'like', "%{$this->search}%")
                        ->orWhere('status', 'like', "%{$this->search}%")
                        ->orWhereHas('province', function ($q) {
                            $q->where('provinceName', 'like', "%{$this->search}%");
                        })
                        ->orWhereHas('city', function ($q) {
                            $q->where('cityName', 'like', "%{$this->search}%");
                });
            });
        });
        $users = $query->get();
        $sortable = ['userID', 'firstName', 'lastName', 'email', 'phoneNumber', 'companyName', 'status' ];
        $sort = in_array($this->sort, $sortable) ? $this->sort : 'userID';
        $direction = $this->direction === 'desc' ? 'desc' : 'asc';

        $query->orderBy($sort, $direction);
        return $users->map(function ($user) {
            return [
                'userID' => $user->userID,
                'firstName' => $user->firstName,
                'lastName' => $user->lastName,
                'email' => $user->email,
                'phoneNumber' => $user->phoneNumber,
                'companyName' => $user->companyName,
                'companyAddress' => $user->companyAddress,
                'province'=> $user->province ? $user->province->provinceName : '',
                'city'=> $user->city ? $user->city->cityName : '',
                'status' => $user->status,
            ];
        });
    }
}
