// Dashboard Chart Initialization
let myChart = null;

function initGrossProfitChart(data) {
    if (!data || !data.labels || !data.values) return;
    const storeKeys = data.labels || [];
    const storeNames = data.store_names || [];
    const displayLabels = storeNames.length ? storeNames : storeKeys;

    const ctx = document.getElementById('grossProfitChart');
    if (!ctx) return;
    
    const chartCtx = ctx.getContext('2d');
    if (myChart) myChart.destroy();

    const gradient = chartCtx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(99, 102, 241, 0.7)');
    gradient.addColorStop(1, 'rgba(168, 85, 247, 0.3)');

    myChart = new Chart(chartCtx, {
        type: 'bar',
        data: {
            labels: displayLabels,
            datasets: [{
                label: 'Gross Profit per Store',
                data: data.values,
                backgroundColor: gradient,
                borderColor: '#6366f1',
                borderWidth: 2,
                borderRadius: 10,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: { duration: 1800, easing: 'easeOutQuart' },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                    callbacks: {
                        title: items => {
                            if (!items.length) return '';
                            const idx = items[0].dataIndex;
                            const name = storeNames[idx] ?? displayLabels[idx] ?? 'Store';
                            const key = storeKeys[idx] ?? null;
                            return key ? `${name} (Key: ${key})` : name;
                        },
                        label: ctx => 'Gross Profit: Rp ' + ctx.parsed.y.toLocaleString('id-ID')
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { callback: v => 'Rp ' + v.toLocaleString('id-ID') }
                }
            }
        }
    });
}

function initQuantityCharts(chartAwal, chartAkhir) {
    // QUANTITY AWAL HARI
    if (chartAwal) {
        const ctxAwal = document.getElementById('chartAwal');
        if (ctxAwal) {
            new Chart(ctxAwal, {
                type: 'bar',
                data: {
                    labels: chartAwal.labels,
                    datasets: [{
                        label: "Quantity Awal Hari",
                        data: chartAwal.values,
                        backgroundColor: "rgba(99,102,241,0.6)",
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });
        }
    }

    // QUANTITY AKHIR HARI
    if (chartAkhir) {
        const ctxAkhir = document.getElementById('chartAkhir');
        if (ctxAkhir) {
            new Chart(ctxAkhir, {
                type: 'bar',
                data: {
                    labels: chartAkhir.labels,
                    datasets: [{
                        label: "Quantity Akhir Hari",
                        data: chartAkhir.values,
                        backgroundColor: "rgba(16,185,129,0.6)",
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });
        }
    }
}

// Initialize charts when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    if (window.dashboardData) {
        if (window.dashboardData.grossProfit) {
            initGrossProfitChart(window.dashboardData.grossProfit);
        }
        if (window.dashboardData.chartAwal || window.dashboardData.chartAkhir) {
            initQuantityCharts(window.dashboardData.chartAwal, window.dashboardData.chartAkhir);
        }
    }
});

