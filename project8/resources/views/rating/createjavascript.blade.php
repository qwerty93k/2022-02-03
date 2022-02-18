@extends('layouts.app')

<style>
    p {
        color:red;
    }

    #test {
        color: red;
    }

    .test {
        color: red;
    }

</style>

@section('content')
<div class="container">

    <button id="add_field" class="btn btn-primary">Add</button>
    <button id="remove_field" class="btn btn-danger">Remove</button>

    <input id="input_count" type="number" value='1'>
    <button id="submit_number" class="btn btn-primary">Ok</button>

    <form method="POST" action="{{route('rating.storejavascript')}}">
        @csrf
    
            <div class="input_rating">
                <input type="text" name="rating_title[]" value="test" />
                <input type="number" name="rating_rating[]" value="1" />
            </div>
        <div class="info"></div>
        <button type="submit" class="btn btn-primary">Save</button>
</form>
</div>
<script>
    //JQuery

    $(document).ready(function(){
        //ad_field mygtukas aff_field isves teksta "mygtukas paspaustas"i p zyme kurios klase yra info
        $('#add_field').click(function(){
            $('.info').append('<div class="input_rating"><input type="text" name="rating_title[]" value="test" /><input type="number" name="rating_rating[]" value="1" /></div>');
        });

        $('#remove_field').click(function(){
            //paimti paskutine klase
            $('.input_rating:last-child').remove();
        });

        $('#submit_number').click(function(){
            //is input lauko turi buti paimama jo reiksme input_count
            //input count sukasi ciklas rie div info tiesiog tiek kartu appendina inputus
            // kiek kartu nurodyta
            let input_count;
            input_count = $('#input_count').val();
            for(let i=0; i<input_count; i++)
            $('.info').append('<div class="input_rating"><input type="text" name="rating_title[]" value="test" /><input type="number" name="rating_rating[]" value="1" /></div>');
        });
        console.log('dokumentas uzsikrove');
    });

</script>    
@endsection