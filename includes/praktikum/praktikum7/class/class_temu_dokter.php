<?php
class TemuDokter
{
    private $id;
    private $id_pasien;
    private $id_dokter;
    private $tanggal_temu;
    private $waktu_temu;
    private $status;

    public function __construct($id = null, $id_pasien = null, $id_dokter = null, $tanggal_temu = null, $waktu_temu = null, $status = null)
    {
        $this->id = $id;
        $this->id_pasien = $id_pasien;
        $this->id_dokter = $id_dokter;
        $this->tanggal_temu = $tanggal_temu;
        $this->waktu_temu = $waktu_temu;
        $this->status = $status;
    }

    public function getId()
    {
        return $this->id;
    }
    public function setId($id)
    {
        $this->id = $id;
    }
    public function getIdPasien()
    {
        return $this->id_pasien;
    }
    public function setIdPasien($id_pasien)
    {
        $this->id_pasien = $id_pasien;
    }
    public function getIdDokter()
    {
        return $this->id_dokter;
    }
    public function setIdDokter($id_dokter)
    {
        $this->id_dokter = $id_dokter;
    }
    public function getTanggalTemu()
    {
        return $this->tanggal_temu;
    }
    public function setTanggalTemu($tanggal_temu)
    {
        $this->tanggal_temu = $tanggal_temu;
    }
    public function getWaktuTemu()
    {
        return $this->waktu_temu;
    }
    public function setWaktuTemu($waktu_temu)
    {
        $this->waktu_temu = $waktu_temu;
    }
    public function getStatus()
    {
        return $this->status;
    }
    public function setStatus($status)
    {
        $this->status = $status;
    }
    public static function getByPemilik($db, $idpemilik)
    {
        $sql = "SELECT t.*, p.nama AS nama_pet, u.nama AS nama_dokter
                FROM temu_dokter t
                JOIN pet p ON t.idpet = p.idpet
                JOIN role_user ru ON t.idrole_user = ru.idrole_user
                JOIN user u ON ru.iduser = u.iduser
                WHERE p.idpemilik = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $idpemilik);
        $stmt->execute();
        return $stmt->get_result();
    }
}
