@push('scripts')
    <script>
        // last 10 days by doctor
        var all_calls_by_doctors = document.getElementById('all_calls_by_doctors').getContext('2d');
        var all_calls_by_doctors_chart = new Chart(all_calls_by_doctors, {
            type: 'pie',
        });
        axios({
            method: 'POST',
            url: '{{ route('admin.statistics.all_calls_count_by_doctors') }}'
        }).then((response) => {
            let data = response.data.data;
            all_calls_by_doctors_chart.data.labels = Object.keys(data);
            all_calls_by_doctors_chart.data.datasets.push({
                data: Object.values(data),
                borderWidth: 2,
                backgroundColor: ChartColors,
                fill: false,
            });
            all_calls_by_doctors_chart.update();
        }).catch(error => {
            ChartNoData(all_calls_by_doctors_chart);
        });
    </script>
@endpush
