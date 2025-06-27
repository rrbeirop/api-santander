<?php

namespace App\Controller;

use App\Dto\UsuarioContaDto;
use App\Entity\Conta;
use App\Entity\Usuario;
use App\Repository\ContaRepository;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route('/api')]
final class TransacaoController extends AbstractController
{
    #[Route('/Transacao', name: 'realizar_transacao', methods: ['POST'])]
    public function realizarTransacao(
        Request $request,
        UsuarioRepository $usuarioRepository,
        ContaRepository $contaRepository,
        EntityManagerInterface $em
    ): JsonResponse {
        // Decodifica o JSON do corpo da requisição
        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['usuario_id'], $data['conta_id'], $data['valor'])) {
            return new JsonResponse(['erro' => 'Dados incompletos'], 400);
        }

        // Busca o usuário e a conta
        $usuario = $usuarioRepository->find($data['usuario_id']);
        $conta = $contaRepository->find($data['conta_id']);

        if (!$usuario || !$conta) {
            return new JsonResponse(['erro' => 'Usuário ou conta não encontrados'], 404);
        }

        // Exemplo: adiciona o valor na conta
        $novoSaldo = $conta->getSaldo() + $data['valor'];
        $conta->setSaldo($novoSaldo);

        $em->persist($conta);
        $em->flush();

        return new JsonResponse([
            'mensagem' => 'Transação realizada com sucesso',
            'novo_saldo' => $conta->getSaldo()
        ]);
    }
}
