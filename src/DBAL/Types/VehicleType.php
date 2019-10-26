<?php

namespace App\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

final class VehicleType extends AbstractEnumType
{
    public const CAR = 'CAR';
    public const BIKE = 'BIKE';
    public const ATV = 'ATV';
    public const JET = 'JET';
    // public const BOAT = 'BOAT';


    protected static $choices = [
        self::CAR => 'Voiture',
        self::BIKE => 'Moto / Scooter',
        self::ATV => 'Quad',
        self::JET => 'Jet Ski',
        // self::BOAT => 'Moteur Bateau'
    ];
}