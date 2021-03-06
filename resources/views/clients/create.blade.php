@extends('app')

@section('content')
    <div class="container">
        <h3>Novo cliente</h3>

        {!! Form::open(['route'=>'clients.store', 'class'=>'form']) !!}
        <div class="form-group">
            {!! Form::label('name', 'Nome:') !!}
            {!! Form::text('name', null, ['class'=>'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('estado', 'Estado:') !!}
            {!! Form::text('estado', null, ['class'=>'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::submit('Criar', ['class'=>'btn btn-primary']) !!}
        </div>

        {!! Form::close() !!}

    </div>

@endsection
