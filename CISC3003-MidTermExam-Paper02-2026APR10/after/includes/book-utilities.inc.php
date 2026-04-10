<?php
function cleanField($value) {
    return trim((string)$value);
}

function readCustomers($filename) {
    $customers = [];

    if (!is_readable($filename)) {
        return $customers;
    }

    $rows = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($rows as $row) {
        $parts = array_map('cleanField', explode(';', $row));

        if (count($parts) < 12) {
            continue;
        }

        $id = $parts[0];

        $customers[$id] = [
            'id' => $id,
            'first_name' => $parts[1],
            'last_name' => $parts[2],
            'email' => $parts[3],
            'university' => $parts[4],
            'address' => $parts[5],
            'city' => $parts[6],
            'state' => $parts[7],
            'country' => $parts[8],
            'zip' => $parts[9],
            'phone' => $parts[10],
            'sales' => preg_replace('/\s+/', '', $parts[11])
        ];
    }

    return $customers;
}

function readOrders($customer, $filename) {
    $orders = [];

    if (!is_readable($filename)) {
        return $orders;
    }

    $rows = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($rows as $row) {
        $parts = str_getcsv($row);
        $parts = array_map('cleanField', $parts);

        if (count($parts) < 5) {
            continue;
        }

        if ($parts[1] !== (string)$customer) {
            continue;
        }

        $orders[] = [
            'order_id' => $parts[0],
            'customer_id' => $parts[1],
            'isbn' => $parts[2],
            'title' => $parts[3],
            'category' => $parts[4]
        ];
    }

    return $orders;
}
?>
