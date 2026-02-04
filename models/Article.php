<?php
// Model Article
class Article {
    public $id, $judul, $isi, $gambar, $created_at;
    public function __construct($data) {
        $this->id = $data['id'] ?? null;
        $this->judul = $data['judul'] ?? '';
        $this->isi = $data['isi'] ?? '';
        $this->gambar = $data['gambar'] ?? '';
        $this->created_at = $data['created_at'] ?? '';
    }
}
