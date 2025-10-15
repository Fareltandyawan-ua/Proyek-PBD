<?php
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