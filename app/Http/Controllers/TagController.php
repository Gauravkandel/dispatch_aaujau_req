<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Tag;
use App\Model\TagsForAgent;
use App\Model\TagsForTeam;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::orderBy('created_at', 'DESC')->paginate(5);
        $agentTags = TagsForAgent::orderBy('created_at', 'DESC')->paginate(5);
        $teamTeags = TagsForTeam::orderBy('created_at', 'DESC')->paginate(5);
        return view('tags.tag')->with(['tags' => $tags, 'agentTags' => $agentTags, 'teamTeags' => $teamTeags]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tags.update-tag');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = '\App\Model\Tag';
        if ($request->type == 'team') {
            $model = "\App\Model\TagsForTeam";
        } elseif ($request->type == 'agent') {
            $model = "\App\Model\TagsForAgent";
        } else {
            $model = '\App\Model\Tag';
        }
        $data = [
            'name' => $request->name,
        ];

        $client = $model::create($data);
        return redirect()->route('tag.index')->with('success', 'Tag Added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $type)
    {
        $model = '\App\Model\Tag';
        if ($type == 'team') {
            $model = "\App\Model\TagsForTeam";
        } elseif ($type == 'agent') {
            $model = "\App\Model\TagsForAgent";
        } else {
            $model = '\App\Model\Tag';
        }
        $tag = $model::find($id);
        return view('tags.update-tag')->with(['tag' => $tag, 'type' => $type]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $model = '\App\Model\Tag';
        if ($request->type == 'team') {
            $model = "\App\Model\TagsForTeam";
        } elseif ($request->type == 'agent') {
            $model = "\App\Model\TagsForAgent";
        } else {
            $model = '\App\Model\Tag';
        }
        $data = [
            'name' => $request->name,
        ];

        $tag = $model::where('id', $id)->update($data);
        return redirect()->route('tag.index')->with('success', 'Tag Updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $type)
    {
        $model = '\App\Model\Tag';
        if ($type == 'team') {
            $model = "\App\Model\TagsForTeam";
        } elseif ($type == 'agent') {
            $model = "\App\Model\TagsForAgent";
        } else {
            $model = '\App\Model\Tag';
        }

        $deleteRecord = $model::where('id', $id)->delete();
        return redirect()->back()->with('success', 'Tag Deleted successfully!');
    }
}
