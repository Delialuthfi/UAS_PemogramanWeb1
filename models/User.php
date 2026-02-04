<?php
// Model User (opsional, untuk pengembangan OOP lebih lanjut)
class User {
    public $id, $nama, $email, $no_hp, $password, $role;
    public function __construct($data) {
        $this->id = $data['id'] ?? null;
        $this->nama = $data['nama'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->no_hp = $data['no_hp'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->role = $data['role'] ?? 'user';
    }
}
