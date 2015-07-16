@extends('layouts.template')


@section('content')

<table>
    <tr>
        <td>Template</td>
        <td><a href="/create_template" class="btn btn-default">Create New</a></td>
    </tr>
    @foreach($templates as $type)
        <tr>
            <td>{{$type['name']}}</td>
            <td><a class='btn btn-default' href='/editTemplate/{{$type['id']}}'>Edit</a></td>
            <td><a class='btn btn-default' href='/deleteTemplate/{{$type['id']}}'>Delete</a></td>
            <td><a class='btn btn-default' href='/duplicate/{{$type['id']}}'>Duplicate</a></td>
        </tr>
    @endforeach
</table>
@stop