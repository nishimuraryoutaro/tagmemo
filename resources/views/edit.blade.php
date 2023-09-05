{{-- @extends('layouts.app') --}}

{{-- @section('content') --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    </head>
    <body>
      <div class="card">
      <div class="card-header">編集
        <form class="card-body" action="{{ route('destory') }}" method="POST">
          @csrf
          <input type="hidden" name="memo_id" value="{{ $edit_memo['id'] }}"/>
          <button tyoee="submit">削除</button>
      </div>
        <form class="card-body" action="{{ route('update') }}" method="POST">
          @csrf
          <input type="hidden" name="memo_id" value="{{ $edit_memo['id'] }}"/>
            <div class="form-group">
              <textarea class="form-control" name="content" rows="3" placeholder="メモ入力">{{ $edit_memo['content'] }}</textarea>
            </div>
          <button type="submit" class="btn btn-primary">更新</button>
        </form>
      </div>
    </body>
</html>

{{-- @endsection --}}
