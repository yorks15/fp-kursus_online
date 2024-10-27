<?php 
include "service/database.php";
session_start();

$register_message = "";

// Redirect jika sudah login
if (isset($_SESSION["is_login"])) {
    header("location: dashboard.php");
    exit;
} 

// Proses registrasi
if (isset($_POST["register"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $class = $_POST["class"];
    $province = $_POST["province"];
    $regency = $_POST["regency"];
    $district = $_POST["district"];
    $address = $_POST["address"] ?? ''; // Tambahkan pemeriksaan agar tidak error jika belum ada nilai

    $hash_password = hash("sha256", $password); // Hash password

    // Query untuk memasukkan data
    $sql = "INSERT INTO users (username, password, email, phone, class, province, regency, district, address) VALUES ('$username', '$hash_password', '$email', '$phone', '$class', '$province', '$regency', '$district', '$address')";

    try {
        // Eksekusi query
        if (mysqli_query($db, $sql)) {
            $register_message = "Daftar akun berhasil, silahkan login";
        }
    } catch (mysqli_sql_exception $e) {
        // Menangani kesalahan duplikasi username
        if (mysqli_errno($db) == 1062) { // 1062 adalah kode error untuk duplikat entry
            $register_message = "Username sudah digunakan, silahkan ganti";
        } else {
            $register_message = "Daftar akun gagal, silahkan coba lagi. Error: " . $e->getMessage();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        .dropdown-container {
            position: relative;
        }
        .search-input {
            width: 100%;
            padding: 8px;
            margin-bottom: 5px;
            box-sizing: border-box;
        }
        select, input[type="text"], input[type="password"], input[type="email"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    <?php include "layout/header.html"; ?>
    
    <form method="post" action="register.php">
        <h2>Register</h2>

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="phone">No Handphone:</label>
        <input type="text" id="phone" name="phone" required>

        <label for="class">Class:</label>
        <input type="text" id="class" name="class" required>

        <!-- Dropdown Provinsi -->
        <div class="dropdown-container">
            <label for="province">Provinsi:</label>
            <select id="province" name="province" required>
                <option value="">Pilih Provinsi</option>
            </select>
        </div>

        <!-- Dropdown Kabupaten/Kota -->
        <div class="dropdown-container">
            <label for="regency">Kabupaten/Kota:</label>
            <select id="regency" name="regency" required disabled>
                <option value="">Pilih Kabupaten/Kota</option>
            </select>
        </div>

        <!-- Dropdown Kecamatan -->
        <div class="dropdown-container">
            <label for="district">Kecamatan:</label>
            <select id="district" name="district" required disabled>
                <option value="">Pilih Kecamatan</option>
            </select>
        </div>

        <!-- Alamat Lengkap -->
        <label for="address">Alamat Lengkap:</label>
        <input type="text" id="address" name="address" placeholder="Masukkan alamat lengkap Anda" required>

        <button type="submit" name="register">Register</button>
    </form>

    <script>
        // Base URL API
        const apiBase = 'https://www.emsifa.com/api-wilayah-indonesia/api';

        // Function to fetch data
        async function fetchData(endpoint) {
            const response = await fetch(`${apiBase}/${endpoint}.json`);
            return response.json();
        }

        // Load Provinsi
        async function loadProvinces() {
            const provinces = await fetchData('provinces');
            const provinceSelect = document.getElementById("province");

            provinces.forEach(province => {
                const option = new Option(province.name, province.id);
                provinceSelect.add(option);
            });
        }

        // Load Kabupaten/Kota based on selected Province
        async function loadRegencies(provinceId) {
            const regencies = await fetchData(`regencies/${provinceId}`);
            const regencySelect = document.getElementById("regency");

            // Clear current options
            regencySelect.innerHTML = "<option value=''>Pilih Kabupaten/Kota</option>";

            regencies.forEach(regency => {
                const option = new Option(regency.name, regency.id);
                regencySelect.add(option);
            });

            regencySelect.disabled = false;
        }

        // Load Kecamatan based on selected Kabupaten/Kota
        async function loadDistricts(regencyId) {
            const districts = await fetchData(`districts/${regencyId}`);
            const districtSelect = document.getElementById("district");

            // Clear current options
            districtSelect.innerHTML = "<option value=''>Pilih Kecamatan</option>";

            districts.forEach(district => {
                const option = new Option(district.name, district.id);
                districtSelect.add(option);
            });

            districtSelect.disabled = false;
        }

        // Event listeners
        document.getElementById("province").addEventListener("change", function() {
            const provinceId = this.value;
            if (provinceId) {
                loadRegencies(provinceId);
                document.getElementById("district").innerHTML = "<option value=''>Pilih Kecamatan</option>";
                document.getElementById("district").disabled = true;
            }
        });

        document.getElementById("regency").addEventListener("change", function() {
            const regencyId = this.value;
            if (regencyId) {
                loadDistricts(regencyId);
            }
        });

        // Initial load
        loadProvinces();
    </script>
</body>
</html>
