<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUser;
use Validator;
use Spatie\Image\Image;
use Spatie\Image\Manipulations;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::find(auth()->user()->id);
        $view = [
            'title' => __('Account Detail'),
            'subtitle' => __('Hi') . ', ' . $user->name . '!',
            'description' => __('Change information about yourself on this page.'),
            'breadcrumbs' => [
                route('account.index') => __('Account'),
                null => __('Detail')
            ],
            'data' => $user
        ];
        return view('account.show', $view);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(StoreUser $request)
    {
        $user = User::find(auth()->user()->id);
        if ($request->filled('password')) {
            $request->merge(['password' => Hash::make($request->password)]);
        } else {
            $request->merge(['password' => $user->password]);
        }
        $user->fill($request->except(['username', 'name']));
        $user->save();
        $this->uploadPhoto($user, $request);
        return redirect(url()->previous())->with('alert-success', __($this->updatedMessage));
    }

    /**
     * Upload photo for user avatar
     * 
     * @param  \App\User  $user
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $oldFile
     * @return string
     */
    public function uploadPhoto($user, Request $request)
    {
        if ($request->hasFile('photo')) {
            $user->addMediaFromRequest('photo')->usingFileName('photo-'.date('d-m-Y-h-m-s-').md5(uniqid(rand(), true)))->toMediaCollection('photos');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
