@if (session('msg'))
    <div class="row">
        <div class="alert alert-{{((session('status'))?'success':'danger')}}">
            {!! session('msg') !!}
        </div>
    </div>
@endif
