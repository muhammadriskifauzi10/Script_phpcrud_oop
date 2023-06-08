<?php
class database
{
  public $host;
  public $uname;
  public $pass;
  public $db;

  public $conn;
  public $table;

  public function __construct($host, $uname, $pass, $db)
  {
    $this->host = $host;
    $this->uname = $uname;
    $this->pass = $pass;
    $this->db = $db;
  }

  public function connect() {
    $this->conn = new mysqli($this->host, $this->uname, $this->pass, $this->db);
  }

  public function get_data($table) {
    $this->table = $table;
    $sql = 'SELECT * FROM ' . $this->table;
    $query = $this->conn->query($sql);

    if ($query->num_rows > 0) {
      while ($row = $query->fetch_assoc()) {
        $result[] = $row;
      }
      return $result;
    } else {
      return [];
    }
  }

  public function get_data_id($table, $id) {
    $this->table = $table;
    $sql = 'SELECT * FROM ' . $this->table . ' WHERE id =' . $id;
    $query = $this->conn->query($sql);

    if ($query->num_rows > 0) {
      while ($row = $query->fetch_assoc()) {
        $result[] = $row;
      }
    } else {
      return 0;
    }

    return $result;
  }

  public function get_data_id_delete($table, $id) {
    $this->table = $table;
    $sql = 'DELETE FROM ' . $this->table . ' WHERE id =' . $id;
    $this->conn->query($sql); 
  }

  public function get_count_table($table) {
    $this->table = $table;
    $sql = "SELECT * FROM " . $this->table;
    $query = $this->conn->query($sql);

    if ($query->num_rows > 0) {
      while ($row = $query->fetch_assoc()) {
        $result[] = $row;
      }
      
      return count($result);
    }
    else {
      return 0;
    }
  }

  public function insert_data_student($tanggal_lahir, $name, $nirm, $jenis_kelamin, $jurusan, $program) {
    $sql = "INSERT INTO students (tanggal_lahir, name, nirm, jenis_kelamin, id_major, id_program) 
            VALUES ('$tanggal_lahir', '$name', '$nirm', '$jenis_kelamin', '$jurusan', '$program')";
    $this->conn->query($sql);
  }

  public function update_data_student($tanggal_lahir, $name, $jenis_kelamin, $jurusan, $program, $id) {
    $sql = "UPDATE students SET tanggal_lahir='$tanggal_lahir', 
    name='$name', 
    jenis_kelamin='$jenis_kelamin', 
    id_major='$jurusan', 
    id_program='$program' 
    WHERE id='$id'";
    $this->conn->query($sql);
  }

  // public function join_table() {
  //   $sql = "SELECT majors.name from students INNER JOIN majors ON students.id_major = majors.id";
  //   $query = $this->conn->query($sql);
  //   if ($query->num_rows > 0) {
  //     while ($row = $query->fetch_assoc()) {
  //       $result[] = $row;
  //     }
  //     return $result;
  //   }
  // }
  public function where_table1($table, $id) {
    $this->table = $table;
    $sql = "SELECT name from $this->table where id = $id";
    $query = $this->conn->query($sql);

    return $query->fetch_column();
  }
}

$db = new database('localhost', 'root', '', 'campus');
$db->connect();
?>
