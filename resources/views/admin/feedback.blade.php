<x-admin-layout>
  <h1 class="text-xl font-bold mb-4">Feedback</h1>

  <div class="rounded-xl bg-white border p-4 overflow-auto">
    <table class="w-full text-sm">
      <thead class="text-gray-500">
        <tr>
          <th class="text-left py-2">Waktu</th>
          <th class="text-left py-2">Order</th>
          <th class="text-left py-2">Rating</th>
          <th class="text-left py-2">Komentar</th>
          <th class="text-left py-2">Status</th>
          <th class="text-left py-2">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($feedback as $f)
          <tr class="border-t align-top">
            <td class="py-2">{{ $f->created_at }}</td>
            <td class="py-2">{{ $f->order?->order_code }}</td>
            <td class="py-2">{{ $f->rating ?? '-' }}</td>
            <td class="py-2 whitespace-pre-wrap">{{ $f->comment ?? '-' }}</td>
            <td class="py-2">{{ $f->status }}</td>
            <td class="py-2">
              <form method="POST" action="{{ route('admin.feedback.toggle', $f) }}">
                @csrf
                <button class="underline">{{ $f->status === 'VISIBLE' ? 'Hide' : 'Show' }}</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <div class="mt-4">
      {{ $feedback->links() }}
    </div>
  </div>
</x-admin-layout>
