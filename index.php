<?php
require 'connect.php';

if (isset($_POST['value'])) {

  if ($_POST['value'] == 'detail') {
    $message = 'success';
    $student = [];
    foreach ($db->get_data_id('students', $_POST['valuemodal']) as $row) {

      $birthday = new DateTime($row['tanggal_lahir']);
      $today = new DateTime();
      $interval = $today->diff($birthday);

      $jurusan = $db->where_table1('majors', $row['id_major']);
      $program = $db->where_table1('programs', $row['id_program']);

      $student[] = '
      <tr>
        <td>' . $interval->format('%d') . ' Hari ' . $interval->format('%m') . ' Bulan ' . $interval->format('%y') . ' Tahun ' . '</td>
        <td>' . $row['name'] . '</td>
        <td>' . $row['nirm'] . '</td>
        <td>' . $row['jenis_kelamin'] . '</td>
        <td>' . $jurusan . '</td>
        <td>' . $program . '</td>
      </tr>
      ';
    }
    $data = '
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="universalModalLabel">Detail Data</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">Tanggal Lahir</th>
              <th scope="col">Nama</th>
              <th scope="col">Nirm</th>
              <th scope="col">Jenis Kelamin</th>
              <th scope="col">Jurusan</th>
              <th scope="col">Program</th>
            </tr>
          </thead>
          <tbody>
            ' . implode(" ", $student) . '
          </tbody>
        </table>
      </div>
    </div>
    ';
  }
  else if ($_POST['value'] == 'edit') {
    $message = 'success';
    $studentinputform = [];
    foreach ($db->get_data_id('students', $_POST['valuemodal']) as $row) {
      $birthday = new DateTime($row['tanggal_lahir']);
      $today = new DateTime();
      $interval = $today->diff($birthday);

      $jurusan = $db->where_table1('majors', $row['id_major']);
      $program = $db->where_table1('programs', $row['id_program']);
    }


    $data_jenis_kelamin = [
      "data1" => [
        "id" => 1,
        "jenis_kelamin" => "Laki-Laki",
      ],
      "data2" => [
        "id" => 2,
        "jenis_kelamin" => "Perempuan",
      ]
    ];

    $select_jenis_kelamin = [];
    for ($i = 1; $i <= count($data_jenis_kelamin); $i++) {

      if ($data_jenis_kelamin['data' . $i . '']['jenis_kelamin'] == $row['jenis_kelamin']) {
        $selected = 'selected';
      } else {
        $selected = '';
      }
      $select_jenis_kelamin[] = '
        <option value="' . $data_jenis_kelamin['data' . $i . '']['jenis_kelamin'] . '" ' . $selected . '>' . $data_jenis_kelamin['data' . $i . '']['jenis_kelamin'] . '</option>
      ';
    }

    $select_jurusan = [];
    foreach ($db->get_data('majors') as $row_jurusan) {
      if ($row_jurusan['id'] == $row['id_major']) {
        $selected = 'selected';
      } else {
        $selected = '';
      }
      $select_jurusan[] = '<option value="' . $row_jurusan['id'] . '" ' . $selected . '>' . $row_jurusan['name'] . '</option>';
    }

    $select_program = [];
    foreach ($db->get_data('programs') as $row_program) {
      if ($row_program['id'] == $row['id_program']) {
        $selected = 'selected';
      } else {
        $selected = '';
      }

      $select_program[] = '<option value="' . $row_program['id'] . '" ' . $selected . '>' . $row_program['name'] . '</option>';
    }

    $data = '
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="universalModalLabel">Edit Data</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <tbody>
          <form id="formedit" onsubmit="formedit(event, ' . $_POST['valuemodal'] . ')">
            <div class="mb-2">
              <label class="form-label">Nama</label>
              <input type="text" class="form-control" name="name" placeholder="Nama Mahasiswa" value="' . $row['name'] . '">
              <span class="text-danger" id="name"></span>
            </div>
            <div class="mb-2">
              <label class="form-label">Tanggal Lahir</label>
              <input type="date" class="form-control" name="tanggal_lahir" value="' . $row['tanggal_lahir'] . '">
              <span class="text-danger" id="tanggal_lahir"></span>
            </div>
            <div class="mb-2">
              <label class="form-label">Jenis Kelamin</label>
              <select class="form-select" name="jenis_kelamin">
                <option value="">-- Pilih Jenis Kelamin --</option>
                ' . implode(" ", $select_jenis_kelamin) . '
              </select>
              <span class="text-danger" id="jenis_kelamin"></span>
            </div>
            <div class="mb-2">
              <label class="form-label">Jurusan</label>
              <select class="form-select" name="jurusan">
                <option value="">-- Pilih Jurusan --</option>
                ' . implode(" ", $select_jurusan) . '
              </select>
              <span class="text-danger" id="jurusan"></span>
            </div>
            <div class="mb-2">
              <label class="form-label">Program</label>
              <select class="form-select" name="program">
                <option value="">-- Pilih Program --</option>
                ' . implode(" ", $select_program) . '
              </select>
              <span class="text-danger" id="program"></span>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Update!</button>
            </div>
          </form>
        </tbody>
      </div>
    </div>
    ';
  } 
  elseif ($_POST['value'] == 'delete') {
    $message = 'success';
    $data = '
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="universalModalLabel">Hapus Data</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Yakin, mau hapus data?</p>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="delete_data(' . $_POST['valuemodal'] . ')">Ya, hapus!</button>
        </div>
      </div>
    </div>
    ';
  }
  echo json_encode([
    'success' => true,
    'message' => $message,
    'data' => $data
  ]);

  exit;
}

if (isset($_POST['valuemodaldelete'])) {
  $message = 'Data berhasil di hapus!';
  $db->get_data_id_delete('students', $_POST['valuemodaldelete']);
  echo json_encode([
    'success' => true,
    'message' => $message
  ]);

  exit;
}

if (isset($_POST['valuemodal'])) {
  parse_str($_POST['data'], $params);

  $name = htmlspecialchars($params['name']);
  $tanggal_lahir = htmlspecialchars($params['tanggal_lahir']);
  $jenis_kelamin = $params['jenis_kelamin'];
  $jurusan = $params['jurusan'];
  $program = $params['program'];

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
      $db->update_data_student($tanggal_lahir, $name, $jenis_kelamin, $jurusan, $program, $_POST['valuemodal']);
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

require 'templates/head.php';
?>

<body>
  <div class="container">
    <div class="row mt-4">
      <div class="col-lg-6 m-auto">
        <nav aria-label="breadcrumb mb-4">
          <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Dasbor</li>
          </ol>
        </nav>
        <div class="mb-4">
          <a href="tambahdata.php" class="btn btn-primary">Tambah Data</a>
        </div>
        <table id="data-table" class="table" style="width: 100%;">
          <thead>
            <tr>
              <th scope="col">No</th>
              <th scope="col">Umur</th>
              <th scope="col">Nama</th>
              <th scope="col">Nirm</th>
              <!-- <th scope="col">Jenis Kelamin</th>
              <th scope="col">Jurusan</th>
              <th scope="col">Program</th> -->
              <th scope="col">Aksi</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <?php include 'templates/footer.php'; ?>

  <script>
    var table
    $(document).ready(function() {
      table = $('#data-table').DataTable({
        "processing": true,
        "ajax": {
          url: "data-students.php",
          // type: "get",
          // data: {
          //   filter_data: function() {
          //     return $("#filter-data").serialize()
          //   }
          // }
        },
        "columns": [{
            "data": "nomor"
          },
          {
            "data": "tanggal_lahir"
          },
          {
            "data": "name"
          },
          {
            "data": "nirm"
          },
          // {
          //   "data": "jenis_kelamin"
          // },
          // {
          //   "data": "jurusan"
          // },
          // {
          //   "data": "program"
          // },
          {
            "data": "action"
          },
        ],
        // "order": [
        //     [1, 'asc']
        // ],
        // scrollY:        "700px",
        scrollX: true,
        // scrollCollapse: true,
        // paging:         false,
        // fixedColumns:   {
        //     left: 3,
        // }
      });
    })

    function universalModal(value, id) {
      $('#modal-dialog').empty()

      if (value === "detail") {
        $.ajax({
          url: '',
          type: 'POST',
          data: {
            value: value,
            valuemodal: id
          },
          success: function(response) {
            const responseParse = JSON.parse(response)

            if (responseParse.success) {
              $('#modal-dialog').addClass('modal-lg')
              $('#modal-dialog').append(responseParse.data)
            }
          }
        })
      } else if (value === "edit") {
        $.ajax({
          url: '',
          type: 'POST',
          data: {
            value: value,
            valuemodal: id
          },
          success: function(response) {
            const responseParse = JSON.parse(response)

            if (responseParse.success) {
              $('#modal-dialog').addClass('modal-lg')
              $('#modal-dialog').append(responseParse.data)
            }
          }
        })
      } else if (value === "delete") {
        $.ajax({
          url: '',
          type: 'POST',
          data: {
            value: value,
            valuemodal: id
          },
          success: function(response) {
            const responseParse = JSON.parse(response)

            if (responseParse.success) {
              $('#modal-dialog').append(responseParse.data)
            }
          }
        })
      } else {
        $('#modal-dialog').append(`
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="universalModalLabel">Detail Data</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p>Opps, maaf data tidak ditemukan!</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary">Save changes</button>
            </div>
          </div>
        `)
      }
    }

    function delete_data(id) {
      $.ajax({
        url: '',
        type: 'POST',
        data: {
          valuemodaldelete: id
        },
        success: function(response) {
          const responseParse = JSON.parse(response)

          if (responseParse.success) {
            Toastify({

              text: responseParse.message,

              duration: 3000,

              style: {
                background: "#96c93d",
              },

            }).showToast();

            $('#universalModal').modal('hide')

            table.ajax.reload()
          }
        }
      })
    }

    function formedit(e, id) {
      e.preventDefault()

      $.ajax({
        url: '',
        type: 'POST',
        data: {
          valuemodal: id,
          data: $("#formedit").serialize(),
        },
        success: function(response) {
          const responseParse = JSON.parse(response)

          if (responseParse.success) {
            Toastify({

              text: "Data Berhasil Di Update!",

              duration: 3000,

              style: {
                background: "#96c93d",
              },

            }).showToast();

            table.ajax.reload()
            $('#universalModal').modal('hide')
          }

          $("#name").text(responseParse.errors['name'])
          $("#tanggal_lahir").text(responseParse.errors['tanggal_lahir'])
          $("#jenis_kelamin").text(responseParse.errors['jenis_kelamin'])
          $("#jurusan").text(responseParse.errors['jurusan'])
          $("#program").text(responseParse.errors['program'])
        }
      })
    }
  </script>
</body>

</html>