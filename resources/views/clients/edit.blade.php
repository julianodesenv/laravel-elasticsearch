@extends('app')

@section('content')
    <div class="container">
        <h3>Editar cliente</h3>

        {!! Form::open(['route'=>['clients.update', $client['_id']], 'class'=>'form']) !!}
        {{ method_field('PUT') }}
        <div class="form-group">
            {!! Form::label('name', 'Nome:') !!}
            {!! Form::text('name', $client['_source']['name'], ['class'=>'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('estado', 'Estado:') !!}
            {!! Form::text('estado', $client['_source']['estado'], ['class'=>'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::submit('Editar', ['class'=>'btn btn-primary']) !!}
        </div>

        {!! Form::close() !!}
    </div>

@endsection
