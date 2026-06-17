<?php

namespace App\Enums;

enum AnalyseStatusEnum: string
{
    case Pending = 'pending';
    case Completed = 'completed';
    case Failed = 'failed';
}
