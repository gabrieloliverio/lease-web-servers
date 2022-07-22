<?php

namespace App\Enums;

enum HardDiskTypeEnum : string
{
    case SAS = 'SAS';
    case SATA = 'SATA';
    case SATA2 = 'SATA2';
    case SSD = 'SSD';
}