<?php
/**
 * File: AppointmentStatus.php
 * Description: Enum to handle operations
 *
 * Created By: Vivek Singh
 * Created At: 2025-10-08
 * Updated By: Vivek Singh
 * Updated At: 2025-10-08
 *
 * @package App\Enums
 * @author Vivek Singh
 */

namespace App\Enums;

enum AppointmentStatus: string
{
    case BOOKED = 'booked';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
