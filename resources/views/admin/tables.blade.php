<x-admin-layout>
  <h1 class="text-xl font-bold mb-4">Tables & QR</h1>

  <form class="rounded-xl bg-white border p-4 mb-4" method="POST" action="{{ route('admin.tables.store') }}">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
      <input class="rounded border px-3 py-2" type="number" name="table_no" placeholder="No meja" required>
      <input class="rounded border px-3 py-2" name="name" placeholder="Nama (opsional)">
      <button class="rounded bg-gray-900 text-white px-4 py-2 font-semibold">Tambah Meja</button>
    </div>
  </form>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($tables as $t)
      @php $token = $t->activeToken(); @endphp
      <div class="rounded-xl bg-white border shadow-sm overflow-hidden" id="qr-card-{{ $t->id }}">
        <!-- QR Card Header -->
        <div class="bg-gradient-to-r from-primary-600 to-primary-700 text-white px-4 py-3 text-center">
          <div class="text-lg font-bold">{{ config('app.name', 'Cafe QR') }}</div>
        </div>
        
        <!-- QR Code Area -->
        <div class="p-6 flex flex-col items-center">
          @if($token)
            <!-- QR Code Container - will be filled by JavaScript -->
            <div id="qr-container-{{ $t->id }}" 
                 class="w-48 h-48 border-4 border-gray-100 rounded-lg shadow-sm flex items-center justify-center bg-white"
                 data-url="{{ url('/t/'.$token->token) }}">
              <div class="text-gray-400 text-sm">Loading QR...</div>
            </div>
          @else
            <div class="w-48 h-48 bg-gray-100 rounded-lg flex items-center justify-center">
              <span class="text-gray-400 text-sm">No QR Token</span>
            </div>
          @endif
          
          <!-- Table Number Badge -->
          <div class="mt-4 bg-gray-900 text-white px-6 py-2 rounded-full">
            <span class="text-sm font-medium">MEJA</span>
            <span class="text-2xl font-bold ml-1">{{ $t->table_no }}</span>
          </div>
          
          @if($t->name)
            <div class="mt-2 text-sm text-gray-600">{{ $t->name }}</div>
          @endif
          
          <!-- Instruction Message -->
          <div class="mt-4 text-center text-xs text-gray-500 px-4 leading-relaxed">
            Silahkan scan QR untuk pesan<br>atau langsung ke kasir
          </div>
        </div>

        <!-- Actions -->
        @if($token)
        <div class="border-t px-4 py-3 bg-gray-50">
          <div class="text-xs text-gray-500 mb-2 truncate">
            <span class="font-medium">URL:</span> 
            <span class="font-mono">{{ url('/t/'.$token->token) }}</span>
          </div>
          
          <!-- Download Buttons -->
          <div class="grid grid-cols-2 gap-2 mb-2">
            <button onclick="downloadQR({{ $t->id }}, {{ $t->table_no }}, 'png')" 
               class="flex items-center justify-center gap-1 py-2 px-3 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
              </svg>
              PNG
            </button>
            <button onclick="printQR({{ $t->id }}, {{ $t->table_no }})" 
               class="flex items-center justify-center gap-1 py-2 px-3 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition-colors">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
              </svg>
              Print
            </button>
          </div>
          
          <!-- Rotate Token -->
          <form id="rotate-form-{{ $t->id }}" class="hidden" method="POST" action="{{ route('admin.tables.rotate', $t) }}">@csrf</form>
          <button type="button" onclick="confirmRotateToken('rotate-form-{{ $t->id }}', '{{ $t->table_no }}')" 
                  class="w-full py-2 bg-gray-900 text-white rounded-lg text-sm font-medium hover:bg-gray-800 transition-colors">
            Rotate Token
          </button>
        </div>
        @endif
      </div>
    @endforeach
  </div>

  <!-- QRCode.js Library -->
  <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
  
  <script>
    // Generate QR codes on page load
    document.addEventListener('DOMContentLoaded', function() {
      document.querySelectorAll('[id^="qr-container-"]').forEach(container => {
        const url = container.dataset.url;
        if (url) {
          container.innerHTML = ''; // Clear loading text
          QRCode.toCanvas(url, { 
            width: 180, 
            margin: 2,
            color: { dark: '#000000', light: '#ffffff' }
          }, function(error, canvas) {
            if (error) {
              console.error(error);
              container.innerHTML = '<div class="text-red-500 text-xs">QR Error</div>';
              return;
            }
            canvas.style.borderRadius = '8px';
            container.appendChild(canvas);
          });
        }
      });
    });

    // Download QR as PNG
    function downloadQR(tableId, tableNo, format) {
      const container = document.getElementById('qr-container-' + tableId);
      const canvas = container.querySelector('canvas');
      
      if (!canvas) {
        alert('QR Code belum ter-generate');
        return;
      }
      
      // Create styled image
      const cafeName = '{{ config("app.name", "Cafe QR") }}';
      const width = 400;
      const height = 520;
      
      const styledCanvas = document.createElement('canvas');
      styledCanvas.width = width;
      styledCanvas.height = height;
      const ctx = styledCanvas.getContext('2d');
      
      // Background
      ctx.fillStyle = '#ffffff';
      ctx.fillRect(0, 0, width, height);
      
      // Header
      ctx.fillStyle = '#059669';
      ctx.fillRect(0, 0, width, 50);
      
      // Cafe name
      ctx.fillStyle = '#ffffff';
      ctx.font = 'bold 20px Arial';
      ctx.textAlign = 'center';
      ctx.fillText(cafeName, width/2, 32);
      
      // QR Code (centered)
      const qrSize = 280;
      const qrX = (width - qrSize) / 2;
      ctx.drawImage(canvas, qrX, 70, qrSize, qrSize);
      
      // Table badge
      ctx.fillStyle = '#1f2937';
      const badgeWidth = 140;
      const badgeHeight = 40;
      const badgeX = (width - badgeWidth) / 2;
      const badgeY = 370;
      ctx.beginPath();
      ctx.roundRect(badgeX, badgeY, badgeWidth, badgeHeight, 20);
      ctx.fill();
      
      ctx.fillStyle = '#ffffff';
      ctx.font = 'bold 22px Arial';
      ctx.fillText('MEJA ' + tableNo, width/2, badgeY + 28);
      
      // Instruction
      ctx.fillStyle = '#6b7280';
      ctx.font = '14px Arial';
      ctx.fillText('Silahkan scan QR untuk pesan', width/2, 440);
      ctx.fillText('atau langsung ke kasir', width/2, 460);
      
      // Border
      ctx.strokeStyle = '#e5e7eb';
      ctx.lineWidth = 2;
      ctx.strokeRect(1, 1, width-2, height-2);
      
      // Download
      const link = document.createElement('a');
      link.download = 'QR_Meja_' + tableNo + '.png';
      link.href = styledCanvas.toDataURL('image/png');
      link.click();
    }
    
    // Print QR
    function printQR(tableId, tableNo) {
      const container = document.getElementById('qr-container-' + tableId);
      const canvas = container.querySelector('canvas');
      
      if (!canvas) {
        alert('QR Code belum ter-generate');
        return;
      }
      
      const cafeName = '{{ config("app.name", "Cafe QR") }}';
      const qrDataUrl = canvas.toDataURL('image/png');
      
      const printWindow = window.open('', '_blank');
      printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
          <title>QR Meja ${tableNo}</title>
          <style>
            @page { size: 10cm 13cm; margin: 0; }
            body { 
              margin: 0; 
              display: flex; 
              justify-content: center; 
              align-items: center; 
              min-height: 100vh;
              font-family: Arial, sans-serif;
            }
            .card {
              width: 10cm;
              border: 1px solid #ccc;
              text-align: center;
            }
            .header {
              background: linear-gradient(to right, #059669, #047857);
              color: white;
              padding: 15px;
              font-size: 24px;
              font-weight: bold;
            }
            .qr-area { padding: 20px; }
            .qr-area img { width: 7cm; height: 7cm; }
            .table-badge {
              display: inline-block;
              background: #1f2937;
              color: white;
              padding: 10px 30px;
              border-radius: 30px;
              font-size: 28px;
              font-weight: bold;
              margin: 15px 0;
            }
            .instruction {
              color: #6b7280;
              font-size: 14px;
              padding-bottom: 20px;
            }
          </style>
        </head>
        <body>
          <div class="card">
            <div class="header">${cafeName}</div>
            <div class="qr-area">
              <img src="${qrDataUrl}" alt="QR Code">
            </div>
            <div class="table-badge">MEJA ${tableNo}</div>
            <div class="instruction">
              Silahkan scan QR untuk pesan<br>atau langsung ke kasir
            </div>
          </div>
          <script>window.onload = function() { window.print(); }</script>
        </body>
        </html>
      `);
      printWindow.document.close();
    }
  </script>
</x-admin-layout>
