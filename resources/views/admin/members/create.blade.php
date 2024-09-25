@extends('layouts.app')

@section('content')
    <h1>{{ isset($member) ? 'Edit Member' : 'Create Member' }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ isset($member) ? route('admin.members.update', $member->id) : route('admin.members.store') }}" method="POST">
        @csrf
        @if(isset($member))
            @method('PUT')
        @endif

        <input type="hidden" name="id" value="{{ $member->id ?? '' }}">

        <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" class="form-control" name="first_name" id="first_name" placeholder="First Name" value="{{ old('first_name', isset($member) ? $member->first_name : '') }}" required>
        </div>

        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Last Name" value="{{ old('last_name', isset($member) ? $member->last_name : '') }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" name="email" id="email" placeholder="Email" value="{{ old('email', isset($member) ? $member->email : '') }}" required>
        </div>

        <div class="form-group">
            <label for="country">Country</label>
            <input type="text" class="form-control" name="country" id="country" placeholder="Country" value="{{ old('country', isset($member) ? $member->country : '') }}" required>
        </div>

        <div class="form-group">
            <label for="position">Position</label>
            <input type="text" class="form-control" name="position" id="position" placeholder="Position" value="{{ old('position', isset($member) ? $member->position : '') }}">
        </div>

        <div class="form-group">
            <label for="organization">Organization</label>
            <textarea class="form-control" name="organization" id="organization" placeholder="Organization">{{ old('organization', isset($member) ? $member->organization : '') }}</textarea>
        </div>

        <div class="form-group">
            <label for="gen_int1">General Interest 1</label>
            <input type="text" class="form-control" name="gen_int1" id="gen_int1" placeholder="General Interest 1" value="{{ old('gen_int1', isset($member) ? $member->gen_int1 : '') }}">
        </div>

        <div class="form-group">
            <label for="gen_int2">General Interest 2</label>
            <input type="text" class="form-control" name="gen_int2" id="gen_int2" placeholder="General Interest 2" value="{{ old('gen_int2', isset($member) ? $member->gen_int2 : '') }}">
        </div>

        <div class="form-group">
            <label for="gen_int3">General Interest 3</label>
            <input type="text" class="form-control" name="gen_int3" id="gen_int3" placeholder="General Interest 3" value="{{ old('gen_int3', isset($member) ? $member->gen_int3 : '') }}">
        </div>

        <div class="form-group">
            <label for="degree">Degree</label>
            <input type="text" class="form-control" name="degree" id="degree" placeholder="Degree" value="{{ old('degree', isset($member) ? $member->degree : '') }}">
        </div>

        <div class="form-group mt-2">
            <button type="submit" class="btn btn-primary">{{ isset($member) ? 'Update' : 'Create' }}</button>
        </div>
    </form>
@endsection
