<?php
class User {
    protected int $iduser;
    protected string $nama;
    protected string $email;
    protected string $password;

    public function __construct(int $iduser = 0, string $nama = "", string $email = "", string $password = "", bool $isHashed = false) {
        $this->iduser = $iduser;
        $this->nama = $nama;
        $this->email = $email;
        // Jika password belum di-hash, hash dulu
        $this->password = $isHashed ? $password : password_hash($password, PASSWORD_DEFAULT);
    }

    public function set_data($iduser, $nama, $email,$password) {
        $this->iduser = $iduser;
        $this->nama = $nama;
        $this->email = $email;
        $this->password = $password;

    }

    // CREATE
    public function create($db): bool {
        $stmt = $db->prepare("INSERT INTO user (nama, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $this->nama, $this->email, $this->password);
        return $stmt->execute();
    }

    // READ
    public static function getById($db, int $iduser): ?User {
        $stmt = $db->prepare("SELECT * FROM user WHERE iduser=?");
        $stmt->bind_param("i", $iduser);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            // Password sudah di-hash di database
            return new User($row['iduser'], $row['nama'], $row['email'], $row['password'], true);
        }
        return null;
    }

    // UPDATE
    public function update($db): bool {
        $stmt = $db->prepare("UPDATE user SET nama=?, email=?, password=? WHERE iduser=?");
        $stmt->bind_param("sssi", $this->nama, $this->email, $this->password, $this->iduser);
        return $stmt->execute();
    }

    // DELETE
    public function delete($db): bool {
        $stmt = $db->prepare("DELETE FROM user WHERE iduser=?");
        $stmt->bind_param("i", $this->iduser);
        return $stmt->execute();
    }

    // READ ALL
    public static function getAll($db): array {
        $users = [];
        $result = $db->query("SELECT iduser, nama, email, password FROM user");
        while ($row = $result->fetch_assoc()) {
            $users[] = new User($row['iduser'], $row['nama'], $row['email'], $row['password'], true);
        }
        return $users;
    }

    // Login
    public static function login($db, $email, $password) {
        $stmt = $db->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                // Ambil role aktif
                $stmt_role = $db->prepare("SELECT * FROM role_user WHERE iduser = ? AND status = 1");
                $stmt_role->bind_param("i", $row['iduser']);
                $stmt_role->execute();
                $role_result = $stmt_role->get_result();
                if ($role_row = $role_result->fetch_assoc()) {
                    return [
                        'id' => $row['iduser'],
                        'nama' => $row['nama'],
                        'email' => $row['email'],
                        'role_aktif' => $role_row['idrole'],
                        'logged_in' => true
                    ];
                } else {
                    throw new Exception("Role tidak ditemukan atau tidak aktif.");
                }
            } else {
                throw new Exception("Password salah!");
            }
        } else {
            throw new Exception("Email tidak ditemukan");
        }
    }

    // Tampilkan info user
    public function getInfo() {
        return "Nama: $this->nama, Email: $this->email";
    }

    // Getter
    public function getIduser(): int { return $this->iduser; }
    public function getNama(): string { return $this->nama; }
    public function getEmail(): string { return $this->email; }
    public function getPassword(): string { return $this->password; }

    public function getUserByIdArray($db) {
        $stmt = $db->prepare("SELECT * FROM user WHERE iduser=?");
        $stmt->bind_param("i", $this->iduser);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            // Set data ke objek jika mau
            $this->set_data($row['iduser'], $row['nama'], $row['email'], $row['password']);
            return [
                'status' => true,
                'message' => 'User found',
                'data' => $row
            ];
        } else {
            return [
                'status' => false,
                'message' => 'User not found',
                'data' => []
            ];
        }
    }
}

class pemilik extends User {
    protected $idpemilik;
    protected $no_wa;
    protected $alamat;
    
    public function __construct($idpemilik = 0, $no_wa = "", $alamat = "", $iduser = 0, $nama = "", $email = "", $password = "", $isHashed = false) {
        parent::__construct($iduser, $nama, $email, $password, $isHashed);
        $this->idpemilik = $idpemilik;
        $this->no_wa = $no_wa;
        $this->alamat = $alamat;
    }

    // Methode overriding (Tampilkan info user + info pemilik)
    public function getInfo() {
        return parent::getInfo() . ", WA: $this->no_wa, Alamat: $this->alamat";
    }

    // Override set_data
    public function set_data($iduser, $nama, $email, $password, $idpemilik = null, $no_wa = null, $alamat = null) {
        parent::set_data($iduser, $nama, $email, $password);
        $this->idpemilik = $idpemilik;
        $this->no_wa = $no_wa;
        $this->alamat = $alamat;
    }
    
    // Override create: insert ke tabel user dan pemilik
    public function create($db): bool {
        // Buat user dulu
        $userCreated = parent::create($db);
        if ($userCreated) {
            // Ambil iduser terakhir
            $iduser = $db->insert_id;
            $stmt = $db->prepare("INSERT INTO pemilik (no_wa, alamat, iduser) VALUES (?, ?, ?)");
            $stmt->bind_param("ssi", $this->no_wa, $this->alamat, $iduser);
            $result = $stmt->execute();
            if ($result) {
                $this->iduser = $iduser;
                $this->idpemilik = $db->insert_id;
            }
            return $result;
        }
        return false;
    }

    // Override getUserByIdArray: ambil data user dan pemilik
    public function getUserByIdArray($db) {
        $stmt = $db->prepare("SELECT u.iduser, u.nama, u.email, u.password, p.idpemilik, p.no_wa, p.alamat 
                              FROM user u 
                              JOIN pemilik p ON u.iduser = p.iduser 
                              WHERE u.iduser=?");
        $stmt->bind_param("i", $this->iduser);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $this->set_data($row['idpemilik'], $row['no_wa'], $row['alamat'], $row['iduser'], $row['nama'], $row['email'], $row['password']);
            return [
                'status' => true,
                'message' => 'Pemilik found',
                'data' => $row
            ];
        } else {
            return [
                'status' => false,
                'message' => 'Pemilik not found',
                'data' => []
            ];
        }
    }

}
?>