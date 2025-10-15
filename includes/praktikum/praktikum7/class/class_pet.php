<?php
class Pet {
    private int $idpet;
    private string $nama;
    private string $tanggal_lahir;
    private string $warna_tanda;
    private string $jenis_kelamin;
    private int $idpemilik;
    private int $idras_hewan;

    public function __construct(
        int $idpet = 0, string $nama = "", string $tanggal_lahir = "", string $warna_tanda = "",
        string $jenis_kelamin = "", int $idpemilik = 0, int $idras_hewan = 0
    ) {
        $this->idpet = $idpet;
        $this->nama = $nama;
        $this->tanggal_lahir = $tanggal_lahir;
        $this->warna_tanda = $warna_tanda;
        $this->jenis_kelamin = $jenis_kelamin;
        $this->idpemilik = $idpemilik;
        $this->idras_hewan = $idras_hewan;
    }

    public function create($db): bool {
        $stmt = $db->prepare('INSERT INTO pet (nama, tanggal_lahir, warna_tanda, jenis_kelamin, idpemilik, idras_hewan) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('ssssii', $this->nama, $this->tanggal_lahir, $this->warna_tanda, $this->jenis_kelamin, $this->idpemilik, $this->idras_hewan);
        return $stmt->execute();
    }

    public function update($db): bool {
        $stmt = $db->prepare('UPDATE pet SET nama=?, tanggal_lahir=?, warna_tanda=?, jenis_kelamin=?, idpemilik=?, idras_hewan=? WHERE idpet=?');
        $stmt->bind_param('ssssiii', $this->nama, $this->tanggal_lahir, $this->warna_tanda, $this->jenis_kelamin, $this->idpemilik, $this->idras_hewan, $this->idpet);
        return $stmt->execute();
    }

    public function delete($db): bool {
        $stmt = $db->prepare('DELETE FROM pet WHERE idpet=?');
        $stmt->bind_param('i', $this->idpet);
        return $stmt->execute();
    }

    public static function getById($db, int $idpet): ?Pet {
        $stmt = $db->prepare('SELECT * FROM pet WHERE idpet=?');
        $stmt->bind_param('i', $idpet);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return new Pet(
                $row['idpet'], $row['nama'], $row['tanggal_lahir'],
                $row['warna_tanda'], $row['jenis_kelamin'],
                $row['idpemilik'], $row['idras_hewan']
            );
        }
        return null;
    }

    public static function getAllWithJoin($db): array {
        $query = 'SELECT pt.idpet, pt.nama AS nama_pet, pt.tanggal_lahir, pt.warna_tanda, pt.jenis_kelamin, pm.nama AS nama_pemilik, rh.nama_ras, pt.idpemilik, pt.idras_hewan
                  FROM pet pt
                  LEFT JOIN pemilik p ON pt.idpemilik = p.idpemilik
                  LEFT JOIN user pm ON p.iduser = pm.iduser
                  LEFT JOIN ras_hewan rh ON pt.idras_hewan = rh.idras_hewan';
        $result = $db->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public static function getByPemilik($db, int $idpemilik): array {
        $stmt = $db->prepare("SELECT * FROM pet WHERE idpemilik=?");
        $stmt->bind_param("i", $idpemilik);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->close();
        return $data;
    }

    // Getter
    public function getIdpet(): int { return $this->idpet; }
    public function getNama(): string { return $this->nama; }
    public function getTanggalLahir(): string { return $this->tanggal_lahir; }
    public function getWarnaTanda(): string { return $this->warna_tanda; }
    public function getJenisKelamin(): string { return $this->jenis_kelamin; }
    public function getIdpemilik(): int { return $this->idpemilik; }
    public function getIdrasHewan(): int { return $this->idras_hewan; }
    // Setter
    public function setNama(string $nama): void { $this->nama = $nama; }
    public function setTanggalLahir(string $tgl): void { $this->tanggal_lahir = $tgl; }
    public function setWarnaTanda(string $w): void { $this->warna_tanda = $w; }
    public function setJenisKelamin(string $jk): void { $this->jenis_kelamin = $jk; }
    public function setIdpemilik(int $id): void { $this->idpemilik = $id; }
    public function setIdrasHewan(int $id): void { $this->idras_hewan = $id; }
}