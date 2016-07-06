@extends('bot.responses.types.public')

@section('text')
    During last week you recieved {{ $last_week }} coins.
    This week you have {{ $this_week }} total amount
@endsection