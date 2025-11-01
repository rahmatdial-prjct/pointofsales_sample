document.addEventListener('DOMContentLoaded', function () {
    // Existing salesChart code (if any) can remain here

    // Render laporan kinerja chart if reportData is available
    if (typeof reportData !== 'undefined' && reportData) {
        var ctx = document.getElementById('kinerjaChart').getContext('2d');

        var salesData = {};
        reportData.transactions.forEach(function(transaction) {
            var date = transaction.created_at.substr(0, 10);
            if (!salesData[date]) {
                salesData[date] = 0;
            }
            salesData[date] += transaction.total;
        });

        var labels = Object.keys(salesData);
        var data = Object.values(salesData);

        if(window.kinerjaChartInstance) {
            window.kinerjaChartInstance.destroy();
        }

        window.kinerjaChartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Penjualan',
                    data: data,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
});
