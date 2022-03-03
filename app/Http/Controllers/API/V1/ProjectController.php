<?php

namespace App\Http\Controllers\API\V1;

use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectResource;


class ProjectController extends Controller
{
    public function index(Request $request)
    {
        return ProjectResource::collection(Project::all());
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(ProjectRequest $request)
    {
        $validated = $request->validated();
        return new ProjectResource(Project::create($validated));
    }
    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param  Property  $property
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Project $project)
    {
        return new ProjectResource($project);
    }
}
