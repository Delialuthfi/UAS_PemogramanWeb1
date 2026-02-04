<?php
// Model Doctor
class Doctor {
    public $id, $nama, $spesialis, $tarif, $foto, $deskripsi;
    public function __construct($data) {
        $this->id = $data['id'] ?? null;
        $this->nama = $data['nama'] ?? '';
        $this->spesialis = $data['spesialis'] ?? '';
        $this->tarif = $data['tarif'] ?? 0;
        $this->foto = $data['foto'] ?? '';
        $this->deskripsi = $data['deskripsi'] ?? '';
    }
}
