<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AttendanceController extends Controller
{
    public function clockIn(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|string|exists:employees,employee_id',
            'action' => 'nullable|in:clock_in,clock_out',
            'photo' => 'required|string',
        ]);

        $employeeCode = $request->input('employee_id');
        $employee = Employee::where('employee_id', $employeeCode)->first();
        $now = Carbon::now('Asia/Manila');
        $today = $now->toDateString();
        $action = $request->input('action', 'clock_in');

        $photoPath = $this->storeCompressedAttendancePhoto(
            $request->input('photo'),
            $employee->id,
            $action === 'clock_out' ? 'out' : 'in'
        );

        if (! $photoPath) {
            return back()
                ->with('error', 'Invalid or missing attendance photo. Please capture again.')
                ->withInput(['employee_id' => $employeeCode]);
        }

        if ($action === 'clock_out') {
            $attendance = Attendance::where('employee_id', $employee->id)
                ->where('attendance_date', $today)
                ->first();

            if (! $attendance || ! $attendance->time_in) {
                return back()->with('error', 'No clock-in found for today.');
            }

            if ($attendance->time_out) {
                return back()->with('error', 'This employee already clocked out today.');
            }

            $attendance->time_out = $now->format('H:i:s');
            $attendance->time_out_image = $photoPath;
            $attendance->save();

            return redirect()->route('clockinout')
                ->with('success', 'Clock out recorded for employee #' . $employeeCode)
                ->with('employee_id', $employeeCode)
                ->with('clock_in', $attendance->time_in)
                ->with('clock_out', $attendance->time_out)
                ->with('clocked_in', true)
                ->with('clocked_out', true)
                ->withInput(['employee_id' => $employeeCode]);
        }

        $attendance = Attendance::firstOrNew([
            'employee_id' => $employee->id,
            'attendance_date' => $today,
        ]);

        if ($attendance->exists && $attendance->time_in) {
            if ($attendance->time_out) {
                return back()->with('error', 'This employee already clocked out today.');
            }

            return back()->with('error', 'This employee already clocked in today.');
        }

        $attendance->time_in = $now->format('H:i:s');
        $attendance->time_in_image = $photoPath;
        $attendance->status = 'Present';
        $attendance->save();

        return redirect()->route('clockinout')
            ->with('success', 'Clock in recorded for employee #' . $employeeCode)
            ->with('employee_id', $employeeCode)
            ->with('clock_in', $attendance->time_in)
            ->with('clocked_in', true)
            ->withInput(['employee_id' => $employeeCode]);
    }

    /**
     * Save a compressed JPEG from a canvas data URL.
     * Max edge ~480px, quality ~55 for a small file size.
     */
    private function storeCompressedAttendancePhoto(string $dataUrl, int $employeeId, string $type): ?string
    {
        if (! preg_match('/^data:image\/(png|jpeg|jpg|webp);base64,/', $dataUrl, $matches)) {
            return null;
        }

        $binary = base64_decode(substr($dataUrl, strpos($dataUrl, ',') + 1), true);

        if ($binary === false || strlen($binary) < 100) {
            return null;
        }

        // Reject oversized payloads before processing (~2MB raw).
        if (strlen($binary) > 2_500_000) {
            return null;
        }

        $source = @imagecreatefromstring($binary);

        if (! $source) {
            return null;
        }

        $srcW = imagesx($source);
        $srcH = imagesy($source);
        $maxEdge = 480;

        $scale = min(1, $maxEdge / max($srcW, $srcH));
        $dstW = max(1, (int) round($srcW * $scale));
        $dstH = max(1, (int) round($srcH * $scale));

        $resized = imagecreatetruecolor($dstW, $dstH);
        imagecopyresampled($resized, $source, 0, 0, 0, 0, $dstW, $dstH, $srcW, $srcH);

        $directory = public_path('attendance_photos');
        File::ensureDirectoryExists($directory);

        $filename = sprintf(
            'emp%s_%s_%s_%s.jpg',
            $employeeId,
            $type,
            now('Asia/Manila')->format('Ymd_His'),
            Str::lower(Str::random(6))
        );

        $fullPath = $directory.DIRECTORY_SEPARATOR.$filename;
        imagejpeg($resized, $fullPath, 55);

        imagedestroy($source);
        imagedestroy($resized);

        return $filename;
    }
}
