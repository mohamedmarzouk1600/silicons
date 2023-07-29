@if (session('msg'))
    <div class="alert alert-{{((session('status'))?'success':'danger')}}">
        {!! session('msg') !!}
    </div>
@endif
