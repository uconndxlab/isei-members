<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use League\Csv\Writer;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Retrieve filters from request
        $name = $request->input('name');
        $country = $request->input('country');
        $gen_int = $request->input('gen_int');
        $city = $request->input('city'); // Assuming city is part of the `organization` or other field
    
        // Query members with filters
        $members = Member::query();
    
        if ($name) {
            $members->where(function($query) use ($name) {
                $query->where('first_name', 'like', "%{$name}%")
                      ->orWhere('last_name', 'like', "%{$name}%");
            });
        }
    
        if ($country) {
            $members->where('country', $country);
        }
    
        if ($gen_int) {
            $members->where(function($query) use ($gen_int) {
                $query->where('gen_int1', $gen_int)
                      ->orWhere('gen_int2', $gen_int)
                      ->orWhere('gen_int3', $gen_int);
            });
        }
    
        if ($city) {
            $members->where('organization', 'like', "%{$city}%");
        }
    
        // Get paginated results
        $members = $members->paginate(10);
    
        // Pass filters and members to the view
        $countries = Member::select('country')->distinct()->pluck('country')->filter()->toArray();
        $countries = collect($countries)->sort();
        
       

        $genInt1 = Member::distinct()->pluck('gen_int1')->filter()->toArray();
        $genInt2 = Member::distinct()->pluck('gen_int2')->filter()->toArray();
        $genInt3 = Member::distinct()->pluck('gen_int3')->filter()->toArray();
        
        // Merge the arrays and get the unique values
        $general_interests = collect(array_merge($genInt1, $genInt2, $genInt3))->unique()->values();
        // order it by name
        $general_interests = $general_interests->sort();
        
    
        return view('members.index', compact('members', 'countries', 'general_interests'));
    }

    public function adminIndex()
    {
        $members = Member::paginate(10);
        return view('admin.members.index', compact('members'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.members.create');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'last_name' => 'required|string',
            'first_name' => 'required|string',
            'degree' => 'nullable|string',
            'position' => 'nullable|string',
            'organization' => 'nullable|string',
            'email' => 'required|email|unique:members',
            'country' => 'nullable|string',
            'gen_int1' => 'nullable|string',
            'gen_int2' => 'nullable|string',
            'gen_int3' => 'nullable|string',
        ]);
    
        $member = Member::create($data);
        
        // go to the admin index page with message
        return redirect()->route('admin.index')->with('success', 'Member created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member)
    {
        return view('admin.members.create', compact('member'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Member $member)
    {
        // Validate the incoming request
        $data = $request->validate([
            'last_name' => 'required|string',
            'first_name' => 'required|string',
            'degree' => 'nullable|string',
            'position' => 'nullable|string',
            'organization' => 'nullable|string',
            'email' => 'required|email|unique:members,email,' . $member->id,
            'country' => 'nullable|string',
            'gen_int1' => 'nullable|string',
            'gen_int2' => 'nullable|string',
            'gen_int3' => 'nullable|string',
        ]);
    

    
        // Attempt to update the member
        $memberUpdated = $member->update($data);
    
        // Check if the update was successful
        if ($memberUpdated) {
            return redirect()->route('admin.index')->with('success', 'Member updated successfully.');
        } else {
            return back()->withErrors(['update' => 'Failed to update member.']);
        }
    }

    public function export()
    {
        $members = Member::all();
        $csv = \League\Csv\Writer::createFromFileObject(new \SplTempFileObject());
        $csv->insertOne(array_keys($members->first()->toArray()));
        $members->each(function ($member) use ($csv) {
            $csv->insertOne($member->toArray());
        });
        $csv->output('members.csv');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        $member->delete();
        return redirect()->route('admin.index')->with('success', 'Member deleted successfully.');
    }
}
