<?php
class Role {
    private int $idrole;
    private string $nama_role;

    public function __construct(int $idrole = 0, string $nama_role = "") {
        $this->idrole = $idrole;
        $this->nama_role = $nama_role;
    }

    // CREATE
    public function create($db): bool {
        $stmt = $db->prepare("INSERT INTO role (nama_role) VALUES (?)");
        $stmt->bind_param("s", $this->nama_role);
        return $stmt->execute();
    }

    // READ
    public static function getById($db, int $idrole): ?Role {
        $stmt = $db->prepare("SELECT * FROM role WHERE idrole=?");
        $stmt->bind_param("i", $idrole);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return new Role($row['idrole'], $row['nama_role']);
        }
        return null;
    }

    // UPDATE
    public function update($db): bool {
        $stmt = $db->prepare("UPDATE role SET nama_role=? WHERE idrole=?");
        $stmt->bind_param("si", $this->nama_role, $this->idrole);
        return $stmt->execute();
    }

    // DELETE
    public function delete($db): bool {
        $stmt = $db->prepare("DELETE FROM role WHERE idrole=?");
        $stmt->bind_param("i", $this->idrole);
        return $stmt->execute();
    }

    public function getIdrole(): int {
        return $this->idrole;
    }
    public function getNamaRole(): string {
        return $this->nama_role;
    }
}

class User {
    private int $iduser;
    private string $nama;
    private string $email;
    private string $password;

    public function __construct(int $iduser = 0, string $nama = "", string $email = "", string $password = "", bool $isHashed = false) {
        $this->iduser = $iduser;
        $this->nama = $nama;
        $this->email = $email;
        // Jika password belum di-hash, hash dulu
        $this->password = $isHashed ? $password : password_hash($password, PASSWORD_DEFAULT);
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

    // Getter
    public function getIduser(): int { return $this->iduser; }
    public function getNama(): string { return $this->nama; }
    public function getEmail(): string { return $this->email; }
    public function getPassword(): string { return $this->password; }
}

class UserRole {
    private int $idrole_user;
    private int $iduser;
    private int $idrole;
    private bool $status;

    public function __construct(int $idrole_user = 0, int $iduser = 0, int $idrole = 0, bool $status = false) {
        $this->idrole_user = $idrole_user;
        $this->iduser = $iduser;
        $this->idrole = $idrole;
        $this->status = $status;
    }

    // CREATE
    public function create($db): bool {
        $stmt = $db->prepare("INSERT INTO role_user (iduser, idrole, status) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $this->iduser, $this->idrole, $this->status);
        return $stmt->execute();
    }

    // READ (by idrole_user)
    public static function getById($db, int $idrole_user): ?UserRole {
        $stmt = $db->prepare("SELECT * FROM role_user WHERE idrole_user=?");
        $stmt->bind_param("i", $idrole_user);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return new UserRole($row['idrole_user'], $row['iduser'], $row['idrole'], $row['status']);
        }
        return null;
    }

    // UPDATE
    public function update($db): bool {
        $stmt = $db->prepare("UPDATE role_user SET iduser=?, idrole=?, status=? WHERE idrole_user=?");
        $stmt->bind_param("iiii", $this->iduser, $this->idrole, $this->status, $this->idrole_user);
        return $stmt->execute();
    }

    // DELETE
    public function delete($db): bool {
        $stmt = $db->prepare("DELETE FROM role_user WHERE idrole_user=?");
        $stmt->bind_param("i", $this->idrole_user);
        return $stmt->execute();
    }

    // Ambil semua user beserta role-nya
    public static function getAllWithRole($db) {
        $data = [];
        $sql = "SELECT u.iduser, u.nama, r.nama_role, ru.status
                FROM user u
                LEFT JOIN role_user ru ON u.iduser = ru.iduser
                LEFT JOIN role r ON ru.idrole = r.idrole
                ORDER BY u.iduser";
        $result = $db->query($sql);
        while ($row = $result->fetch_assoc()) {
            $iduser = $row['iduser'];
            if (!isset($data[$iduser])) {
                $data[$iduser] = [
                    'iduser' => $iduser,
                    'nama' => $row['nama'],
                    'roles' => []
                ];
            }
            if ($row['nama_role']) {
                $data[$iduser]['roles'][] = [
                    'nama_role' => $row['nama_role'],
                    'status' => $row['status']
                ];
            }
        }
        return $data;
    }

    // Tambah role ke user
    public static function addRoleToUser($db, $iduser, $idrole) {
        // Nonaktifkan semua role user dulu (opsional, jika hanya satu role aktif)
        $stmt = $db->prepare("UPDATE role_user SET status=0 WHERE iduser=?");
        $stmt->bind_param("i", $iduser);
        $stmt->execute();
        $stmt->close();

        // Cek apakah role sudah ada
        $stmt = $db->prepare("SELECT idrole_user FROM role_user WHERE iduser=? AND idrole=?");
        $stmt->bind_param("ii", $iduser, $idrole);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            // Jika sudah ada, aktifkan saja
            $stmt->close();
            $stmt = $db->prepare("UPDATE role_user SET status=1 WHERE iduser=? AND idrole=?");
            $stmt->bind_param("ii", $iduser, $idrole);
            $stmt->execute();
            $stmt->close();
        } else {
            // Jika belum ada, insert baru
            $stmt->close();
            $stmt = $db->prepare("INSERT INTO role_user (iduser, idrole, status) VALUES (?, ?, 1)");
            $stmt->bind_param("ii", $iduser, $idrole);
            $stmt->execute();
            $stmt->close();
        }
    }
}
?>