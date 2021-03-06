<?php

namespace App\Http\Controllers;

use App\School;
use App\Document;
use Validator;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    private $table;

    /**
     * Create a new class instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->table = 'documents';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Filter resource
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\School  $school
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request, $token = null)
    {
        return redirect(url()->previous() . '#school-documents')->with('documentCategory', base64_decode($token));
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
        if ($request->ajax()) {
            Validator::make($request->all(), 
                [
                    'category' => ['required'],
                    'filename' => [
                        'required',
                        'mimes:png,jpg,jpeg,pdf',
                        'max:5000'
                    ]
                ]
            )->validate();
            $school = School::find(auth()->user()->school->id);
            $document = $school->documents()->create($request->all());
            $document->filename = $this->uploadDocument($document, $request);
            $document->save();
            return response()->json(['status' => true]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function show(Document $document)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function edit(Document $document)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Document $document)
    {
        //
    }

    /**
     * Upload document for school
     * 
     * @param  \App\School  $school
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $oldFile
     * @return string
     */
    public function uploadDocument($document, $request, $oldFile = null)
    {
        if ($request->hasFile('filename')) {
            $filename = 'document_'.date('d_m_Y_H_i_s_').md5(uniqid(rand(), true)).'.'.$request->filename->extension();
            $path = $request->filename->storeAs('public/document/'.$document->id, $filename);
            return $document->id.'/'.$filename;
        }
        return $oldFile;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        foreach ($request->selectedData as $id) {
            $document = Document::find($id);
            unlink(storage_path('app/public/document/'.$document->filename));
        }
        Document::destroy($request->selectedData);
        return response()->json(['status' => true, 'message' => __($this->deletedMessage)]);
    }
}
