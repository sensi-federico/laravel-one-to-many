<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::orderByDesc('id')->get();
        //dd
        return view('admin.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.projects.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProjectRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProjectRequest $request)
    {
        // dd($request->cover);
        $val_data = $this->validation($request->all());

        $project_slug = Str::slug($val_data['title']);
        $val_data['slug'] = $project_slug;

        $cover = Storage::disk('public')->put('placeholders', $request->cover);
        // dd($cover);
        $val_data['cover'] = $cover;

        $project = Project::create($val_data);

        return to_route('admin.projects.index')->with('message', "$project->title added successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        return view('admin.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        return view('admin.projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProjectRequest  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $val_data = $this->validation($request->all());
        $project_slug = Str::slug($val_data['title']);
        $val_data['slug'] = $project_slug;

        $project->update($val_data);

        return to_route('admin.projects.index')->with('message', "$project->title added successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return to_route('admin.projects.index')->with('message', "$project->title deleted successfully");
    }



    private function validation($data)
    {
        // Validator::make($data, $rules, $message)
        $validator = Validator::make($data, [
            'title' => 'required|min:5|max:100',
            'overview' => 'nullable',
            'cover' => 'nullable|image|max:500'
        ], [
            'title.required' => 'Il titolo é obbligatorio',
            'title.min' => 'Il titolo deve essere almeno :min caratteri',
            'title.max' => 'Il titolo deve essere almeno :max caratteri'
        ])->validate();

        return $validator;
    }
}
