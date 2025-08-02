<?php

namespace App\Http\Controllers;

use App\Models\Feedbacks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactUsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateInformation = $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phoneNumber' => 'required|string|max:20',
            'feedbackType' => 'required|in:Job Application Issues,AI-Job Matching Issues,Resume Builder Problems,Other',
            'feedbackText' => 'required|string|max:1000',
        ]);
        $validateInformation['status'] = 'Sent'; 
        try{
            Feedbacks::create($validateInformation);
            return redirect()->back()->with('success', 'Thank you for your feedback! We will get back to you shortly.');
        }catch (\Exception $e) {
            Log::error('Error storing feedback: ' . $e->getMessage());
            return redirect()->back()->with('error', 'There was an error submitting your feedback. Please try again later.');
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
