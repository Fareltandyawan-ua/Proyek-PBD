<?php
class KategoriKlinis {
	private DBConnection $db;

	public function __construct() {
		$this->db = new DBConnection();
		$this->db->init_connect();
	}

	public function create(string $nama): bool {
		$stmt = $this->db->dbconn->prepare('INSERT INTO kategori_klinis (nama_kategori_klinis) VALUES (?)');
		$stmt->bind_param('s', $nama);
		$result = $stmt->execute();
		$stmt->close();
		return $result;
	}

	public function update(int $id, string $nama): bool {
		$stmt = $this->db->dbconn->prepare('UPDATE kategori_klinis SET nama_kategori_klinis=? WHERE idkategori_klinis=?');
		$stmt->bind_param('si', $nama, $id);
		$result = $stmt->execute();
		$stmt->close();
		return $result;
	}

	public function delete(int $id): bool {
		$stmt = $this->db->dbconn->prepare('DELETE FROM kategori_klinis WHERE idkategori_klinis=?');
		$stmt->bind_param('i', $id);
		$result = $stmt->execute();
		$stmt->close();
		return $result;
	}

	public function getAll(): array {
		$result = $this->db->send_query('SELECT * FROM kategori_klinis');
		return $result['status'] === 'success' ? $result['data'] : [];
	}

	public function getById(int $id): ?array {
		$stmt = $this->db->dbconn->prepare('SELECT * FROM kategori_klinis WHERE idkategori_klinis=?');
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$data = $result->fetch_assoc();
		$stmt->close();
		return $data ?: null;
	}
}
