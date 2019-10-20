<?php

namespace App\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

final class EngineEnergyType extends AbstractEnumType
{
    public const GASOLINE = 'GAS';
    public const DIESEL = 'DIE';
    public const ELECTRIC = 'ELE';

    protected static $choices = [
        self::GASOLINE => 'Essence',
        self::DIESEL => 'Diesel',
        self::ELECTRIC => 'Électrique',
    ];

    protected static $string = [
        self::GASOLINE => 'Essence',
        self::DIESEL => 'Diesel',
        self::ELECTRIC => 'Électrique',
    ];
}