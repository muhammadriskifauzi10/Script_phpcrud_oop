<?php
require 'connect.php';

$no = 1;
$output = [];
foreach($db->get_data('students') as $row) {
  $action = '
  <div class="d-flex align-items-center justify-content-center gap-1">
        <button type="button" class="btn btn-info text-light" style="width: 80px;" data-bs-toggle="modal" data-bs-target="#universalModal" onclick="universalModal(`detail`, ' . $row['id'] . ')">Detail</button>
        |
        <button type="button" class="btn btn-warning text-light" style="width: 80px;" data-bs-toggle="modal" data-bs-target="#universalModal" onclick="universalModal(`edit`, ' . $row['id'] . ')">Edit</button>
        |
        <button type="button" class="btn btn-danger text-light" style="width: 80px;" data-bs-toggle="modal" data-bs-target="#universalModal" onclick="universalModal(`delete`, ' . $row['id'] . ')">Hapus</button>
  </div>';

  $birthday = new DateTime($row['tanggal_lahir']);
  $today = new DateTime();
  $interval = $today->diff($birthday);

  $jurusan = $db->where_table1('majors', $row['id_major']);
  $program = $db->where_table1('programs', $row['id_program']);
  $output[] = array(
    'nomor' => '<strong>' . $no++ . '</strong>',
    'tanggal_lahir' => $interval->format('%d') . ' Hari ' . $interval->format('%m') . ' Bulan ' . $interval->format('%y') . ' Tahun ',
    'name' => $row['name'],
    'nirm' => $row['nirm'],
    // 'jenis_kelamin' => $row['jenis_kelamin'],
    // 'jurusan' => $jurusan,
    // 'program' => $program,
    'action' => $action,
  );
}

echo json_encode(['data' => $output]);
?>