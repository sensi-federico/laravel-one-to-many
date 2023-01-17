@extends('layouts.admin')

@section('content')
@include('partials.errors')
<h1 class="mt-4">Edit Project!</h1>
<form action="{{route('admin.projects.update', $project->id)}}" method="post">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="title">Title</label>
        <input type="text" name="title" id="title" class="form-control" placeholder="Add title" aria-describedby="titleHelper" value="{{old('title', $project->title)}}">
    </div>
    <div class="my-3">
        <label for="description" class="form-label">Overview</label>
        <textarea class="form-control" name="overview" id="overview" rows="3">{{old('overview', $project->overview)}}</textarea>
    </div>
    <button type="submit" class="btn btn-primary mt-3">Update</button>
</form>
@endsection