<x-admin-layout>
  <div class="flex items-center justify-between">
    <h1 class="text-xl font-bold mb-4">Reports</h1>
    <div class="flex gap-2">
      <a class="px-3 py-2 rounded border {{ $range==='7d'?'bg-gray-900 text-white':'' }}" href="{{ route('admin.reports', ['range'=>'7d']) }}">7d</a>
      <a class="px-3 py-2 rounded border {{ $range==='30d'?'bg-gray-900 text-white':'' }}" href="{{ route('admin.reports', ['range'=>'30d']) }}">30d</a>
    </div>
  </div>

  <div class="rounded-xl bg-white border p-4">
    <canvas id="chart" height="120"></canvas>
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
