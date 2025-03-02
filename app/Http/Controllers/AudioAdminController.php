<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Audio;
use DB;
use File;

class AudioAdmin extends Controller
{
    public function create()
    {
        return view('admin.audio.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'gambar_sampul' => 'required|file|image|max:2200',
            'caption' => 'required',
            'judul' => 'required',
            'konten' => 'required|mimes:audio/mpeg,mpga,mp3,wav,aac',
        ]);
        // dump($request);
        // sampul
        $extThumb = $request->gambar_sampul->getClientOriginalExtension();
        $pathThumb = "sampul-".time().".".$extThumb;
        $pathStore = $request->gambar_sampul->move(public_path('audioProd/sampul'), $pathThumb);

        // konten audio
        $konten = $request->file('konten');
        $audioname = $konten->getClientOriginalName();
        $audiopath = $konten->storeAs('konten', $audioname);
        $pathStore = $request->konten->move(public_path('audioProd/audio'), $audiopath);
/*
        $audio_sampul = $request->audio_sampul;
        $new_audio_sampul = time() . ' - ' . $audio_sampul->getClientOriginalName();

        $konten = $request->konten;
        $new_konten = time() . ' - ' . $konten->getClientOriginalName();
*/
        audio::create([
            "gambar_sampul" => $pathThumb,
            "judul" => $request["judul"],
            "konten" => $audioname,
            "caption" => $request["caption"],
        ]);

        return redirect('/admin/list-audio')->with('success', 'Audio Berhasil Ditambahkan!');
    }

    public function index()
    {
        $audios = audio::all();
        return view('admin.audio.list', compact('audios'));
    }
/*
    public function show($id) {
        $submission = DB::table('submission')->where('id', $id)->first();
        return view('admin.show',compact('submission'));
    }
*/
    public function edit($id) {
        $audio = audio::find($id)->first();
        return view('admin.audio.edit',compact('audio'));
    }

    public function update($id, Request $request) {
        $request->validate([
            'gambar_sampul' => 'required|file|image|max:2200',
            'judul' => 'required',
            'konten' => 'nullable|audio', 
            'caption' => 'required'
        ]);

        $audio = audio::findorfail($id);
        if ($request->has('picture')) {
            File::delete("img-audio-sampul/".$audio->picture);
            $picture = $request->picture;
            $new_audio_sampul = time() . ' - ' . $picture->getClientOriginalName();
            $picture->move('img-audio-sampul/', $new_audio_sampul);
/*
            $request->has('konten_picture')) {
            File::delete("img-audio-konten/".$audio->konten_picture);
            $konten_picture = $request->konten_picture;
            $new_audio_konten = time() . ' - ' . $konten_picture->getClientOriginalName();
            $konten_picture->move('img-audio-konten/', $new_audio_sampul);
*/
            $audio_data = [
                "gambar_sampul" => $new_audio_sampul,
                "text_sampul" => ["text-sampul"],
                "judul" => $request["judul"],
                "slug" => $request["slug"],
                "konten" => $new_konten,
                "caption" => $request["caption"],
            ];
        } else {
            $audio_data = [
                "gambar-sampul" => $new_picture["gambar-sampul"],
                "text_sampul" => $request["text_sampul"],
                "judul" => $request["judul"],
                "slug" => $request["slug"],
                "konten" => $new_konten,
                "caption" => $request["caption"]
            ];
        }
        
        $audio->update($audio_data);

        return redirect('/admin/list-audio')->with('success', 'Submission successfully updated!');
    }

    public function destroy($id) {
        $submission = DB::table('audios')->where('id', $id)->delete();
        return redirect('/admin/list-audio')->with('success', 'Submission successfully deleted!');
    }
/*
    public function upload($id) {
        $audio = Audio::where('id',$id)->first();
        return view('layouts.audio_content',compact('article')); //belum ditambahin ke layout
    }

    public function list_content() {
        $audios = Audio::all();
        return view('layouts.audio',compact('audios')); 
    }
*/}
