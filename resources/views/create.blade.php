@extends('layouts.app')

@section('content')
<div class="card">
<div class="card-header">新規メモ作成</div>
   <form class="card-body" action="{{ route('store') }}" method="POST">
    @csrf
      <div class="form-group">
        <textarea class="form-control mb-3" name="content" rows="3" placeholder="メモ入力"></textarea>
      </div>
      @foreach ( $tags as $t )
      <div class="form-check form-switch">
          {{-- テェックボックスで複数の値を送りたい時 name="tags[]"--}}
          <input class="form-check-input" type="checkbox" name="tags[]" id="{{ $t['id'] }}" value="{{ $t['id'] }}">
          <label class="form-check-label" for="{{ $t['id'] }}">{{  $t['name'] }}</label>
      </div>
      @endforeach
            <input type="text" class="form-control w-50 mb-3" name="new_tag" placeholder="タグ入力"/>
            <button type="submit" class="btn btn-primary">保存</button>
          </form>
      </div>
      @endsection
