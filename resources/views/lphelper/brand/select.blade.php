@php
$i=0;

@endphp
@foreach ($brands as $key => $brand)
@php
if(!is_array($select)){

    if ($brand->id == $select) {
        $confirmck="selected";
    }
    else{
        $confirmck="";
    }
}
else{
    $confirmck="";
}
   
@endphp
<option value="{{ $brand->id }}" {{ $confirmck  }} > {{ $brand->name }}</option>

@endforeach
