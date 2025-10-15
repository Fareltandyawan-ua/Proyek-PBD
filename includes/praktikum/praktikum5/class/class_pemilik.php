<?php
class Pemilik {
	private $db;
	public function __construct($dbconn) {
		$this->db = $dbconn;
	}

	public function create($data) {
		$iduser = intval($data['iduser']);
		$no_wa = trim($data['no_wa']);
		$alamat = trim($data['alamat']);
		if ($iduser && $no_wa !== '' && $alamat !== '') {
			$stmt = $this->db->prepare('INSERT INTO pemilik (iduser, no_wa, alamat) VALUES (?, ?, ?)');
			$stmt->bind_param('iss', $iduser, $no_wa, $alamat);
			$stmt->execute();
			$stmt->close();
			header('Location: data_pemilik.php');
			exit;
		}
	}

	public function update($data) {
		$id = intval($data['idpemilik']);
		$iduser = intval($data['iduser']);
		$no_wa = trim($data['no_wa']);
		$alamat = trim($data['alamat']);
		if ($id && $iduser && $no_wa !== '' && $alamat !== '') {
			$stmt = $this->db->prepare('UPDATE pemilik SET iduser=?, no_wa=?, alamat=? WHERE idpemilik=?');
			$stmt->bind_param('issi', $iduser, $no_wa, $alamat, $id);
			$stmt->execute();
			$stmt->close();
			header('Location: data_pemilik.php');
			exit;
		}
	}

	public function delete($id) {
		$id = intval($id);
		if ($id) {
			$stmt = $this->db->prepare('DELETE FROM pemilik WHERE idpemilik=?');
			$stmt->bind_param('i', $id);
			$stmt->execute();
			$stmt->close();
			header('Location: data_pemilik.php');
			exit;
		}
	}

	public function getById($id) {
		$id = intval($id);
		$stmt = $this->db->prepare('SELECT * FROM pemilik WHERE idpemilik=?');
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$data = $result->fetch_assoc();
		$stmt->close();
		return is_array($data) ? $data : null;
	}

	public function getAllWithUser() {
		$query = 'SELECT p.idpemilik, u.nama, u.email, p.no_wa, p.alamat, p.iduser FROM pemilik p LEFT JOIN user u ON p.iduser = u.iduser';
		$result = $this->db->query($query);
		if ($result) {
			$data = [];
			while ($row = $result->fetch_assoc()) {
				$data[] = $row;
			}
			return [
				'status' => 'success',
				'data' => $data
			];
		} else {
			return [
				'status' => 'error',
				'message' => $this->db->error
			];
		}
	}
}
?>