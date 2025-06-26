<?php 

namespace App\Dto;

class UsuarioDto
{
    private ?string $cpf = null;
    private ?string $nome;
    private ?string $email;
    private ?string $senha;
    private ?string $telefone;

    //Função do CPF
    public function getCpf() {
        return $this->cpf;
    }
    public function setCpf($cpf) {
        $this->cpf = $cpf;
        return $this;
    }
    //Função do Nome
    public function getNome() {
        return $this->nome;
    }
    public function setNome($nome) {
        $this->nome = $nome;
        return $this;
    }
    //Função do Email
    public function getEmail() {
        return $this->email;
    }
    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }
    //Função da Senha
    public function getSenha() {
        return $this->senha;
    }
    public function setSenha($senha) {
        $this->senha = $senha;
        return $this;
    }
    //Função do Telefone
    public function getTelefone() {
        return $this->telefone;
    }
    public function setTelefone($telefone) {
        $this->telefone = $telefone;
        return $this;
    }
}