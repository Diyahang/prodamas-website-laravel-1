<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;
use DB;
use File;

class ArticleController extends Controller
{
    public function create()
    {
        $statuss = article::all();
        return view('admin.article.add', compact('statuss'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'status' => 'required',
            'kategori' => 'required',
            'gambar_sampul' => 'mimes:jpeg,jpg,png|max:2200',
            'text_sampul' => 'required',
            'judul' => 'required',
            'slug' => 'required',
            'article' => 'required',
            //'picture' => 'mimes:jpeg,jpg,png|max:2200'
        ]);

        //$picture = $request->picture;
        //$new_picture = time() . ' - ' . $picture->getClientOriginalName();

        $gambar_sampul = $request->gambar_sampul;
        $new_sampul = time() . ' - ' . $gambar_sampul->getClientOriginalName();


        article::create([
            "status" => $request["status"],
            "kategori" => $request["kategori"],
            "gambar_sampul" => $new_sampul,
            "text_sampul" => $request["text_sampul"],
            "judul" => $request["judul"],
            "slug" => $request["slug"],
            "article" => $request["article"],
            //"picture" => $new_picture
        ]);

        //$picture->move('img-upload/', $new_picture);
        $gambar_sampul->move('img-artikel-sampul/', $new_sampul);

        return redirect('/admin/list-article')->with('success', 'Data submission successful!');
    }

    public function index()
    {
        $articles = article::all();
        return view('admin.article.list', compact('articles'));
    }

    // public function show($id) {
    //     $submission = DB::table('submission')->where('id', $id)->first();
    //     return view('admin.show',compact('submission'));
    // }

    public function edit($id) {
        $article = article::find($id)->first();
        return view('admin.article.edit',compact('article'));
    }

    public function update($id, Request $request) {
        $request->validate([
            'status' => 'required',
            'kategori' => 'required',
            'gambar_sampul' => 'mimes:jpeg,jpg,png|max:2200',
            'text_sampul' => 'required',
            'judul' => 'required',
            'slug' => 'required',
            'article' => 'required',
            'picture' => 'mimes:jpeg,jpg,png|max:2200'
        ]);

        $article = article::findorfail($id);
        if ($request->has('picture')) {
            File::delete("img-upload/".$article->picture);
            $picture = $request->picture;
            $new_picture = time() . ' - ' . $picture->getClientOriginalName();
            $picture->move('img-upload/', $new_picture);
            $article_data = [
                "status" => $request["status"],
                "kategori" => $request["kategori"],
                "gambar_sampul" => $new_picture,
                "text_sampul" => $new_picture["text-sampul"],
                "judul" => $request["judul"],
                "slug" => $request["slug"],
                "article" => $request["article"],
                //"picture" => $new_picture
            ];
        } else {
            $article_data = [
                "status" => $request["status"],
                "kategori" => $request["kategori"],
                //"gambar-sampul" => $new_picture["gambar-sampul"],
                "text_sampul" => $request["text_sampul"],
                "judul" => $request["judul"],
                "slug" => $request["slug"],
                "article" => $request["article"]
            ];
        }
        
        $article->update($article_data);

        return redirect('/admin/list-article')->with('success', 'Submission successfully updated!');
    }

    public function destroy($id) {
        $submission = DB::table('articles')->where('id', $id)->delete();
        return redirect('/admin/list-article')->with('success', 'Submission successfully deleted!');
    }

    public function upload($id) {
        $article = Article::where('id',$id)->first();
        return view('layouts.article_content',compact('article')); 
    }

    public function list_content() {
        $articles = Article::all();
        return view('layouts.article',compact('articles')); 
    }
    
}
