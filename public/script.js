let stokBarang = JSON.parse(localStorage.getItem('stokBarang')) || [];

function tampilkanStok() {
  const tbody = document.getElementById('stok-body');
  tbody.innerHTML = '';

  stokBarang.forEach((barang, index) => {
    const tr = document.createElement('tr');

    tr.innerHTML = `
      <td>${index + 1}</td>
      <td>${barang.nama}</td>
      <td>${barang.kode}</td>
      <td>${barang.jumlah}</td>
      <td class="aksi">
        <button onclick="barangMasuk(${index})">+ Masuk</button>
        <button onclick="barangKeluar(${index})">– Keluar</button>
        <button onclick="hapusBarang(${index})" class="hapus">Hapus</button>
      </td>
    `;

    tbody.appendChild(tr);
  });
}

function tambahBarang() {
  const nama = document.getElementById('nama').value.trim();
  const kode = document.getElementById('kode').value.trim();
  const jumlah = parseInt(document.getElementById('jumlah').value);

  if (nama && kode && !isNaN(jumlah) && jumlah >= 0) {
    stokBarang.push({ nama, kode, jumlah });
    localStorage.setItem('stokBarang', JSON.stringify(stokBarang));
    tampilkanStok();
    document.getElementById('form-barang').reset();
  } else {
    alert("Mohon isi data dengan benar.");
  }
}

function barangMasuk(index) {
  const jumlah = parseInt(prompt("Masukkan jumlah barang masuk:", "1"));
  if (!isNaN(jumlah) && jumlah > 0) {
    stokBarang[index].jumlah += jumlah;
    localStorage.setItem('stokBarang', JSON.stringify(stokBarang));
    tampilkanStok();
  } else {
    alert("Jumlah tidak valid.");
  }
}

function barangKeluar(index) {
  const jumlah = parseInt(prompt("Masukkan jumlah barang keluar:", "1"));
  if (!isNaN(jumlah) && jumlah > 0) {
    if (stokBarang[index].jumlah >= jumlah) {
      stokBarang[index].jumlah -= jumlah;
      localStorage.setItem('stokBarang', JSON.stringify(stokBarang));
      tampilkanStok();
    } else {
      alert("Jumlah keluar melebihi stok tersedia!");
    }
  } else {
    alert("Jumlah tidak valid.");
  }
}

function hapusBarang(index) {
  if (confirm("Yakin mau hapus barang ini?")) {
    stokBarang.splice(index, 1);
    localStorage.setItem('stokBarang', JSON.stringify(stokBarang));
    tampilkanStok();
  }
}

document.addEventListener('DOMContentLoaded', tampilkanStok);
