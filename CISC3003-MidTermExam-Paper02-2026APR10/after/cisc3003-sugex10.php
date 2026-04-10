<?php
include 'includes/book-utilities.inc.php';

$customers = readCustomers('data/customers.txt');
$selectedId = isset($_GET['customer_id']) ? trim($_GET['customer_id']) : '';
$selectedCustomer = '';
$selectedOrders = [];

if ($selectedId !== '' && isset($customers[$selectedId])) {
    $selectedCustomer = $customers[$selectedId];
    $selectedOrders = readOrders($selectedId, 'data/orders.txt');
}

function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function customerName($customer) {
    return $customer['first_name'] . ' ' . $customer['last_name'];
}

function customerAddress($customer) {
    $parts = [
        $customer['address'],
        $customer['city'],
        $customer['state'],
        $customer['country'],
        $customer['zip']
    ];

    $parts = array_filter($parts, function ($item) {
        return trim((string)$item) !== '';
    });

    return implode(', ', $parts);
}

function coverUrl($isbn) {
    return 'https://covers.openlibrary.org/b/isbn/' . rawurlencode($isbn) . '-S.jpg?default=false';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>dc228400 Wang Yufeng</title>
    <link rel="icon" type="image/png" href="images/favicon.png">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="css/material.min.css">
    <link rel="stylesheet" href="css/demo-styles.css">
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script src="js/material.min.js"></script>
    <script src="js/jquery.sparkline.2.1.2.js"></script>
</head>
<body>
<div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
    <?php include 'includes/header.inc.php'; ?>
    <?php include 'includes/left-nav.inc.php'; ?>

    <main class="mdl-layout__content mdl-color--grey-100">
        <section class="page-content">
            <div class="mdl-grid">
                <div class="mdl-cell mdl-cell--7-col card-lesson mdl-card mdl-shadow--2dp">
                    <div class="mdl-card__title mdl-color--orange">
                        <h2 class="mdl-card__title-text">Customers</h2>
                    </div>
                    <div class="mdl-card__supporting-text">
                        <table class="mdl-data-table mdl-shadow--2dp">
                            <thead>
                                <tr>
                                    <th class="mdl-data-table__cell--non-numeric">Name</th>
                                    <th class="mdl-data-table__cell--non-numeric">University</th>
                                    <th class="mdl-data-table__cell--non-numeric">City</th>
                                    <th>Sales</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($customers as $customer): ?>
                                    <?php $isActive = $selectedId === $customer['id']; ?>
                                    <tr>
                                        <td class="mdl-data-table__cell--non-numeric">
                                            <a class="customer-link<?php echo $isActive ? ' active' : ''; ?>" href="cisc3003-sugex10.php?customer_id=<?php echo urlencode($customer['id']); ?>"><?php echo h(customerName($customer)); ?></a>
                                        </td>
                                        <td class="mdl-data-table__cell--non-numeric"><?php echo h($customer['university']); ?></td>
                                        <td class="mdl-data-table__cell--non-numeric"><?php echo h($customer['city']); ?></td>
                                        <td><span class="inlinesparkline"><?php echo h($customer['sales']); ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mdl-grid mdl-cell--5-col">
                    <div class="mdl-cell mdl-cell--12-col card-lesson mdl-card mdl-shadow--2dp">
                        <div class="mdl-card__title mdl-color--deep-purple mdl-color-text--white">
                            <h2 class="mdl-card__title-text">Customer Details</h2>
                        </div>
                        <div class="mdl-card__supporting-text">
                            <?php if ($selectedCustomer): ?>
                                <h4><?php echo h(customerName($selectedCustomer)); ?></h4>
                                <p><span class="detail-label">Email:</span> <?php echo h($selectedCustomer['email']); ?></p>
                                <p><span class="detail-label">University:</span> <?php echo h($selectedCustomer['university']); ?></p>
                                <p><span class="detail-label">Address:</span> <?php echo h(customerAddress($selectedCustomer)); ?></p>
                                <p><span class="detail-label">Phone:</span> <?php echo h($selectedCustomer['phone']); ?></p>
                            <?php else: ?>
                                <p class="empty-msg">Select a customer to view details.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mdl-cell mdl-cell--12-col card-lesson mdl-card mdl-shadow--2dp">
                        <div class="mdl-card__title mdl-color--deep-purple mdl-color-text--white">
                            <h2 class="mdl-card__title-text">Order Details</h2>
                        </div>
                        <div class="mdl-card__supporting-text">
                            <table class="mdl-data-table mdl-shadow--2dp">
                                <thead>
                                    <tr>
                                        <th class="mdl-data-table__cell--non-numeric">Cover</th>
                                        <th class="mdl-data-table__cell--non-numeric">ISBN</th>
                                        <th class="mdl-data-table__cell--non-numeric">Title</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($selectedCustomer && count($selectedOrders) > 0): ?>
                                        <?php foreach ($selectedOrders as $order): ?>
                                            <tr>
                                                <td class="mdl-data-table__cell--non-numeric"><img class="order-cover" src="<?php echo h(coverUrl($order['isbn'])); ?>" alt="<?php echo h($order['title']); ?> cover" onerror="this.onerror=null;this.src='images/favicon.png';"></td>
                                                <td class="mdl-data-table__cell--non-numeric"><?php echo h($order['isbn']); ?></td>
                                                <td class="mdl-data-table__cell--non-numeric"><?php echo h($order['title']); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php elseif ($selectedCustomer): ?>
                                        <tr>
                                            <td colspan="3" class="mdl-data-table__cell--non-numeric empty-msg">No orders for this customer</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="exam-footer">CISC3003 Web Programming: dc228400 Wang Yufeng 2026</footer>
        </section>
    </main>
</div>

<script>
$(function () {
    $('.inlinesparkline').sparkline('html', {
        type: 'bar',
        barColor: '#3366cc',
        height: '26px',
        barWidth: 4,
        barSpacing: 1,
        chartRangeMin: 0
    });
});
</script>
</body>
</html>
