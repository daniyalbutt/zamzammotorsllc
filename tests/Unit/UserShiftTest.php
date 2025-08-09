<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserShiftTest extends TestCase
{
    use RefreshDatabase;

    public function test_night_shift_handling()
    {
        // Create a night shift (9 PM to 6 AM)
        $nightShift = Shift::create([
            'name' => 'Night Shift',
            'start_time' => '21:00:00',
            'end_time' => '06:00:00',
            'status' => 1
        ]);

        // Create a user with the night shift
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Set the shift meta
        $user->setMeta('shift_id', $nightShift->id);

        // Test at 10 PM (within shift time)
        $this->travelTo(Carbon::parse('2024-01-15 22:00:00'));
        $this->assertTrue($user->isWithinShiftTime());
        
        $shiftWindow = $user->getCurrentShiftWindow();
        $this->assertEquals('2024-01-15 21:00:00', $shiftWindow['start']->toDateTimeString());
        $this->assertEquals('2024-01-16 06:00:00', $shiftWindow['end']->toDateTimeString());
        
        // Test at 2 AM (within shift time, but date should be previous day)
        $this->travelTo(Carbon::parse('2024-01-16 02:00:00'));
        $this->assertTrue($user->isWithinShiftTime());
        $this->assertEquals('2024-01-15', $user->getAttendanceDate());
        
        // Test at 8 AM (outside shift time)
        $this->travelTo(Carbon::parse('2024-01-16 08:00:00'));
        $this->assertFalse($user->isWithinShiftTime());
        
        // Test formatted shift time
        $this->assertEquals('9:00 PM - 6:00 AM (Next Day)', $user->getFormattedShiftTime());
        
        $this->travelBack();
    }

    public function test_day_shift_handling()
    {
        // Create a day shift (9 AM to 5 PM)
        $dayShift = Shift::create([
            'name' => 'Day Shift',
            'start_time' => '09:00:00',
            'end_time' => '17:00:00',
            'status' => 1
        ]);

        // Create a user with the day shift
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Set the shift meta
        $user->setMeta('shift_id', $dayShift->id);

        // Test at 10 AM (within shift time)
        $this->travelTo(Carbon::parse('2024-01-15 10:00:00'));
        $this->assertTrue($user->isWithinShiftTime());
        
        // Test at 8 PM (outside shift time)
        $this->travelTo(Carbon::parse('2024-01-15 20:00:00'));
        $this->assertFalse($user->isWithinShiftTime());
        
        // Test formatted shift time
        $this->assertEquals('9:00 AM - 5:00 PM', $user->getFormattedShiftTime());
        
        $this->travelBack();
    }
}
