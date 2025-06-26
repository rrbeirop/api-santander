<?php

namespace App\Controller;

use App\Dto\UsuarioDto;
use App\Entity\Conta;
use App\Entity\Usuario;
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
        //$numeroConta = preg_replace('/\D/', '', uniqid());
        $numeroConta = rand(1, 100);
        $conta->setNumero($numeroConta);
        $conta->setSaldo('0');
        $conta->setUsuario($usuario);

        //criar registro na tb conta
        $entityManager->persist($conta);
        $entityManager->flush();

        //retornar os dados de usuário e conta
        return $this->json([$usuario]);
    }
}
