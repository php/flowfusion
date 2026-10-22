--TEST--
Translated seed (inferredbugs-csharp: DFe.NET/2)
--FILE--
<?php

class EventoEncerramento {
    public function MDFeEventoEncerramento($mdfe, $sequenciaEvento, $protocolo) {
        $encerramento = $this->criaEvEncMDFe($mdfe, $protocolo);
        $retorno = $this->executar($mdfe, $sequenciaEvento, $encerramento);
        return $retorno;
    }

    private function criaEvEncMDFe($mdfe, $protocolo) {
        return ['mdfe' => $mdfe, 'protocolo' => $protocolo];
    }

    private function executar($mdfe, $sequenciaEvento, $encerramento) {
        // Simulate some processing with type coercion
        $result = (string)$sequenciaEvento . " processed with " . json_encode($encerramento);
        return $result;
    }
}

$evento = new EventoEncerramento();
$mdfe = ['id' => 1, 'data' => '2023-10-03'];
$sequenciaEvento = 255; // High value for testing edge case behavior
$protocolo = 'PROTO12345';
$retorno = $evento->MDFeEventoEncerramento($mdfe, $sequenciaEvento, $protocolo);
echo $retorno;
--EXPECT--
