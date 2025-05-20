<?php

use PHPUnit\Framework\TestCase;

require_once "./UserProcessor.php";
// require_once "./UserProcessorSolution.php";

// use function solution\processUser;
use function default\processUser;

class UserProcessorTest extends TestCase
{
    private function captureOutput($user) : string
    {
        return processUser($user);
    }

    public function testActiveAdminRecentLogin()
    {
        $name = uniqid();
        $output = $this->captureOutput([
            'is_active' => true,
            'role' => 'admin',
            'last_login' => date('Y-m-d', strtotime('-5 days')),
            'name' => $name
        ]);
        $this->assertEquals("Welcome, $name. You have full access.", $output);
    }

    public function testManagerLoginStale()
    {
        $output = $this->captureOutput([
            'is_active' => true,
            'role' => 'manager',
            'last_login' => date('Y-m-d', strtotime('-60 days')),
            'name' => 'Bob'
        ]);
        $this->assertEquals("Your access is temporarily limited. Please log in soon.", $output);
    }

    public function testNoLoginRecord()
    {
        $output = $this->captureOutput([
            'is_active' => true,
            'role' => 'admin',
            'last_login' => null,
            'name' => 'Noob'
        ]);
        $this->assertEquals("Please complete your first login.", $output);
    }

    public function testGuestWithSignup()
    {
        $name = uniqid();
        $output = $this->captureOutput([
            'is_active' => true,
            'role' => 'guest',
            'signup_date' => '2024-01-01',
            'name' => $name,
            'last_login' => strtotime('-30 days')
        ]);
        $this->assertEquals("Welcome, $name. Signed up on 2024-01-01.", $output);
    }

    public function testNotificationFlagIsIgnored()
    {
        $name = uniqid();
        $output = $this->captureOutput([
            'is_active' => true,
            'role' => 'admin',
            'last_login' => date('Y-m-d', strtotime('-5 days')),
            'notification_enabled' => true, // should be ignored
            'name' => $name
        ]);

        $this->assertStringContainsString("Welcome, $name.", $output);

        $this->assertStringNotContainsString("You have notifications turned", strtolower($output));
    }


    public function testGuestNoSignup()
    {
        $output = $this->captureOutput([
            'is_active' => true,
            'role' => 'guest',
            'name' => 'G2',
            'last_login' => strtotime('-30 days')
        ]);
        $this->assertEquals("Welcome, guest. Signed up on unknown date.", $output);
    }

    public function testUnknownRole()
    {
        $output = $this->captureOutput([
            'is_active' => true,
            'role' => 'pirate',
            'name' => 'Blackbeard',
            'last_login' => strtotime('-30 days')
        ]);
        $this->assertEquals("Access denied. Role not recognized.", $output);
    }

    public function testInactiveUser()
    {
        $output = $this->captureOutput([
            'is_active' => false,
            'role' => 'admin',
            'name' => 'Carol'
        ]);
        $this->assertEquals("Your account is not active.", $output);
    }

    public function testNullUser()
    {
        $output = $this->captureOutput(null);
        $this->assertEquals("No user data provided.", $output);
    }
}
