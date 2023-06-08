<?php
require 'connect.php';

if (isset($_POST['name']) && isset($_POST['tanggal_lahir']) && isset($_POST['jenis_kelamin']) && isset($_POST['jurusan']) && isset($_POST['program'])) {
  $name = htmlspecialchars($_POST['name']);
  $tanggal_lahir = htmlspecialchars($_POST['tanggal_lahir']);
  $jenis_kelamin = $_POST['jenis_kelamin'];
  $jurusan = $_POST['jurusan'];
  $program = $_POST['program'];

  $errors = [];
  if (empty($name)) {
    $errors['name'] = "Nama Wajib Diisi";
  } else {
    $errors['name'] = '';
  }

  if (empty($tanggal_lahir)) {
    $errors['tanggal_lahir'] = "Tanggal Lahir Wajib Diisi";
  } else {
    $errors['tanggal_lahir'] = '';
  }

  if ($jenis_kelamin != "Laki-Laki" && $jenis_kelamin != "Perempuan") {
    $errors['jenis_kelamin'] = "Jenis Kelamin Wajib Diisi";
  } else {
    $errors['jenis_kelamin'] = '';
  }

  if (!is_numeric($jurusan)) {
    $errors['jurusan'] = "Jurusan Wajib Diisi";
  } else {
    $errors['jurusan'] = '';
  }

  if (!is_numeric($program)) {
    $errors['program'] = "Program Wajib Diisi";
  } else {
    $errors['program'] = '';
  }

  try {
    if ($errors['name'] == '' && $errors['tanggal_lahir'] == '' && $errors['jenis_kelamin'] == '' && $errors['jurusan'] == '' && $errors['program'] == '') {
      $db->insert_data_student($tanggal_lahir, $name, date('Y') . ($db->get_count_table('students') + 1), $jenis_kelamin, $jurusan, $program);
      $data = "Masuk";
      $success = true;

    } else {
      $data = "Gak Masuk";
      $success = false;
    }
  } catch (Exception $e) {
    $data = $e->getMessage();
  }

  echo json_encode([
    'success' => $success,
    'message' => 'success',
    'errors' => $errors,
    'datas' => $data
  ]);
  exit;
}

function is_numeric_input($data, $key = "", $value = "")
{
  if (is_numeric($data)) {
    $error = '';
  } else {
    $error = $key . " " . $value;
  }
  return $error;
}

require 'templates/head.php';
?>

<body>
  <div class="container">
    <div class="row mt-4">
      <div class="col-lg-6 m-auto">
        <nav aria-label="breadcrumb mb-4">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a href="/campus">Dasbor</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Data</li>
          </ol>
        </nav>
        <form action="" id="addData">
          <div class="mb-2">
            <label class="form-label">Nama</label>
            <input type="text" class="form-control" name="name" placeholder="Nama Mahasiswa" autofocus>
            <span class="text-danger" id="name"></span>
          </div>
          <div class="mb-2">
            <label class="form-label">Tanggal Lahir</label>
            <input type="date" class="form-control" name="tanggal_lahir">
            <span class="text-danger" id="tanggal_lahir"></span>
          </div>
          <div class="mb-2">
            <label class="form-label">Jenis Kelamin</label>
            <select class="form-select" name="jenis_kelamin">
              <option value="">-- Pilih Jenis Kelamin --</option>
              <option value="Laki-Laki">Laki-Laki</option>
              <option value="Perempuan">Perempuan</option>
            </select>
            <span class="text-danger" id="jenis_kelamin"></span>
          </div>
          <div class="mb-2">
            <label class="form-label">Jurusan</label>
            <select class="form-select" name="jurusan">
              <option value="">-- Pilih Jurusan --</option>
              <?php foreach ($db->get_data('majors') as $row) : ?>
                <option value="<?= $row['id']; ?>"><?= $row['name']; ?></option>
              <?php endforeach; ?>
            </select>
            <span class="text-danger" id="jurusan"></span>
          </div>
          <div class="mb-2">
            <label class="form-label">Program</label>
            <select class="form-select" name="program">
              <option value="">-- Pilih Program --</option>
              <?php foreach ($db->get_data('programs') as $row) : ?>
                <option value="<?= $row['id']; ?>"><?= $row['name']; ?></option>
              <?php endforeach; ?>
            </select>
            <span class="text-danger" id="program"></span>
          </div>
          <div class="mb-2">
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <?php include 'templates/footer.php'; ?>

  <script>
    $(document).ready(function() {
      $('#addData').on('submit', function(e) {
        e.preventDefault()

        $.ajax({
          url: "",
          method: "POST",
          data: $("#addData").serialize(),
          success: function(response) {
            const responseParse = JSON.parse(response)

            if (responseParse.success) {
              Toastify({

                text: "Data Berhasil Ditambahkan!",

                duration: 3000,

                style: {
                  background: "#96c93d",
                },

              }).showToast();

              $("input[name='name']").val("")
              $("input[name='tanggal_lahir']").val("")
              $("select[name='jenis_kelamin']").val("")
              $("select[name='jurusan']").val("")
              $("select[name='program']").val("")
            }

            $("#name").text(responseParse.errors['name'])
            $("#tanggal_lahir").text(responseParse.errors['tanggal_lahir'])
            $("#jenis_kelamin").text(responseParse.errors['jenis_kelamin'])
            $("#jurusan").text(responseParse.errors['jurusan'])
            $("#program").text(responseParse.errors['program'])
          }
        })
      })
    })
  </script>
</body>

</html>