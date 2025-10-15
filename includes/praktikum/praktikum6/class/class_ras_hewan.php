<?php
class RasHewan {
    private int $idras_hewan;
    private string $nama_ras;
    private int $idjenis_hewan;

    public function __construct(int $idras_hewan = 0, string $nama_ras = "", int $idjenis_hewan = 0) {
        $this->idras_hewan = $idras_hewan;
        $this->nama_ras = $nama_ras;
        $this->idjenis_hewan = $idjenis_hewan;
    }

    public static function getByJenis($db, int $idjenis_hewan): array {
        $stmt = $db->prepare("SELECT * FROM ras_hewan WHERE idjenis_hewan=? ORDER BY nama_ras");
        $stmt->bind_param("i", $idjenis_hewan);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = new RasHewan($row['idras_hewan'], $row['nama_ras'], $row['idjenis_hewan']);
        }
        return $data;
    }

    public function create($db): bool {
        $stmt = $db->prepare("INSERT INTO ras_hewan (nama_ras, idjenis_hewan) VALUES (?, ?)");
        $stmt->bind_param("si", $this->nama_ras, $this->idjenis_hewan);
        return $stmt->execute();
    }

    public static function getById($db, int $idras_hewan): ?RasHewan {
        $stmt = $db->prepare("SELECT * FROM ras_hewan WHERE idras_hewan=?");
        $stmt->bind_param("i", $idras_hewan);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return new RasHewan($row['idras_hewan'], $row['nama_ras'], $row['idjenis_hewan']);
        }
        return null;
    }

    public function update($db): bool {
        $stmt = $db->prepare("UPDATE ras_hewan SET nama_ras=?, idjenis_hewan=? WHERE idras_hewan=?");
        $stmt->bind_param("sii", $this->nama_ras, $this->idjenis_hewan, $this->idras_hewan);
        return $stmt->execute();
    }

    public function delete($db): bool {
        $stmt = $db->prepare("DELETE FROM ras_hewan WHERE idras_hewan=?");
        $stmt->bind_param("i", $this->idras_hewan);
        return $stmt->execute();
    }

    public function getIdrasHewan(): int { return $this->idras_hewan; }
    public function getNamaRas(): string { return $this->nama_ras; }
    public function getIdjenisHewan(): int { return $this->idjenis_hewan; }
}
?>