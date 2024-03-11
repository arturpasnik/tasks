<?php

namespace App\Enums;

enum TaskStatus: int
{
    case Open = 1;
    case Closed = 2;
}
