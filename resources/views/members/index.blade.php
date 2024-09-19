@extends('layouts.app')

@section('title', 'Member Directory')

@section('content')
    <h1>Member Directory</h1>

    <form method="GET" action="{{ route('members.index') }}" class="mb-4" 
    hx-get="{{ route('members.index') }}" 
    hx-target="#results" 
    hx-swap="innerHTML" 
    hx-select="#results">
       @csrf
        <div class="row">
            <!-- Name Search -->
            <div class="col-md-3">
                <input type="text" name="name" class="form-control" placeholder="Search by Name" value="{{ request('name') }}">
            </div>

            <!-- Country Select -->
            <div class="col-md-3">
                <select name="country" class="form-select">
                    <option selected value="">Select Country</option>
                    @foreach ($countries as $country)
                        <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>
                            {{ $country }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- General Interest Select -->
            <div class="col-md-3">
                <select name="gen_int" class="form-select">
                    <option value="">Select General Interest</option>
                    @foreach ($general_interests as $interest)
                        <option value="{{ $interest }}" {{ request('gen_int') == $interest ? 'selected' : '' }}>
                            {{ $interest }}
                        </option>
                    @endforeach
                </select>
            </div>


        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Search</button>
            <a href="{{ route('members.index') }}" class="btn btn-secondary">Clear</a>
        </div>
    </form>

    <table id="results" class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Degree</th>
                <th>Position</th>
                <th>Organization</th>
                <th>Email</th>
                <th>Country</th>

                <th>General Interests</th>
                <th>Entry Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($members as $member)
                <tr>
                    <td>{{ $member->first_name }} {{ $member->last_name }}</td>
                    <td>{{ $member->degree }}</td>
                    <td>{{ $member->position }}</td>
                    <td>{{ $member->organization }}</td>
                    <td>{{ $member->email }}</td>
                    <td>{{ $member->country }}</td>

                    <td><ul>
                        <li>{{$member->gen_int1}}</li>
                        <li>{{$member->gen_int2}}</li>
                        <li>{{$member->gen_int3}}</li>
                        
                    </ul></td>
                    <td>{{ $member->entry_date }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">No members found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $members->appends(request()->query())->links() }} <!-- Pagination links -->
@endsection
