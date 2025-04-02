@extends('adminlte::page')

@section('title', 'Danh sách bài viết')

@section('content_header')
    <h1>Danh sách bài viết</h1>
@stop

@section('content')
    <a href="{{ route('posts.create') }}" class="btn btn-primary mb-3">Thêm bài viết mới</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tiêu đề</th>
                <th>Ngày tạo</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($posts as $post)
                <tr>
                    <td>{{ $post->id }}</td>
                    <td>{{ $post->title }}</td>
                    <td>{{ $post->created_at }}</td>
                    <td>
                        <a href="{{ route('posts.show', $post) }}" class="btn btn-info">Xem</a>
                        <a href="{{ route('posts.edit', $post) }}" class="btn btn-warning">Sửa</a>
                        <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@stop
