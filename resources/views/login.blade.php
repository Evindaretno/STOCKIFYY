<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login - Stockify</title>
  <link href="https://fonts.googleapis.com/css2?family=Comic+Neue:wght@700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      font-family: 'Comic Neue', cursive;
    }
  </style>
</head>
<body class="bg-pink-100 flex items-center justify-center min-h-screen">
  <div class="bg-white rounded-2xl shadow-lg p-8 w-full max-w-md text-center">
    <div class="text-5xl mb-4">🔐</div>
    <h1 class="text-2xl font-bold text-pink-600 mb-2">Selamat Datang Kembali!</h1>
    <p class="text-gray-600 mb-6">Yuk login dulu ke Stockify~</p>

    <form id="loginForm">
      <input type="text" id="username" placeholder="Nama Pengguna" class="w-full p-3 mb-4 border border-gray-300 rounded-lg" required>
      <input type="password" id="password" placeholder="Kata Sandi" class="w-full p-3 mb-4 border border-gray-300 rounded-lg" required>
      <button type="submit" class="w-full bg-pink-600 text-white py-3 rounded-lg hover:bg-pink-700 transition">Login</button>
    </form>

    <p class="text-sm text-gray-500 mt-4">
      Belum punya akun?
      <a href="{{ url('/register') }}" class="text-pink-600 font-semibold hover:underline">Daftar yuk!</a>
    </p>
  </div>

  <script>
    document.getElementById("loginForm").addEventListener("submit", function (e) {
      e.preventDefault();

      const username = document.getElementById("username").value.trim();
      const password = document.getElementById("password").value.trim();

      if (!username || !password) {
        alert("Harap isi nama pengguna dan kata sandi.");
        return;
      }

      // Simpan info login ke localStorage
      localStorage.setItem("userLogin", "true");
      localStorage.setItem("userName", username);
      localStorage.setItem("userRole", "Admin"); // default role, bisa diganti dinamis nanti

      // Redirect ke dashboard
      window.location.href = "{{ url('/') }}";
    });
  </script>
</body>
</html>
