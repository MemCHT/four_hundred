<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Follow;

class FollowController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $user_id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $user_id)
    {
        $user = Auth::guard('user')->user();

        if($user->canFollow($user_id))
            Follow::create([
                'from_user_id' => $user->id,
                'to_user_id' => $user_id
            ]);

        return back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $user_id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $user_id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $user_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($user_id, $follow_id)
    {
        $user = Auth::guard('user')->user();

        if($user->isFollow($user_id))
            Follow::where('from_user_id', $user->id)->where('to_user_id', $user_id)->delete();

        return back();
    }
}
