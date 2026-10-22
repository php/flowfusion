--TEST--
Translated seed (inferredbugs-csharp: DFe.NET/6)
--FILE--
<?php

class NFe {
    public $infNFe;
}

class InfNFe {
    public $det = [];
    public $total;
}

class Det {
    public $prod;
}

class Prod {
    public $cProd;
    public $xProd;
    public $qCom;
    public $vUnCom;
    public $vProd;
    public $vDesc;
}

class Total {
    public $ICMSTot;
}

class ICMSTot {
    public $vTotTrib;
}

$det1 = new Det();
$det1->prod = new Prod();
$det1->prod->cProd = "001";
$det1->prod->xProd = "Product 1";
$det1->prod->qCom = 10;
$det1->prod->vUnCom = 5.00;
$det1->prod->vProd = 50.00;
$det1->prod->vDesc = 0.00;

$det2 = new Det();
$det2->prod = new Prod();
$det2->prod->cProd = "002";
$det2->prod->xProd = "Product 2";
$det2->prod->qCom = 5;
$det2->prod->vUnCom = 10.00;
$det2->prod->vProd = 50.00;
$det2->prod->vDesc = 5.00;

$nfe = new NFe();
$nfe->infNFe = new InfNFe();
$nfe->infNFe->det = [$det1, $det2];

$total = new Total();
$total->ICMSTot = new ICMSTot();
$total->ICMSTot->vTotTrib = 10.00;

$nfe->infNFe->total = $total;

$totalDesconto = 0;
$totalValor = 0;

foreach ($nfe->infNFe->det as $det) {
    $totalValor += $det->prod->vProd;
    $totalDesconto += $det->prod->vDesc;
}

$totalAPagar = $totalValor - $totalDesconto;

echo "Valor Total: R$ " . number_format($totalValor, 2, ',', '.') . PHP_EOL;
echo "Total Desconto: R$ " . number_format($totalDesconto, 2, ',', '.') . PHP_EOL;
echo "Valor a Pagar: R$ " . number_format($totalAPagar, 2, ',', '.') . PHP_EOL;
--EXPECT--
