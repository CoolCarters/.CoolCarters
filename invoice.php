<?php
// Setting dynamic date and bill number
$date = "29th March, 2025";
$bill_no = "001";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CoolCarters Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex: 1;
            padding: 20px;
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .bill-to, .invoice-details {
            font-size: 14px;
        }
        .invoice-details {
            text-align: right;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 16px;
        }
        th, td {
            border: 1px solid #000;
            padding: 16px 12px;
            text-align: left;
            height: 60px;
        }
        th {
            background-color: #f0f0f0;
            font-size: 17px;
        }
        .total-section {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            
        }
        
        .total-section span {
            background-color: #e0e0e0;
            padding: 10px;
            margin-right: 10px;
            width: 150px;

        }
        .confirm-btn {
            background-color: #e0e0e0;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <?php include "homeNavbar.php"; ?>
    <main>
        <div class="invoice-header">
            <div class="bill-to">
                <p><strong>Bill to:</strong></p>
                <p>Company Name</p>
                <p>Address</p>
            </div>
            <div class="invoice-details">
                <p><strong>Location:</strong> Thapathali, TBC, Trade Tower</p>
                <p><strong>Bill No:</strong> <?php echo $bill_no; ?></p>
                <p><strong>Date:</strong> <?php echo $date; ?></p>
            </div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Delivery Charge</th>
                    <th>TOTAL</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <div class="total-section">
            <span class="ayesha">TOTAL:</span>
            <button class="confirm-btn" onclick="confirmInvoice()">Confirm</button>
        </div>
    </main>
    <?php include "footer.php"; ?>

    <script>
        function confirmInvoice() {
            alert("Invoice confirmed!");
        }
    </script>
</body>
</html>