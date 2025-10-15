<?php
class Kategori {
    private int $idkategori;
    private string $nama_kategori;

    public function __construct(int $idkategori = 0, string $nama_kategori = "") {
        $this->idkategori = $idkategori;
        $this->nama_kategori = $nama_kategori;
    }

    public function create($db): bool {
        $stmt = $db->prepare("INSERT INTO kategori (nama_kategori) VALUES (?)");
        $stmt->bind_param("s", $this->nama_kategori);
        return $stmt->execute();
    }

    public static function getById($db, int $idkategori): ?Kategori {
        $stmt = $db->prepare("SELECT * FROM kategori WHERE idkategori=?");
        $stmt->bind_param("i", $idkategori);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return new Kategori($row['idkategori'], $row['nama_kategori']);
        }
        return null;
    }

    public function update($db): bool {
        $stmt = $db->prepare("UPDATE kategori SET nama_kategori=? WHERE idkategori=?");
        $stmt->bind_param("si", $this->nama_kategori, $this->idkategori);
        return $stmt->execute();
    }

    public function delete($db): bool {
        $stmt = $db->prepare("DELETE FROM kategori WHERE idkategori=?");
        $stmt->bind_param("i", $this->idkategori);
        return $stmt->execute();
    }

    public static function getAll($db): array {
        $kategoris = [];
        $result = $db->query("SELECT idkategori, nama_kategori FROM kategori");
        while ($row = $result->fetch_assoc()) {
            $kategoris[] = new Kategori($row['idkategori'], $row['nama_kategori']);
        }
        return $kategoris;
    }

    public function getIdkategori(): int {
        return $this->idkategori;
    }
    public function getNamaKategori(): string {
        return $this->nama_kategori;
    }
    public function setNamaKategori(string $nama_kategori): void {
        $this->nama_kategori = $nama_kategori;
    }
}
