<?php
class Pet {
    public $idpet, $nama, $tanggal_lahir, $warna_tanda, $jenis_kelamin, $idpemilik, $idras_hewan;
    public function __construct($idpet, $nama, $tanggal_lahir, $warna_tanda, $jenis_kelamin, $idpemilik, $idras_hewan) {
        $this->idpet = $idpet;
        $this->nama = $nama;
        $this->tanggal_lahir = $tanggal_lahir;
        $this->warna_tanda = $warna_tanda;
        $this->jenis_kelamin = $jenis_kelamin;
        $this->idpemilik = $idpemilik;
        $this->idras_hewan = $idras_hewan;
    }
    public function create($db) {
        $stmt = $db->prepare("INSERT INTO pet (nama, tanggal_lahir, warna_tanda, jenis_kelamin, idpemilik, idras_hewan) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssii", $this->nama, $this->tanggal_lahir, $this->warna_tanda, $this->jenis_kelamin, $this->idpemilik, $this->idras_hewan);
        return $stmt->execute();
    }
}