<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Stockify - Manajemen Stok</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
</head>
<body class="bg-pink-50 text-gray-800">
  <div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md p-6 hidden md:block">
      <h1 class="text-2xl font-bold mb-8 text-pink-600">📦 Stockify</h1>
      <nav class="space-y-4">
        <a href="{{ url('/') }}" class="block">Dashboard</a>
        <a href="{{ url('/produk') }}" class="block">Manajemen Produk</a>
        <a href="{{ url('/stok') }}" class="block font-semibold text-pink-600">Manajemen Stok</a>
        <a href="{{ url('/users') }}" class="block">Manajemen Pengguna</a>
        <a href="{{ url('/laporan') }}" class="block">Laporan</a>
        <a href="#" class="block">Pengaturan</a>
      </nav>
    </aside>

    <!-- Main -->
    <main class="flex-1 p-6">
      <div class="mb-6">
        <h2 class="text-2xl font-semibold">Manajemen Stok</h2>
        <p class="text-sm text-gray-500">Catat transaksi barang masuk dan keluar</p>
      </div>

      <!-- Tabs -->
      <div class="mb-6">
        <button id="tabMasuk" class="px-4 py-2 bg-pink-600 text-white rounded mr-2">Barang Masuk</button>
        <button id="tabKeluar" class="px-4 py-2 bg-gray-200 text-gray-800 rounded">Barang Keluar</button>
      </div>

      <!-- Barang Masuk -->
      <section id="masukSection" class="bg-white p-6 rounded-xl shadow-sm">
        <h3 class="text-lg font-semibold mb-4">Barang Masuk</h3>
        <form id="formMasuk" class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
          <input type="text" placeholder="Nama Barang" class="border p-2 rounded" required />
          <input type="number" placeholder="Jumlah" class="border p-2 rounded" required />
          <input type="text" placeholder="Supplier" class="border p-2 rounded" required />
          <input type="date" class="border p-2 rounded" required />
          <input type="text" placeholder="Keterangan" class="border p-2 rounded md:col-span-2" />
          <div class="col-span-1 md:col-span-2">
            <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded">Simpan</button>
          </div>
        </form>
        <table class="min-w-full text-sm" id="tabelMasuk">
          <thead>
            <tr class="bg-gray-100 text-left">
              <th class="p-2">Nama</th>
              <th class="p-2">Jumlah</th>
              <th class="p-2">Supplier</th>
              <th class="p-2">Tanggal</th>
              <th class="p-2">Keterangan</th>
              <th class="p-2">Aksi</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </section>

      <!-- Barang Keluar -->
      <section id="keluarSection" class="bg-white p-6 rounded-xl shadow-sm hidden">
        <h3 class="text-lg font-semibold mb-4">Barang Keluar</h3>
        <form id="formKeluar" class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
          <input type="text" placeholder="Nama Barang" class="border p-2 rounded" required />
          <input type="number" placeholder="Jumlah" class="border p-2 rounded" required />
          <input type="text" placeholder="Tujuan" class="border p-2 rounded" required />
          <input type="date" class="border p-2 rounded" required />
          <input type="text" placeholder="Keterangan" class="border p-2 rounded md:col-span-2" />
          <div class="col-span-1 md:col-span-2">
            <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded">Simpan</button>
          </div>
        </form>
        <table class="min-w-full text-sm" id="tabelKeluar">
          <thead>
            <tr class="bg-gray-100 text-left">
              <th class="p-2">Nama</th>
              <th class="p-2">Jumlah</th>
              <th class="p-2">Tujuan</th>
              <th class="p-2">Tanggal</th>
              <th class="p-2">Keterangan</th>
              <th class="p-2">Aksi</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </section>
    </main>
  </div>

 <script>
    const masukBtn = document.getElementById("tabMasuk");
    const keluarBtn = document.getElementById("tabKeluar");
    const masukSection = document.getElementById("masukSection");
    const keluarSection = document.getElementById("keluarSection");

    masukBtn.addEventListener("click", () => {
      masukSection.classList.remove("hidden");
      keluarSection.classList.add("hidden");
      masukBtn.classList.replace("bg-gray-200", "bg-pink-600");
      masukBtn.classList.replace("text-gray-800", "text-white");
      keluarBtn.classList.replace("bg-pink-600", "bg-gray-200");
      keluarBtn.classList.replace("text-white", "text-gray-800");
    });

    keluarBtn.addEventListener("click", () => {
      masukSection.classList.add("hidden");
      keluarSection.classList.remove("hidden");
      keluarBtn.classList.replace("bg-gray-200", "bg-pink-600");
      keluarBtn.classList.replace("text-gray-800", "text-white");
      masukBtn.classList.replace("bg-pink-600", "bg-gray-200");
      masukBtn.classList.replace("text-white", "text-gray-800");
    });

    const formMasuk = document.getElementById("formMasuk");
    const formKeluar = document.getElementById("formKeluar");
    const tabelMasuk = document.getElementById("tabelMasuk").querySelector("tbody");
    const tabelKeluar = document.getElementById("tabelKeluar").querySelector("tbody");

    tampilkanDataAwal();

    formMasuk.addEventListener("submit", function (e) {
    e.preventDefault();
    const [nama, jumlah, supplier, tanggal, keterangan] = ambilNilaiInput(formMasuk);
    const data = { nama, jumlah: parseInt(jumlah), supplier, tanggal, keterangan };
    simpanKeStorage("barangMasuk", data);
    tambahBaris(tabelMasuk, [nama, jumlah, supplier, tanggal, keterangan], formMasuk);
  });

  formKeluar.addEventListener("submit", function (e) {
    e.preventDefault();
    const [nama, jumlah, tujuan, tanggal, keterangan] = ambilNilaiInput(formKeluar);
    const data = { nama, jumlah: parseInt(jumlah), tujuan, tanggal, keterangan };
    simpanKeStorage("barangKeluar", data);
    tambahBaris(tabelKeluar, [nama, jumlah, tujuan, tanggal, keterangan], formKeluar);
  });

    function ambilNilaiInput(form) {
      return Array.from(form.querySelectorAll("input")).map(i => i.value);
    }

    function tambahBaris(tbody, dataArr, form) {
      const tr = document.createElement("tr");
      tr.className = "border-t";
      tr.innerHTML = `
  ${dataArr.map(d => `<td class="p-2">${d}</td>`).join('')}
  <td class="p-2 space-x-2">
    <button onclick="editBaris(this)" class="text-pink-600 hover:underline">Edit</button>
    <button onclick="hapusBaris(this)" class="text-red-600 hover:underline">Hapus</button>
  </td>
`;
      tbody.appendChild(tr);
      form.reset();
    }

    function simpanKeStorage(key, dataBaru) {
      const data = JSON.parse(localStorage.getItem(key)) || [];
      data.push(dataBaru);
      localStorage.setItem(key, JSON.stringify(data));
    }

    function tampilkanDataAwal() {
      const masuk = JSON.parse(localStorage.getItem("barangMasuk")) || [];
      masuk.forEach(item => {
        tambahBaris(tabelMasuk, [item.nama, item.jumlah, item.supplier, item.tanggal], formMasuk);
      });

      const keluar = JSON.parse(localStorage.getItem("barangKeluar")) || [];
    keluar.forEach(item => {
      tambahBaris(tabelKeluar, [item.nama, item.jumlah, item.tujuan, item.tanggal, item.keterangan || ""], formKeluar);
    });
    }

    function hapusBaris(btn) {
      if (confirm("Yakin ingin menghapus entri ini?")) {
        const tr = btn.closest("tr");
        const td = tr.querySelectorAll("td");
        const isMasuk = tr.closest("tbody").parentElement.id === "tabelMasuk";
        const key = isMasuk ? "barangMasuk" : "barangKeluar";
        const nama = td[0].textContent;
        const tanggal = td[3].textContent;

        let data = JSON.parse(localStorage.getItem(key)) || [];
        data = data.filter(item => !(item.nama === nama && item.tanggal === tanggal));
        localStorage.setItem(key, JSON.stringify(data));
        tr.remove();
      }
    }

    function editBaris(btn) {
      const tr = btn.closest("tr");
      const td = tr.querySelectorAll("td");
      const isMasuk = tr.closest("tbody").parentElement.id === "tabelMasuk";
      const form = isMasuk ? formMasuk : formKeluar;
      const key = isMasuk ? "barangMasuk" : "barangKeluar";

      form.querySelectorAll("input").forEach((input, i) => {
        input.value = td[i].textContent;
      });

      const nama = td[0].textContent;
      const tanggal = td[3].textContent;
      let data = JSON.parse(localStorage.getItem(key)) || [];
      data = data.filter(item => !(item.nama === nama && item.tanggal === tanggal));
      localStorage.setItem(key, JSON.stringify(data));
      tr.remove();
      window.scrollTo({ top: form.offsetTop - 50, behavior: 'smooth' });
    }

    document.addEventListener("DOMContentLoaded", tampilkanDataAwal);
  </script>
</body>
</html>
