<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Stockify - Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
  body {
    background-color: #f3f4f6; /* Gaya profesional abu terang */
  }
  .btn-magenta {
    @apply bg-pink-600 text-white hover:bg-pink-700 px-4 py-2 rounded;
  }
</style>
</head>
<body class="text-gray-800">

  <!-- Cek Login -->
  <script>
    const userLogin = localStorage.getItem("userLogin");
    if (!userLogin) {
      window.location.href = "login.html";
    }
  </script>

  <div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md p-6 hidden md:block">
      <h1 class="text-2xl font-bold mb-4">Halo, <span id="namaUser">User</span>!</h1>
      <h1 class="text-2xl font-bold mb-8 text-pink-600">📦 Stockify</h1>
      <nav class="space-y-4">
        <a href="{{ url('/') }}" class="block font-semibold text-pink-600">Dashboard</a>
        <a href="{{ url('/produk') }}" class="block">Manajemen Produk</a>
        <a href="{{ url('/stok') }}" class="block">Manajemen Stok</a>
        <a href="{{ url('/users') }}" class="block">Manajemen Pengguna</a>
        <a href="{{ url('/laporan') }}" class="block">Laporan</a>
        <a href="{{ url('/pengaturan') }}" class="block">Pengaturan</a>
      </nav>

      <!-- Tombol Logout -->
      <button onclick="logout()" class="mt-8 bg-pink-600 text-white px-4 py-2 rounded hover:bg-pink-700 w-full">Logout</button>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6">
      <header class="mb-6">
        <h2 class="text-2xl font-semibold">Dashboard</h2>
        <p class="text-sm text-gray-600">Ringkasan aktivitas dan data stok barang</p>
      </header>

      <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 rounded-xl shadow-sm">
          <p class="text-sm text-gray-500">Total Produk</p>
          <h3 class="text-xl font-semibold"><span id="totalProduk">0</span></h3>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm">
          <p class="text-sm text-gray-500">Barang Masuk Hari Ini</p>
          <h3 class="text-xl font-semibold"><span id="totalMasuk">0</span></h3>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm">
          <p class="text-sm text-gray-500">Barang Keluar Hari Ini</p>
          <h3 class="text-xl font-semibold"><span id="totalKeluar">0</span></h3>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm">
          <p class="text-sm text-gray-500">Stok Menipis</p>
          <h3 class="text-xl font-semibold"><span id="stokTersedia">0</span></h3>
          <p id="peringatanStok" class="text-red-600 text-sm mt-1 font-medium"></p>
        </div>
      </section>

      <section class="bg-white p-6 rounded-xl shadow-sm mb-6">
        <h3 class="text-lg font-semibold mb-4">Grafik Transaksi Barang</h3>
        <div class="w-full max-h-60">
          <canvas id="stockChart" class="w-full h-48"></canvas>
        </div>
      </section>

      <section class="bg-white p-6 rounded-xl shadow-sm">
        <h3 class="text-lg font-semibold mb-4">Aktivitas Terbaru</h3>
        <ul id="aktivitasList" class="space-y-2 text-sm">
          <li class="text-gray-500">Belum ada aktivitas.</li>
        </ul>
      </section>
    </main>
  </div>

  <!-- Script -->
  <script>
    function ambilDataHariIni(data) {
      const hariIni = new Date().toISOString().split("T")[0];
      return data.filter(item => item.tanggal === hariIni);
    }

    function tampilkanStokDashboard() {
      const masuk = JSON.parse(localStorage.getItem("barangMasuk")) || [];
      const keluar = JSON.parse(localStorage.getItem("barangKeluar")) || [];

      const totalMasuk = masuk.reduce((sum, item) => sum + Number(item.jumlah || 0), 0);
      const totalKeluar = keluar.reduce((sum, item) => sum + Number(item.jumlah || 0), 0);
      const stokTersedia = totalMasuk - totalKeluar;

      const masukHariIni = ambilDataHariIni(masuk).length;
      const keluarHariIni = ambilDataHariIni(keluar).length;

      const namaProduk = [...new Set(masuk.map(p => p.nama))];
      const stok = {};
      namaProduk.forEach(nama => {
        const masukJumlah = masuk.filter(p => p.nama === nama).reduce((sum, i) => sum + Number(i.jumlah || 0), 0);
        const keluarJumlah = keluar.filter(p => p.nama === nama).reduce((sum, i) => sum + Number(i.jumlah || 0), 0);
        stok[nama] = masukJumlah - keluarJumlah;
      });

      const stokMenipis = Object.values(stok).filter(j => j <= 5).length;

      document.getElementById("totalProduk").textContent = namaProduk.length;
      document.getElementById("totalMasuk").textContent = masukHariIni;
      document.getElementById("totalKeluar").textContent = keluarHariIni;
      document.getElementById("stokTersedia").textContent = stokTersedia;

      const peringatan = document.getElementById("peringatanStok");
      if (stokMenipis > 0) {
        peringatan.textContent = "⚠️ Stok tersedia sangat sedikit!";
        peringatan.classList.add("text-red-600");
        peringatan.classList.remove("text-green-600");
      } else {
        peringatan.textContent = "✅ Stok aman";
        peringatan.classList.add("text-green-600");
        peringatan.classList.remove("text-red-600");
      }
    }

    function tampilkanAktivitas() {
      const aktivitasList = document.getElementById("aktivitasList");
      const data = JSON.parse(localStorage.getItem("aktivitas")) || [];

      if (data.length === 0) {
        aktivitasList.innerHTML = '<li class="text-gray-500">Belum ada aktivitas.</li>';
        return;
      }

      aktivitasList.innerHTML = "";
      data.slice(-5).reverse().forEach(item => {
        const icon = item.icon || "📌";
        const role = item.role || "Pengguna";
        const aksi = item.aksi || "(aksi tidak diketahui)";
        const nama = item.nama || "Tidak diketahui";

        const li = document.createElement("li");
        li.innerHTML = `${icon} ${role} ${aksi} <strong>${nama}</strong>`;
        aktivitasList.appendChild(li);
      });
    }

    function tampilkanGrafik() {
      const masuk = JSON.parse(localStorage.getItem("barangMasuk")) || [];
      const keluar = JSON.parse(localStorage.getItem("barangKeluar")) || [];

      const hariLabels = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
      const masukData = [0, 0, 0, 0, 0, 0];
      const keluarData = [0, 0, 0, 0, 0, 0];

      masuk.forEach(item => {
        const hari = new Date(item.tanggal).getDay();
        if (hari >= 1 && hari <= 5) masukData[hari - 1] += Number(item.jumlah || 0);
      });

      keluar.forEach(item => {
        const hari = new Date(item.tanggal).getDay();
        if (hari >= 1 && hari <= 5) keluarData[hari - 1] += Number(item.jumlah || 0);
      });

      new Chart(document.getElementById('stockChart').getContext('2d'), {
        type: 'line',
        data: {
          labels: hariLabels,
          datasets: [
            {
              label: 'Barang Masuk',
              data: masukData,
              backgroundColor: 'rgba(236, 72, 153, 0.2)',
              borderColor: 'rgba(236, 72, 153, 1)',
              borderWidth: 2
            },
            {
              label: 'Barang Keluar',
              data: keluarData,
              backgroundColor: 'rgba(190, 24, 93, 0.2)',
              borderColor: 'rgba(190, 24, 93, 1)',
              borderWidth: 2
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false
        }
      });
    }

    function logout() {
      localStorage.removeItem("userLogin");
      window.location.href = "login.html";
    }

    // Jalankan semua
    tampilkanStokDashboard();
    tampilkanAktivitas();
    tampilkanGrafik();

    const nama = localStorage.getItem("userName");
    if (nama) {
      document.getElementById("namaUser").textContent = nama;
    }
  </script>
</body>
</html>
