<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Comment;
use App\Models\Status;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $comments_builder = Comment::search($request->input());
        $comments = $comments_builder->paginate(5);

        $comments_count = $comments_builder->count();

        return view('admins.comments.index', compact('comments', 'comments_count'));
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->input());
        $update_status_id = ['toPublic' => Status::getByName('公開')->id, 'toPrivate' => Status::getByName('非公開')->id];

        $input = $request->input();
        $submit_type = $request->input('submitType');
        $comment_ids = $this->extractCommentIdsFromInput($input);
        Comment::whereIn('id', $comment_ids)->update(['status_id' => $update_status_id[$submit_type]]);

        if($comment_ids)
            return back()->with('success', 'コメントを更新しました！');
        else
            return back()->with('success', 'コメントの更新はありませんでした。');
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
     * Remove some resources from storage
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request){

        // dd($request->input());
        $input = $request->input();

        // $submit_type = $request->input('submitType'); -> 一応 "delete" が入る。
        $comment_ids = $this->extractCommentIdsFromInput($input);
        Comment::whereIn('id', $comment_ids)->delete();

        if($comment_ids)
            return back()->with('success', 'コメントを削除しました！');
        else
            return back()->with('success', 'コメントの削除はありませんでした。');
    }

    /**
     * input[]からキーに"comment_"がつくもののみ抜き出して、その値（comment_id: int）を配列の形で返す。
     * @param array $input
     * @return int[]
     */
    private function extractCommentIdsFromInput($input){

        $comment_ids = [];

        foreach($input as $key => $value){
            if(preg_match('/^comment_/', $key)){
                $comment_ids[] = intval($value);
            }
        }

        return $comment_ids;
    }
}
