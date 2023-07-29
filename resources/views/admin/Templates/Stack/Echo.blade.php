<script>
    Echo.channel('{{\Illuminate\Support\Str::slug(auth('admin')->id(), '_')}}')
        .listen('.update-your-timezone', function (data) {
            updateTimezone(data);
        });

</script>
