<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Stockify - Manajemen Pengguna</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
</head>
<body class="bg-pink-100 text-gray-800">
  <div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md p-6 hidden md:block">
      <h1 class="text-2xl font-bold mb-8 text-pink-600">📦 Stockify</h1>
      <nav class="space-y-4">
        <a href="{{ url('/') }}" class="block">Dashboard</a>
        <a href="{{ url('/produk') }}" class="block">Manajemen Produk</a>
        <a href="{{ url('/stok') }}" class="block">Manajemen Stok</a>
        <a href="{{ url('/users') }}" class="block font-semibold text-pink-600">Manajemen Pengguna</a>
        <a href="{{ url('/laporan') }}" class="block">Laporan</a>
        <a href="{{ url('/pengaturan') }}" class="block">Pengaturan</a>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6">
      <header class="mb-6">
        <h2 class="text-2xl font-semibold text-pink-700">Manajemen Pengguna</h2>
        <p class="text-sm text-gray-500">Kelola akun pengguna dan perannya</p>
      </header>

      <!-- Tambah Pengguna -->
      <section class="bg-white p-6 rounded-xl shadow-sm mb-6">
        <h3 class="text-lg font-semibold mb-4">Tambah Pengguna Baru</h3>
        <form id="userForm" class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <input type="text" placeholder="Nama Lengkap" class="border p-2 rounded" required />
          <input type="email" placeholder="Email" class="border p-2 rounded" required />
          <select class="border p-2 rounded" required>
            <option value="">Pilih Role</option>
            <option value="Admin">Admin</option>
            <option value="Manajer Gudang">Manajer Gudang</option>
            <option value="Staff Gudang">Staff Gudang</option>
          </select>
          <input type="password" placeholder="Password" class="border p-2 rounded" required />
          <div class="col-span-1 md:col-span-2">
            <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded">Simpan</button>
          </div>
        </form>
      </section>

      <!-- Daftar Pengguna -->
      <section class="bg-white p-6 rounded-xl shadow-sm">
        <h3 class="text-lg font-semibold mb-4">Daftar Pengguna</h3>
        <table class="min-w-full text-sm">
          <thead>
            <tr class="bg-gray-100 text-left">
              <th class="p-2">Nama</th>
              <th class="p-2">Email</th>
              <th class="p-2">Role</th>
              <th class="p-2">Aksi</th>
            </tr>
          </thead>
          <tbody id="userTable"></tbody>
        </table>
      </section>
    </main>
  </div>

  <script>
    function catatAktivitas(aksi, keterangan) {
      const aktivitas = JSON.parse(localStorage.getItem("aktivitas")) || [];
      const timestamp = new Date().toLocaleString("id-ID");
      aktivitas.unshift({ aksi, keterangan, waktu: timestamp });
      if (aktivitas.length > 50) aktivitas.pop();
      localStorage.setItem("aktivitas", JSON.stringify(aktivitas));
    }

    const userForm = document.getElementById("userForm");
    const userTable = document.getElementById("userTable");
    let users = JSON.parse(localStorage.getItem("users")) || [];
    let editingIndex = null;

    function renderTable() {
      userTable.innerHTML = "";
      users.forEach((user, index) => {
        const row = document.createElement("tr");
        row.className = "border-t";
        row.innerHTML = `
          <td class="p-2">${user.nama}</td>
          <td class="p-2">${user.email}</td>
          <td class="p-2">${user.role}</td>
          <td class="p-2 space-x-2">
            <button onclick="editUser(${index})" class="text-pink-600 hover:underline">Edit</button>
            <button onclick="deleteUser(${index})" class="text-red-600 hover:underline">Hapus</button>
          </td>
        `;
        userTable.appendChild(row);
      });
    }

    function saveToLocalStorage() {
      localStorage.setItem("users", JSON.stringify(users));
    }

    userForm.addEventListener("submit", function (e) {
      e.preventDefault();
      const inputs = userForm.querySelectorAll("input, select");
      const nama = inputs[0].value;
      const email = inputs[1].value;
      const role = inputs[2].value;
      const password = inputs[3].value;

      const userData = { nama, email, role };

      if (editingIndex !== null) {
        const userLama = users[editingIndex];
        users[editingIndex] = userData;
        catatAktivitas("Edit Pengguna", `Mengubah data pengguna: ${userLama.nama} → ${nama}`);
        editingIndex = null;
      } else {
        users.push(userData);
        catatAktivitas("Tambah Pengguna", `Menambahkan pengguna baru: ${nama} (${role})`);
      }

      saveToLocalStorage();
      renderTable();
      userForm.reset();
    });

    function editUser(index) {
      const user = users[index];
      const inputs = userForm.querySelectorAll("input, select");
      inputs[0].value = user.nama;
      inputs[1].value = user.email;
      inputs[2].value = user.role;
      inputs[3].value = "";
      editingIndex = index;
      window.scrollTo({ top: 0, behavior: "smooth" });
    }

    function deleteUser(index) {
      if (confirm("Yakin ingin menghapus pengguna ini?")) {
        const nama = users[index].nama;
        users.splice(index, 1);
        saveToLocalStorage();
        renderTable();
        catatAktivitas("Hapus Pengguna", `Menghapus pengguna: ${nama}`);
      }
    }

    renderTable();
  </script>
</body>
</html>
