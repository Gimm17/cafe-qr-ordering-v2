<x-admin-layout title="Reports">
  <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-6">
    <div>
      <p class="text-sm text-muted">Grafik penjualan (PAID) berdasarkan range.</p>
    </div>
    <div class="flex gap-2">
      <a class="tap-44 px-4 py-2 rounded-xl border border-line font-semibold {{ $range==='7d' ? 'bg-gray-900 text-white' : 'bg-white text-gray-800 hover:bg-gray-50' }}" href="{{ route('admin.reports', ['range'=>'7d']) }}">7d</a>
      <a class="tap-44 px-4 py-2 rounded-xl border border-line font-semibold {{ $range==='30d' ? 'bg-gray-900 text-white' : 'bg-white text-gray-800 hover:bg-gray-50' }}" href="{{ route('admin.reports', ['range'=>'30d']) }}">30d</a>
    </div>
  </div>

  <div class="ui-card p-5">
    <canvas id="chart" height="120"></canvas>
    <p class="text-xs text-muted mt-3">Sumber: transaksi order berstatus PAID.</p>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const labels = @json($labels);
    const data = @json($values);

    new Chart(document.getElementById('chart'), {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{ label: 'Sales (PAID)', data: data }]
      },
      options: {
        responsive: true,
        plugins: { legend: { display: true } }
      }
    });
  </script>
</x-admin-layout>
