<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use \InterventionImage;

class ProfilesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::find(Auth::id());
        return view('users.edit', compact('user'));
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'icon' => [
                'nullable',
                'file',
                'image',
                'mimes:jpeg,png',
                'dimensions:min_width=200,min_height=200,max_width=1000,max_height=1000',
                'max:2048'// 2MB
            ],
        ]);

        // フォームから受け取った値取得
        $inputs = $request->all();

        // iconが空(null)だったらnameのみupdate
        // 空(null)の場合真
        if(empty($inputs['icon'])) {
            User::where('id', Auth::id())->update(['name' => $inputs['name']]);
        } else {
            $filename = 'icon_'. Auth::id(). '.'. $inputs['icon']->getClientOriginalExtension();
            InterventionImage::make($inputs['icon'])
                ->fit(200, 200)
                ->save(public_path('/images/icon/' . $filename));

            User::where('id', Auth::id())->update(['name' => $inputs['name'], 'icon' => $filename]);
        }

        return redirect()
                ->route('profile.edit')
                ->with('success', 'プロフィールが更新されました。');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
