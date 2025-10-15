<?php
class DetailRekamMedis {
    private $db;
    public function __construct($dbconn) {
        $this->db = $dbconn;
    }
    public function create($data) {
        $stmt = $this->db->prepare('INSERT INTO detail_rekam_medis (idrekam_medis, idkode_tindakan_terapi, detail) VALUES (?, ?, ?)');
        $stmt->bind_param('iis', $data['idrekam_medis'], $data['idkode_tindakan_terapi'], $data['detail']);
        $stmt->execute();
        $stmt->close();
    }
    public function update($data) {
        $stmt = $this->db->prepare('UPDATE detail_rekam_medis SET idrekam_medis=?, idkode_tindakan_terapi=?, detail=? WHERE iddetail_rekam_medis=?');
        $stmt->bind_param('iisi', $data['idrekam_medis'], $data['idkode_tindakan_terapi'], $data['detail'], $data['iddetail_rekam_medis']);
        $stmt->execute();
        $stmt->close();
    }
    public function delete($id) {
        $stmt = $this->db->prepare('DELETE FROM detail_rekam_medis WHERE iddetail_rekam_medis=?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();
    }
    public function getById($id) {
        $stmt = $this->db->prepare('SELECT * FROM detail_rekam_medis WHERE iddetail_rekam_medis=?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();
        return $data;
    }
    public function getAllByRekamMedis($idrekam_medis) {
        $stmt = $this->db->prepare('SELECT d.*, k.kode, k.deskripsi_tindakan_terapi FROM detail_rekam_medis d LEFT JOIN kode_tindakan_terapi k ON d.idkode_tindakan_terapi = k.idkode_tindakan_terapi WHERE d.idrekam_medis=?');
        $stmt->bind_param('i', $idrekam_medis);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->close();
        return $data;
    }
}
