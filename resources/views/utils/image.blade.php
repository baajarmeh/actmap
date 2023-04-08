@if(isset($url))
<a href="{{ $url }}" data-url="{{$path}}">
    <img src="{{ $src }}" style="height: 150px;width:200px"/>
</a>
@else
<img src="{{ $src }}" data-url="{{$path}}" style="height: 150px;width:200px"/>
@endif
