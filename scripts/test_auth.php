<?php
session_start();

require __DIR__ . '/../App/functions.php';
require __DIR__ . '/../App/Models/Customer.php';
require __DIR__ . '/../App/Models/PDOFactory.php';
require __DIR__ . '/../App/SessionGuard.php';

$config = [
    'db_host' => 'localhost',
    'db_port' => '5432',
    'db_name' => 'ct275_project',
    'db_user' => 'postgres',
    'db_pass' => 'password',
];

try {
    $pdo = (new App\Models\PDOFactory())->create($config);
    $customerModel = new App\Models\Customer($pdo);
    $guard = new App\SessionGuard();

    // Test: get customer by email
    $customer = $customerModel->findByEmail('khach@example.com');
    if ($customer) {
        echo "✓ Found customer: " . $customer->kh_ten . " (ID: " . $customer->kh_ma . ")\n";
        
        // Test: login
        $credentials = ['password' => 'password123'];
        $loginResult = $guard->login($customer, $credentials);
        echo "✓ Login result: " . ($loginResult ? 'SUCCESS' : 'FAILED') . "\n";
        echo "  Session customer_id: " . ($_SESSION['customer_id'] ?? 'NOT SET') . "\n";
        
        // Test: isCustomerLoggedIn
        echo "✓ isCustomerLoggedIn: " . ($guard->isCustomerLoggedIn() ? 'YES' : 'NO') . "\n";
        
        // Test: customer()
        $retrievedCustomer = $guard->customer();
        if ($retrievedCustomer) {
            echo "✓ Retrieved customer from guard: " . $retrievedCustomer->kh_ten . "\n";
        } else {
            echo "✗ Failed to retrieve customer from guard\n";
        }
        
        // Test: logout
        $guard->logout();
        echo "✓ Called logout()\n";
        echo "  Session customer_id after logout: " . ($_SESSION['customer_id'] ?? 'NOT SET (GOOD)') . "\n";
        echo "  isCustomerLoggedIn after logout: " . ($guard->isCustomerLoggedIn() ? 'YES (BAD)' : 'NO (GOOD)') . "\n";
    } else {
        echo "✗ Customer not found in database\n";
    }
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
