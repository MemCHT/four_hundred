<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Mail;

use App\Http\Requests\AdminUserSendMailRequestForm;
use App\Mail\InvitationEmail;
use App\Models\User;
use App\Models\Status;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $users = User::searchUserByKeyword($request);

        $users = User::search($request->input());
        $users = $users->paginate(8);

        return view('admins.users.index', compact('users'));
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
     * @param  int  $user_id
     * @return \Illuminate\Http\Response
     */
    public function show($user_id)
    {
        $user = User::find($user_id);
        $user->formatForUserCard();

        $statuses = (object)[];
        $statuses->unlock_id = Status::getByName('公開')->id;
        $statuses->lock_id = Status::getByName('非公開')->id;

        return view('admins.users.show', compact('user','statuses'));
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $user_id)
    {
        $user = User::find($user_id);
        $status_id = $request->input('status_id');
        //dd($request->input());


        $user->update(['status_id' => $status_id]);

        // return redirect()->route('admins.users.show', ['user' => $user_id])->with('success', $user->name.'さんのステータスを更新しました。');
        return back()->with('success', $user->name.'さんのステータスを更新しました！');
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

    /**
     * 該当ユーザにメールを送信
     * @param  App\Http\Requests\AdminUserSendMailRequestForm $request
     * @param  int  $user_id
     * @return \Illuminate\Http\Response
     */
    public function sendmail(Request $request, $user_id){
        // dd("おおおい");
        $user = User::find($user_id);
        $data = $request->input();
        $data['body'] =
            `hoge`;

        // $data = json_decode(json_encode($data));

        Mail::to($user)->send(new InvitationEmail($data));
        // dd(new InvitationEmail($data));
        // return redirect()->route('admins.users.show', ['user' => $user_id])->with('success', $user->name.'さんへメールを送信しました。');
        return back()->with('success', $user->name.'さんへメールを送信しました。');
    }
}
