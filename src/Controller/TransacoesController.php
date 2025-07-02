<?php

namespace App\Controller;

use App\Dto\TransacaoRealizarDto;
use App\Entity\Conta;
use App\Repository\ContaRepository;
use PhpParser\Node\Stmt\Return_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
final class TransacoesController extends AbstractController
{
    #[Route('/transacoes', name: 'transacoes_realizar', methods: ['POST'])]
    public function irealizar(
        #[MapRequestPayload(acceptFormat: 'json')]
        TransacaoRealizarDto $entrada,
        ContaRepository $contaRepository

    ): JsonResponse 
    {

        //1. Validade se a entrada tem id de origem / id de destino / valor
        //2. valida se as contass existem
        //3. validar se a origem tem saldo suficientte

        $erros = [];
        if (!$entrada->getIdUsuarioOrigem()) {
            array_push($erros, [
                'message' => 'Informe a conta de Origem!'
            ]);
        
        }
        if (!$entrada->getIdUsuarioDestino()) {
            array_push($erros, [
                'message' => 'Informe a conta de Destino!'
            ]);
        }

        //

        if (!$entrada->getValor() || (float) $entrada->getvalor () <= 0){
            array_push($erros,[
                'message' => 'Informe o valor da transação!'
            ]);
        }

        if ($entrada->getIdUsuarioDestino()=== $entrada->getIdUsuarioOrigem()){
            array_push($erros,[
                'message'=> 'a conta lalalalla'
            ]);
            
        }

        if (count($erros) > 0) {
            return $this->json($erros, 422);
        }
       
        $contaOrigem = $contaRepository->findByUsuarioId($entrada->getIdUsuarioOrigem());
        if (!$contaOrigem);
            return $this->json(
                [ 'message' => 'Con!'], 404);

        $contaDestino = $contaRepository->findByUsuarioId($entrada->getIdUsuarioDestino());
        if (!$contaDestino);
            return $this->json(
                [ 'message' => 'Conta RS!'], 404);
                
        }
                
}  



