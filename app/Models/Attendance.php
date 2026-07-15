<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
 

    $table->foreignId('employee_id');

    $table->date('attendance_date');

    $table->enum(
        'status',
        ['Present','Late','Absent','Leave']
    );

    $table->timestamps();
});
}
