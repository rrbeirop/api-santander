<?php

namespace App\Controller;

use App\Dto\UsuarioContaDto;
use App\Dto\UsuarioDto;
use App\Entity\Conta;
use App\Entity\Usuario;
use App\Repository\ContaRepository;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
final class UsuariosController extends AbstractController
{
    #[Route('/usuarios', name: 'usuarios_criar', methods: ['POST'])]
    public function criar(
        #[MapRequestPayload(acceptFormat: 'json')]
        UsuarioDto $usuarioDto,
        EntityManagerInterface $entityManager,
        UsuarioRepository $usuarioRepository
    ): JsonResponse
    {
        $erros = [];
        //Validar CPF
        if (empty($usuarioDto->getCpf())) {
            array_push($erros, [
                'message' => 'CPF é obrigatório!'
            ]);
        } elseif (strlen($usuarioDto->getCpf()) < 11) {
            array_push($erros, [
                'message' => 'CPF Inválido!'
            ]);
        }
        // Validar Nome
        if (empty($usuarioDto->getNome())) {
            array_push($erros, [
                'message' => 'Nome é obrigatório!'
            ]);
        }
        //Validar E-mail
        if (empty($usuarioDto->getEmail())) {
            array_push($erros, [
                'message' => 'E-mail é obrigatório!'
            ]);
        }
        //Validar Senha
        if (empty($usuarioDto->getSenha())) {
            array_push($erros, [
                'message' => 'Senha é obrigatória!'
            ]);
        }
        //Validar Senha
        if (empty($usuarioDto->getTelefone())) {
            array_push($erros, [
                'message' => 'Telefone é obrigatório!'
            ]);
        }
        if (count($erros) > 0) {
            return $this->json($erros, 422);
        }

        //Valida se o cpf ja ta cadastrado
        $usuarioRepository = $usuarioRepository->findByCpf($usuarioDto->getCpf());
        if ($usuarioRepository) {
            return $this->json([
                'message' => 'Este CPF já está cadastrado!'
            ], 409);
        }

        $usuario = new Usuario();
        $usuario->setCpf($usuarioDto->getCpf());
        $usuario->setNome($usuarioDto->getNome());
        $usuario->setEmail($usuarioDto->getEmail());
        $usuario->setSenha($usuarioDto->getSenha());
        $usuario->setTelefone($usuarioDto->getTelefone());

        $entityManager->persist($usuario);

        //Instanciar o objeto Conta
        $conta = new Conta();
        $numeroConta = preg_replace('/\D/', '', uniqid());
       // $numeroConta = rand(1, 99999);
        $conta->setNumero($numeroConta);
        $conta->setSaldo('0');
        $conta->setUsuario($usuario);

        //criar registro na tb conta
        $entityManager->persist($conta);
        $entityManager->flush();

        //retornar os dados de usuário e conta
        return $this->json([$usuario]);

        //retornar dados de usuario e conta
        $usuarioContaDto = new UsuarioContaDto();
        $usuarioContaDto->setId($usuario->getId());
        $usuarioContaDto->setNome($usuario->getNome());
        $usuarioContaDto->setCpf($usuario->getCpf());
        $usuarioContaDto->setEmail($usuario->getEmail());
        $usuarioContaDto->setTelefone($usuario->getTelefone());
        $usuarioContaDto->setNumeroConta($conta->getNumeroConta());
        $usuarioContaDto-> setSaldo($conta->getSaldo());

        return $this ->json($usuarioContaDto, status: 201);
    }

    #[Route('/usuarios/{id}', name: 'usuarios_buscar', methods: ['GET'])]
    public function buscarPorId (
        int $id,
        ContaRepository $contaRepository

    ) {  
        $conta = $contaRepository->findByUsuarioId($id);
        
        if (!$conta) {
            return $this ->json ([
                'message' => 'Usuario não encontrado'

            ], status: 404);
        }

        $usuarioContaDto = new UsuarioContaDto();
        $usuarioContaDto->setId($conta->getUsuario()->getId());
        $usuarioContaDto->setNome($conta->getUsuario()->getNome());
        $usuarioContaDto->setCpf($conta->getUsuario()->getCpf());
        $usuarioContaDto->setEmail($conta->getUsuario()->getEmail());
        $usuarioContaDto->setTelefone($conta->getUsuario()->getTelefone());
        $usuarioContaDto->setNumeroConta($conta->getNumero());
        $usuarioContaDto->setSaldo($conta->getSaldo());

        return $this->json($usuarioContaDto);
    

        }
        



    
}
    
    