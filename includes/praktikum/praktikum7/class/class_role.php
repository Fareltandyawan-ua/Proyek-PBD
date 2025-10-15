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
?>