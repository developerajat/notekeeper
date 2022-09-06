<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['notes'] = Note::where('user_id', Auth::id())->latest()->paginate(12);

        return view('index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'             => 'required',
            'description'       => 'required',
        ]);

        Note::create([
            'user_id'       => Auth::id(),
            'title'         => $request->title,
            'description'   => nl2br($request->description),
        ]);
        notify()->success('Note saved!');
        return redirect(route('index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function edit(Note $note)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'             => 'required',
            'description'       => 'required',
        ]);

        $note = Note::find($id);
        $note->update([
            'title'         => $request->title,
            'description'   => nl2br($request->description),
        ]);

        notify()->success('Note updated!');
        return back()->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function destroy(Note $note)
    {
        $note->delete();
        notify()->success('Note deleted!');
        return redirect()->back();
    }

    public function pinned()
    {
        $data['notes'] = Note::where(['user_id' => Auth::id(), 'pinned' => 1])->latest()->paginate(12);

        return view('index', $data);
    }

    public function trashed()
    {
        $data['notes'] = Note::onlyTrashed()->where(['user_id' => Auth::id()])->latest()->paginate(12);

        return view('index', $data);
    }

    public function forceDelete($id)
    {
        Note::where('id', $id)->forceDelete();
        notify()->success('Note permanently deleted');
        return redirect(route('notes.trashed'));
    }

    public function pin(Request $request)
    {
        Note::where('id', $request->id)->update(['pinned' => $request->value]);
        return $request->id;
    }

    public function restore(Request $request)
    {
        Note::where('id', $request->id)->restore();
        return $request->id;
    }

    public function storeSketch(Request $request)
    {
        return json_decode($request->sketchData);

        Note::create([
            'user_id'       => Auth::id(),
            'title'         => $request->title,
            'description'   => nl2br($request->description),
        ]);
        notify()->success('Note saved!');
        return redirect(route('index'));
    }

}
