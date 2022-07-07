<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;    // 追加

class TasksController extends Controller
{
    // getでtasks/にアクセスされた場合の「一覧表示処理」
    public function index()
    {
        $data = [];
        if (\Auth::check()) { // 認証済みの場合
            // 認証済みユーザを取得
            $user = \Auth::user();
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
        }
        
        // Welcomeビューでそれらを表示
        return view('welcome', $data);
    }

    // getでtasks/createにアクセスされた場合の「新規登録画面表示処理」
    public function create()
    {
        if (\Auth::check()) { // 認証済みの場合
            $task = new Task;

            // メッセージ作成ビューを表示
            return view('tasks.create', [
                'task' => $task,
            ]);
        }
        else{
            // トップページへリダイレクトさせる
            return redirect('/');
        }
    }

    // postでtasks/にアクセスされた場合の「新規登録処理」
    public function store(Request $request)
    {
        if (\Auth::check()) { // 認証済みの場合
            // バリデーション
            $request->validate([
                'content' => 'required|max:255',
                'status' => 'required|max:10',
            ]);

            // 認証済みユーザ（閲覧者）の投稿として作成（リクエストされた値をもとに作成）
            $request->user()->tasks()->create([
                'content' => $request->content,
                'status' => $request->status,
            ]);
        }
        
        // トップページへリダイレクトさせる
        return redirect('/');
    }

    // getでtasks/idにアクセスされた場合の「取得表示処理」
    public function show($id)
    {
        if (\Auth::check()) { // 認証済みの場合
            // idの値でメッセージを検索して取得
            $task = Task::findOrFail($id);
            
            // 認証済みユーザを取得
            $user = \Auth::user();
            
            if($task->user_id !== $user->id){
                // トップページへリダイレクトさせる
                return redirect('/');
            }

            // メッセージ詳細ビューでそれを表示
            return view('tasks.show', [
                'task' => $task,
            ]);
        }
        else{
            // トップページへリダイレクトさせる
            return redirect('/');
        }
        
    }

    // getでtasks/id/editにアクセスされた場合の「更新画面表示処理」
    public function edit($id)
    {
        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);

        // メッセージ編集ビューでそれを表示
        return view('tasks.edit', [
            'task' => $task,
        ]);
    }

    // putまたはpatchでtasks/idにアクセスされた場合の「更新処理」
    public function update(Request $request, $id)
    {
        // バリデーション
        $request->validate([
            'content' => 'required',
            'status' => 'required|max:10',
        ]);
        
        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);
        // メッセージを更新
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();

        // トップページへリダイレクトさせる
        return redirect('/');
    }

    // deleteでtasks/idにアクセスされた場合の「削除処理」
    public function destroy($id)
    {
        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);
        // メッセージを削除
        $task->delete();

        // トップページへリダイレクトさせる
        return redirect('/');
    }
}
