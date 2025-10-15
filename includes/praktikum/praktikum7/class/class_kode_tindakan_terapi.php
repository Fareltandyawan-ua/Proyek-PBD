<?php
class KodeTindakanTerapi {
    private int $idkode_tindakan_terapi;
    private string $kode;
    private string $deskripsi_tindakan_terapi;
    private int $idkategori;
    private int $idkategori_klinis;

    public function __construct(
        int $idkode_tindakan_terapi = 0, string $kode = "", string $deskripsi_tindakan_terapi = "",
        int $idkategori = 0, int $idkategori_klinis = 0
    ) {
        $this->idkode_tindakan_terapi = $idkode_tindakan_terapi;
        $this->kode = $kode;
        $this->deskripsi_tindakan_terapi = $deskripsi_tindakan_terapi;
        $this->idkategori = $idkategori;
        $this->idkategori_klinis = $idkategori_klinis;
    }

    public function create($db): bool {
        $stmt = $db->prepare('INSERT INTO kode_tindakan_terapi (kode, deskripsi_tindakan_terapi, idkategori, idkategori_klinis) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('ssii', $this->kode, $this->deskripsi_tindakan_terapi, $this->idkategori, $this->idkategori_klinis);
        return $stmt->execute();
    }

    public function update($db): bool {
        $stmt = $db->prepare('UPDATE kode_tindakan_terapi SET kode=?, deskripsi_tindakan_terapi=?, idkategori=?, idkategori_klinis=? WHERE idkode_tindakan_terapi=?');
        $stmt->bind_param('ssiii', $this->kode, $this->deskripsi_tindakan_terapi, $this->idkategori, $this->idkategori_klinis, $this->idkode_tindakan_terapi);
        return $stmt->execute();
    }

    public function delete($db): bool {
        $stmt = $db->prepare('DELETE FROM kode_tindakan_terapi WHERE idkode_tindakan_terapi=?');
        $stmt->bind_param('i', $this->idkode_tindakan_terapi);
        return $stmt->execute();
    }

    public static function getById($db, int $id): ?KodeTindakanTerapi {
        $stmt = $db->prepare('SELECT * FROM kode_tindakan_terapi WHERE idkode_tindakan_terapi=?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return new KodeTindakanTerapi(
                $row['idkode_tindakan_terapi'],
                $row['kode'],
                $row['deskripsi_tindakan_terapi'],
                $row['idkategori'],
                $row['idkategori_klinis']
            );
        }
        return null;
    }

    public static function getAllWithJoin($db): array {
        $query = 'SELECT ktt.idkode_tindakan_terapi, ktt.kode, ktt.deskripsi_tindakan_terapi, ktt.idkategori, ktt.idkategori_klinis, k.nama_kategori, kk.nama_kategori_klinis
                  FROM kode_tindakan_terapi ktt
                  LEFT JOIN kategori k ON ktt.idkategori = k.idkategori
                  LEFT JOIN kategori_klinis kk ON ktt.idkategori_klinis = kk.idkategori_klinis';
        $result = $db->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    // Getter & Setter jika diperlukan
    public function getId(): int { return $this->idkode_tindakan_terapi; }
    public function getKode(): string { return $this->kode; }
    public function getDeskripsi(): string { return $this->deskripsi_tindakan_terapi; }
    public function getIdKategori(): int { return $this->idkategori; }
    public function getIdKategoriKlinis(): int { return $this->idkategori_klinis; }
    public function setKode(string $kode): void { $this->kode = $kode; }
    public function setDeskripsi(string $desc): void { $this->deskripsi_tindakan_terapi = $desc; }
    public function setIdKategori(int $id): void { $this->idkategori = $id; }
    public function setIdKategoriKlinis(int $id): void { $this->idkategori_klinis = $id; }
}