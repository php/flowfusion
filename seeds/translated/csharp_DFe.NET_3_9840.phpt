--TEST--
Translated seed (inferredbugs-csharp: DFe.NET/3)
--FILE--
<?php

class EventoCancelar
{
    public function MDFeEventoCancelar($mdfe, $sequenciaEvento, $protocolo, $justificativa)
    {
        $cancelamento = $this->criaEvCancMDFe($protocolo, $justificativa);
        
        $retorno = $this->executar($mdfe, $sequenciaEvento, $cancelamento);
        
        return $retorno;
    }
    
    private function criaEvCancMDFe($protocolo, $justificativa)
    {
        return [
            'protocolo' => $protocolo,
            'justificativa' => $justificativa
        ];
    }

    private function executar($mdfe, $sequenciaEvento, $cancelamento)
    {
        // Simulating some operation that could be error-prone
        if (!is_array($mdfe) || !isset($mdfe['id'])) {
            return "Erro: MDFe inválido.";
        }

        // Simulating an operation that may return unexpected results
        return [
            'mdfe' => $mdfe['id'],
            'sequenciaEvento' => $sequenciaEvento,
            'cancelamento' => $cancelamento
        ];
    }
}

function main()
{
    $eventoCancelar = new EventoCancelar();
    $mdfe = ['id' => 12345]; // Example MDFe data
    $sequenciaEvento = 1;
    $protocolo = 'PROT123456';
    $justificativa = 'Cancelamento solicitado pelo usuário.';

    $resultado = $eventoCancelar->MDFeEventoCancelar($mdfe, $sequenciaEvento, $protocolo, $justificativa);
    var_dump($resultado);
}

main();
--EXPECT--
