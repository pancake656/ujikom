<?php


$conn = mysqli_connect("localhost", "root", "", "siak_upi");


// fungsi untuk menampilkan data dari database
function query($query){
    global $conn;


    $result = mysqli_query($conn, $query);
    $rows = [];
    while( $row = mysqli_fetch_assoc($result) ) {
        $rows[] = $row;
    }
    return $rows;
}


// fungsi untuk menambahkan data ke database
function tambah_data($data){
    global $conn;

    // var_dump($FILES);
    // die;

    $nim = $data['nim'];
    $nama = $data['nama'];
    $email = $data['email'];
    $jurusan = $data['jurusan'];
    // $gambar = $data['gambar'];

    // upload gambar
    $gambar = upload_gambar($nim, $nama);  // outputnya adalah nim_nama.eksentsi
    if( !$gambar ) {
        return false;
    }

    $query = "INSERT INTO mahasiswa (nim, nama, email, jurusan, gambar)
                  VALUES ('$nim', '$nama', '$email', '$jurusan', '$gambar')
                 ";
    mysqli_query($conn, $query);


    return mysqli_affected_rows($conn);    
}


// fungsi untuk menghapus data dari database
function hapus_data($id){
    global $conn;


    $query = "DELETE FROM mahasiswa WHERE id = $id";


    $result = mysqli_query($conn, $query);


    return mysqli_affected_rows($conn);    
}

// fungsi untuk mengubah data dari database
function ubah_data($data){
    global $conn;


    $id = $data['id'];
    $nim = $data['nim'];
    $nama = $data['nama'];
    $email = $data['email'];
    $jurusan = $data['jurusan'];
    // $gambar = $data['gambar'];

    // upload gambar
    $gambar = upload_gambar($nim, $nama);  // outputnya adalah nim_nama.eksentsi
    if( !$gambar ) {
        return false;
    }

    $query = "UPDATE mahasiswa SET
                nim = '$nim',
                nama = '$nama',
                email = '$email',
                jurusan = '$jurusan',
                gambar = '$gambar'
              WHERE id = $id
             ";


     $result = mysqli_query($conn, $query);
     
     return mysqli_affected_rows($conn);
}

// fungsi untuk mencari data
function search_data($keyword){
    global $conn;


    $query = "SELECT * FROM mahasiswa
              WHERE
              nama LIKE '%$keyword%' OR
              nim LIKE '%$keyword%' OR
              email LIKE '%$keyword%' OR
              jurusan LIKE '%$keyword%'
            ";
    return query($query);
}

// fungsi untuk upload gambar
function upload_gambar($nim, $nama) {


    // setting gambar
    $namaFile = $_FILES['gambar']['name'];
    $ukuranFile = $_FILES['gambar']['size'];
    $error = $_FILES['gambar']['error'];
    $tmpName = $_FILES['gambar']['tmp_name'];


    // cek apakah tidak ada gambar yang diupload
    if( $error === 4 ) {
        echo "<script>
                alert('pilih gambar terlebih dahulu!');
              </script>";
        return false;
    }


    // cek apakah yang diupload adalah gambar
    $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
    $ekstensiGambar = explode('.', $namaFile);
    $ekstensiGambar = strtolower(end($ekstensiGambar));
    if( !in_array($ekstensiGambar, $ekstensiGambarValid) ) {
        echo "<script>
                alert('yang anda upload bukan gambar!');
              </script>";
        return false;
    }


    // cek jika ukurannya terlalu besar
    // maks --> 5MB
    if( $ukuranFile > 5000000 ) {
        echo "<script>
                alert('ukuran gambar terlalu besar!');
              </script>";
        return false;
    }


    // lolos pengecekan, gambar siap diupload
    // generate nama gambar baru
    $namaFileBaru = $nim . "_" . $nama;
    $namaFileBaru .= '.';
    $namaFileBaru .= $ekstensiGambar;


    move_uploaded_file($tmpName, 'img/' . $namaFileBaru);


    return $namaFileBaru;
}

// fungsi untuk register
function register($data){
    global $conn;


    $username = strtolower($data['username']);
    $email = $data['email'];
    $password = mysqli_real_escape_string($conn, $data['password']);
    $konfirmasi_password = mysqli_real_escape_string($conn, $data['confirm_password']);


    // query untuk ngecek username yang diinputkan oleh user di database
    $query = mysqli_query($conn, "SELECT username FROM user WHERE username = '$username'");
    $result = mysqli_fetch_assoc($query);


    if($result != NULL){
        return "Username sudah terdaftar!";
    }


    if($password != $konfirmasi_password){
        return "Konfirmasi password tidak sesuai!";
    }


    // enkripsi password
    $password = password_hash($password, PASSWORD_DEFAULT);


    // tambahkan userbaru ke database
    mysqli_query($conn, "INSERT INTO user (username, email, password) VALUES('$username', '$email', '$password')");


    return true;
}

// fungsi untuk login
function login($data){
    global $conn;

    $username = $data['username'];
    $password = $data['password'];

    $query = "SELECT * FROM user WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) === 1){
        $row = mysqli_fetch_assoc($result);

        if(password_verify($password, $row['password'])){
            $_SESSION['login'] = true;
            $_SESSION['username'] = $row['username'];
            return true;
        } else {
            return "Password salah!";
        }

    } else {
        return "Username tidak terdaftar!";
    }
}



?>
