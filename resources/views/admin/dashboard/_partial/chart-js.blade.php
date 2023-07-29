@push('scripts')
    <script>
        $("div").find("[data-id]").each(function () {
            chartFormResponse(this.getAttribute("data-id"), this.getAttribute("data-route"));
        });

        $('.refresh-chart').on('click', function (e) {
            e.preventDefault();
            var parentChartId = $(this).parents('form').attr('id').replace('_form', '');
            $("[data-id=" + parentChartId + "] canvas").remove();
            $route = $(this).parents('form').parents('div').attr('data-route');
            chartFormResponse(parentChartId, $route);
        })
    </script>
@endpush
