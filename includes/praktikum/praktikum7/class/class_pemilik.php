<?php
class Pemilik {
    private $db;
    private $idpemilik;
    private $no_wa;
    private $alamat;
    private $iduser;
    private $nama;
    private $email;
    private $password;
    
    // Constructor untuk database connection
    public function __construct($dbconn, $idpemilik = 0, $no_wa = '', $alamat = '', $iduser = 0, $nama = '', $email = '', $password = '') {
        $this->db = $dbconn;
        $this->idpemilik = $idpemilik;
        $this->no_wa = $no_wa;
        $this->alamat = $alamat;
        $this->iduser = $iduser;
        $this->nama = $nama;
        $this->email = $email;
        $this->password = $password;
    }

    // Method untuk registrasi pemilik baru (dengan user baru)
    public function createWithUser() {
        try {
            // Mulai transaction
            $this->db->autocommit(false);
            
            // 1. Insert ke tabel user dulu
            $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);
            $stmt_user = $this->db->prepare('INSERT INTO user (nama, email, password) VALUES (?, ?, ?)');
            $stmt_user->bind_param('sss', $this->nama, $this->email, $hashed_password);
            
            if (!$stmt_user->execute()) {
                throw new Exception('Gagal menyimpan data user');
            }
            
            // Ambil ID user yang baru dibuat
            $new_user_id = $this->db->insert_id;
            $stmt_user->close();
            
            // 2. Insert ke tabel pemilik
            $stmt_pemilik = $this->db->prepare('INSERT INTO pemilik (iduser, no_wa, alamat) VALUES (?, ?, ?)');
            $stmt_pemilik->bind_param('iss', $new_user_id, $this->no_wa, $this->alamat);
            
            if (!$stmt_pemilik->execute()) {
                throw new Exception('Gagal menyimpan data pemilik');
            }
            
            $stmt_pemilik->close();
            
            // Commit transaction
            $this->db->commit();
            $this->db->autocommit(true);
            
            return ['status' => 'success', 'message' => 'Registrasi pemilik berhasil!'];
            
        } catch (Exception $e) {
            // Rollback jika ada error
            $this->db->rollback();
            $this->db->autocommit(true);
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    // Method untuk tambah pemilik dengan user yang sudah ada
    public function create($data) {
        if (!is_array($data)) {
            return ['status' => 'error', 'message' => 'Data harus berupa array'];
        }
        
        $iduser = intval($data['iduser']);
        $no_wa = trim($data['no_wa']);
        $alamat = trim($data['alamat']);
        
        if ($iduser && $no_wa !== '' && $alamat !== '') {
            $stmt = $this->db->prepare('INSERT INTO pemilik (iduser, no_wa, alamat) VALUES (?, ?, ?)');
            $stmt->bind_param('iss', $iduser, $no_wa, $alamat);
            $result = $stmt->execute();
            $stmt->close();
            
            if ($result) {
                return ['status' => 'success', 'message' => 'Data berhasil ditambahkan'];
            } else {
                return ['status' => 'error', 'message' => 'Gagal menambahkan data'];
            }
        }
        return ['status' => 'error', 'message' => 'Data tidak valid'];
    }

    public function update($data) {
        if (!is_array($data)) {
            return ['status' => 'error', 'message' => 'Data harus berupa array'];
        }
        
        $id = intval($data['idpemilik']);
        $iduser = intval($data['iduser']);
        $no_wa = trim($data['no_wa']);
        $alamat = trim($data['alamat']);
        
        if ($id && $iduser && $no_wa !== '' && $alamat !== '') {
            $stmt = $this->db->prepare('UPDATE pemilik SET iduser=?, no_wa=?, alamat=? WHERE idpemilik=?');
            $stmt->bind_param('issi', $iduser, $no_wa, $alamat, $id);
            $result = $stmt->execute();
            $stmt->close();
            
            if ($result) {
                return ['status' => 'success', 'message' => 'Data berhasil diupdate'];
            } else {
                return ['status' => 'error', 'message' => 'Gagal mengupdate data'];
            }
        }
        return ['status' => 'error', 'message' => 'Data tidak valid'];
    }

    public function delete($id) {
        $id = intval($id);
        if ($id) {
            $stmt = $this->db->prepare('DELETE FROM pemilik WHERE idpemilik=?');
            $stmt->bind_param('i', $id);
            $result = $stmt->execute();
            $stmt->close();
            
            if ($result) {
                return ['status' => 'success', 'message' => 'Data berhasil dihapus'];
            } else {
                return ['status' => 'error', 'message' => 'Gagal menghapus data'];
            }
        }
        return ['status' => 'error', 'message' => 'ID tidak valid'];
    }

    public function getById($id) {
        $id = intval($id);
        $stmt = $this->db->prepare('SELECT * FROM pemilik WHERE idpemilik=?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();
        return is_array($data) ? $data : null;
    }

    public function getAllWithUser() {
        $query = 'SELECT p.idpemilik, u.nama, u.email, p.no_wa, p.alamat, p.iduser FROM pemilik p LEFT JOIN user u ON p.iduser = u.iduser';
        $result = $this->db->query($query);
        if ($result) {
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return [
                'status' => 'success',
                'data' => $data
            ];
        } else {
            return [
                'status' => 'error',
                'message' => $this->db->error
            ];
        }
    }

    // Method untuk cek email sudah ada atau belum
    public function checkEmailExists($email) {
        $stmt = $this->db->prepare("SELECT iduser FROM user WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }
}
?>