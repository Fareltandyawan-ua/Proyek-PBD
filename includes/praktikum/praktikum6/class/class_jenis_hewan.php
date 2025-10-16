<?php
class JenisHewan {
    private int $idjenis_hewan;
    private string $nama_jenis_hewan;

    public function __construct(int $idjenis_hewan = 0, string $nama_jenis_hewan = "") {
        $this->idjenis_hewan = $idjenis_hewan;
        $this->nama_jenis_hewan = $nama_jenis_hewan;
    }

    public static function getAll($db): array {
        $result = $db->query("SELECT * FROM jenis_hewan");
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = new JenisHewan($row['idjenis_hewan'], $row['nama_jenis_hewan']);
        }
        return $data;
    }

    public static function getById($db, int $idjenis_hewan): ?JenisHewan {
        $stmt = $db->prepare("SELECT * FROM jenis_hewan WHERE idjenis_hewan=?");
        $stmt->bind_param("i", $idjenis_hewan);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return new JenisHewan($row['idjenis_hewan'], $row['nama_jenis_hewan']);
        }
        return null;
    }

    public function create($db): bool {
        $stmt = $db->prepare("INSERT INTO jenis_hewan (nama_jenis_hewan) VALUES (?)");
        $stmt->bind_param("s", $this->nama_jenis_hewan);
        return $stmt->execute();
    }

    public function update($db): bool {
        $stmt = $db->prepare("UPDATE jenis_hewan SET nama_jenis_hewan=? WHERE idjenis_hewan=?");
        $stmt->bind_param("si", $this->nama_jenis_hewan, $this->idjenis_hewan);
        return $stmt->execute();
    }

    public function delete($db): bool {
        $stmt = $db->prepare("DELETE FROM jenis_hewan WHERE idjenis_hewan=?");
        $stmt->bind_param("i", $this->idjenis_hewan);
        return $stmt->execute();
    }

    public function getIdjenis_hewan(): int {
        return $this->idjenis_hewan;
    }

    public function getNamaJenisHewan(): string {
        return $this->nama_jenis_hewan;
    }
}
?>