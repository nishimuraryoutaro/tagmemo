<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memo;
use App\Models\Tag;
use App\Models\MemoTag;
use DB;
class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $memos = Memo::select('memos.*')
        ->where('user_id', '=', \Auth::id())
        ->whereNull('deleted_at')
        ->orderBy('updated_at', 'DESC')
        ->get();

        $tags = Tag::where('user_id', '=', \Auth::id())
        ->whereNull('deleted_at')
        ->orderBy('id', 'DESC') 
        ->get();

        return view('create', compact('memos', 'tags'));
    }
    public function store(Request $request)
    {
        $posts = $request->all();

        //トランザクション
        DB::transaction(function() use($posts) {
            $memo_id =  Memo::insert(['content' => $posts['content'], 'user_id' => \Auth::id()]);
            //tagが存在するか
            $tag_exists = Tag::where('user_id', '=', \Auth::id())//ログインしてるuser_idがあるか
            ->where('name', '=',$posts['new_tag'])//nameが存在するか
            ->exists();//上記が存在するか
            //新規タグが入力されているか
            if(!empty($posts['new_tag']) && !$tag_exists) {
                //ログインしているidと送ったnew_tagをtag_idに
                $tag_id = Tag::insertGetId(['user_id' => \Auth::id(), 'name' => $posts['new_tag']]);
                //memo_tagにメモとタグを結びつける
                MemoTag::insert(['memo_id' => $memo_id, 'tag_id' => $tag_id]);
            }

            foreach($posts['tags'] as $tag){
                MemoTag::insert(['memo_id' => $memo_id, 'tag_id' =>$tag]);
            }
        });



       
        return view('create');
    }

    public function edit($id)
    {
        //メモデータ取得//where分でログインしている人で絞り込む
        $memos = Memo::select('memos.*')
            ->where('user_id', '=', \Auth::id())
            ->whereNull('deleted_at')
            ->orderBy('updated_at', 'DESC')//ASCで小さいDESCで大きい順
            ->get();

        $edit_memo = Memo::find($id);

        return view('edit', compact('memos','edit_memo'));
    }
    public function update(Request $request)
    {
        $posts = $request->all();
       //dd($posts);
        Memo::where('id', $posts['memo_id'])
        ->update(['content' => $posts['content']]);

        return redirect( route('home'));
    }
    public function destory(Request $request)
    {
        $posts = $request->all();
       //dd($posts);
        Memo::where('id', $posts['memo_id'])
        ->update(['deleted_at' => date("Y-m-d:H:i:s", time())]);

        return redirect( route('home'));
    }
}
