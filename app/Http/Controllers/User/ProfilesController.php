<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileFormRequest;
use Carbon\Carbon;

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
        $user = User::find(Auth::guard('user')->user()->id);
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $user_id)
    {
        $user = User::find($user_id);
        $type = $request->input('type');
        $type = isset($type) ? $type : 'newest';
        $methods = [
            'newest' => function($user, $type){return $user->blog->buildArticlesNewest($type);},
            'popularity' => function($user, $type){return $user->blog->buildArticlesPopularity($type);}
        ];

        $articles = $methods[$type]($user, $type)->paginate(8);

        return view('users.show', compact('user', 'articles', 'type'));
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
    public function update(ProfileFormRequest $request)
    {
        // フォームから受け取った値取得
        $inputs = $request->all();
        $profile = [
            'name' => $inputs['name'],
            'email' => $inputs['email'],
            'icon' => isset($inputs['icon']) ? $inputs['icon'] : null
        ];
        $profile['birthday'] = Carbon::create($inputs['birth_year'], $inputs['birth_month'], $inputs['birth_day']);

        //dd($inputs);

        // プロフィール更新
        User::profileUpdate($profile, Auth::guard('user')->user()->id);

        return redirect()
                ->route('users.profile.edit')
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
