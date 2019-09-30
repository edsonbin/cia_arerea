<?php

class Flight {​​
    private $flightNumber;
    private $cia;
    private $departureAirport;
    private $arrivalAirport;
    private $departureTime;
    private $arrivalTime;
    private $valorTotal;
    private $baggages = [];
    private $liveCharges = [];
​
    public function __construct(
        string $flightNumber,
        string $cia,
        string $departureAirport,
        string $arrivalAirport,
        DateTime $departureTime,
        DateTime $arrivalTime,
        float $valorTotal
    )
    {
        $this->flightNumber = $flightNumber;
        $this->cia = $cia;
        $this->departureAirport = $departureAirport;
        $this->arrivalAirport = $arrivalAirport;
        $this->departureTime = $departureTime;
        $this->arrivalTime = $arrivalTime;
        $this->valorTotal = $valorTotal;

    }
​
​
    public function getFlightNumber()
    {
        return $this->flightNumber;
    }
​
​
    public function getCia()
    {
        return $this->cia;
    }
​
    public function getDepartureAirport()
    {
        return $this->departureAirport;
    }
​
​
    public function getArrivalAirport()
    {
        return $this->arrivalAirport;
    }
​
​
    public function getDepartureTime()
    {
        return $this->departureTime;
    }
​
    public function getArrivalTime()
    {
        return $this->arrivalTime;
    }
​
    public function getValorTotal()
    {

        return $this->valorTotal;
    }

    public function addBaggage($baggage)
    {
        array_push($this->baggages,$baggage);
        $this->valorTotal = $this->valorTotal + $baggage->getValorTotal();
    }

    public function addLiveCharge($liveCherge)
    {
        array_push($this->liveCharges,$liveCherge);
        $this->valorTotal = $this->valorTotal + $liveCherge->getValorTotal();
    }

    public function getBaggagesExtract()
    {
        $lista = [];
        foreach ($this->baggages as $item)
        {
            $lista[] = [
                "baggageId"=>$item->baggageId,
                "valor"=>$item->getValorTotal()
            ]
        }
        return $lista;
    }

    public function getLiveChargesExtract()
    {
        $lista = [];
        foreach ($this->baggages as $item)
        {
            $lista[] = [
                "liveChargeId"=>$item->liveChargeId,
                "valor"=>$item->getValorTotal()
            ]
        }
        return $lista;
    }

}
​
​class Baggage {​​
    private $baggageId;
    private $valorTotal;
​
    public function __construct(
        string $baggageId,
        float $valorTotal
    )
    {
        $this->baggageId = $baggageId;
        $this->valorTotal = $valorTotal;
    }
​​
    public function getBaggageId()
    {
        return $this->baggageId;
    }
​
    public function getValorTotal()
    {
        return $this->valorTotal;
    }
}
​
​class LiveCharge {​​
    private $liveChargeId;
    private $valorTotal;
​
    public function __construct(
        string $liveChargeId,
        float $valorTotal
    )
    {
        $this->liveChargeId = $liveChargeId;
        $this->valorTotal = $valorTotal;
    }
​​
    public function getLiveChargeId()
    {
        return $this->liveChargeId;
    }
​
    public function getValorTotal()
    {
        return $this->valorTotal;
    }
}

class Checkout
{
    private $flightOutbound;
    private $flightInbound;
​
    public function __construct(Flight $flightOutbound, Flight $flightInbound = null)
    {
        $this->flightOutbound = $flightOutbound;
        $this->flightInbound = $flightInbound;
    }
​
    public function generateExtract()
    {
        $valorTotal = $this->flightOutbound->getValorTotal();

        $flightDetailsOutbound = [
            'De' => $this->flightOutbound->getDepartureAirport(),
            'Para' => $this->flightOutbound->getArrivalAirport(),
            'Embarque' => $this->flightOutbound->getDepartureTime()->format('d/m/Y H:i'),
            'Desembarque' => $this->flightOutbound->getArrivalTime()->format('d/m/Y H:i'),
            'Cia' => $this->flightOutbound->getCia(),
            'Valor' => $this->flightOutbound->getValorTotal(),
            'Bagagens' => $this->flightOutbound->getBaggagesExtract(),
            'Cargas vivas' => $this->flightOutbound->getLiveChargesExtract()
        ];
        ​
        $flightDetailsInbound = [];
        if (! is_null($this->flightInbound)) {
            $valorTotal += $this->flightInbound->getValorTotal();
            $flightDetailsInbound = [
                'De' => $this->flightInbound->getDepartureAirport(),
                'Para' => $this->flightInbound->getArrivalAirport(),
                'Embarque' => $this->flightInbound->getDepartureTime()->format('d/m/Y H:i'),
                'Desembarque' => $this->flightInbound->getArrivalTime()->format('d/m/Y H:i'),
                'Cia' => $this->flightInbound->getCia(),
                'Valor' => $this->flightInbound->getValorTotal(),
                'Bagagens' => $this->flightInbound->getBaggagesExtract(),
                'Cargas vivas' => $this->flightInbound->getLiveChargesExtract()
            ];
        }
​
        return (object) [
            'flightOutbound' => $flightDetailsOutbound,
            'flightInbound' => $flightDetailsInbound,
            'valorTotal' => $valorTotal
        ];
    }
}