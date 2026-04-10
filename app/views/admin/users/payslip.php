<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay Slip - <?php echo $data['user']->name; ?> - <?php echo $data['current_month']; ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Inter', sans-serif; color: #333; }
        .payslip-container { background: white; padding: 50px; margin: 30px auto; max-width: 800px; box-shadow: 0 0 20px rgba(0,0,0,0.1); }
        .header { border-bottom: 2px solid #6366f1; padding-bottom: 20px; margin-bottom: 30px; }
        .company-name { color: #6366f1; font-weight: 800; font-size: 1.5rem; letter-spacing: -0.5px; }
        .section-title { background: #f1f5f9; padding: 5px 15px; font-weight: 700; font-size: 0.9rem; text-transform: uppercase; margin-bottom: 15px; border-left: 4px solid #6366f1; }
        .table-salary th { background: #f8fafc; border-top: none; }
        .net-pay-box { background: #6366f1; color: white; padding: 20px; border-radius: 8px; margin-top: 30px; }
        .footer { margin-top: 50px; font-size: 0.8rem; color: #64748b; border-top: 1px solid #e2e8f0; padding-top: 20px; }
        @media print {
            body { background: white; }
            .payslip-container { box-shadow: none; margin: 0 auto; width: 100%; max-width: 100%; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

<div class="container no-print mt-3 text-center">
    <button onclick="window.print()" class="btn btn-primary px-4"><i class="fas fa-print mr-2"></i> Print Pay Slip</button>
    <a href="<?php echo URLROOT; ?>/adminUsers/details/<?php echo $data['user']->id; ?>" class="btn btn-outline-secondary px-4 ml-2">Back to Profile</a>
</div>

<div class="payslip-container">
    <div class="header d-flex justify-content-between align-items-center">
        <div>
            <div class="company-name">SERVICE MANAGEMENT SYSTEM</div>
            <div class="text-muted small">Professional Maintenance & Support Solutions</div>
        </div>
        <div class="text-right">
            <h4 class="font-weight-bold mb-0">PAY SLIP</h4>
            <div class="text-muted"><?php echo $data['current_month']; ?></div>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-6">
            <div class="section-title">Employee Details</div>
            <table class="table table-sm table-borderless">
                <tr><td class="text-muted font-weight-bold" width="40%">ID:</td><td><?php echo $data['user']->employee_id ?: 'N/A'; ?></td></tr>
                <tr><td class="text-muted font-weight-bold">Name:</td><td><?php echo $data['user']->name; ?></td></tr>
                <tr><td class="text-muted font-weight-bold">Designation:</td><td><?php echo $data['profile']->designation ?: 'Staff'; ?></td></tr>
                <tr><td class="text-muted font-weight-bold">Department:</td><td>Technical</td></tr>
            </table>
        </div>
        <div class="col-6">
            <div class="section-title">Bank Details</div>
            <table class="table table-sm table-borderless">
                <tr><td class="text-muted font-weight-bold" width="40%">Bank:</td><td><?php echo $data['profile']->bank_name ?: 'N/A'; ?></td></tr>
                <tr><td class="text-muted font-weight-bold">A/C No:</td><td><?php echo $data['profile']->account_no ?: 'N/A'; ?></td></tr>
                <tr><td class="text-muted font-weight-bold">IFSC:</td><td><?php echo $data['profile']->ifsc_code ?: 'N/A'; ?></td></tr>
                <tr><td class="text-muted font-weight-bold">PAN:</td><td class="text-uppercase"><?php echo $data['profile']->pan_no ?: 'N/A'; ?></td></tr>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <div class="section-title">Earnings</div>
            <table class="table table-salary">
                <thead><tr><th>Description</th><th class="text-right">Amount</th></tr></thead>
                <tbody>
                    <tr><td>Basic Salary</td><td class="text-right">₹<?php echo number_format($data['profile']->basic_salary, 2); ?></td></tr>
                    <tr><td>HRA Allowance</td><td class="text-right">₹<?php echo number_format($data['profile']->hra_allowance, 2); ?></td></tr>
                    <tr><td>Travel Allowance</td><td class="text-right">₹<?php echo number_format($data['profile']->travel_allowance, 2); ?></td></tr>
                    <tr><td>Other Allowances</td><td class="text-right">₹<?php echo number_format($data['profile']->other_allowances, 2); ?></td></tr>
                    <tr class="font-weight-bold"><td>Total Earnings</td><td class="text-right">₹<?php echo number_format($data['profile']->basic_salary + $data['total_allowances'], 2); ?></td></tr>
                </tbody>
            </table>
        </div>
        <div class="col-6">
            <div class="section-title">Deductions</div>
            <table class="table table-salary">
                <thead><tr><th>Description</th><th class="text-right">Amount</th></tr></thead>
                <tbody>
                    <tr><td>TDS</td><td class="text-right">₹<?php echo number_format($data['profile']->tds_deduction, 2); ?></td></tr>
                    <tr><td>Provident Fund (PF)</td><td class="text-right">₹<?php echo number_format($data['profile']->pf_deduction, 2); ?></td></tr>
                    <tr class="invisible"><td>-</td><td class="text-right">0</td></tr>
                    <tr class="invisible"><td>-</td><td class="text-right">0</td></tr>
                    <tr class="font-weight-bold"><td>Total Deductions</td><td class="text-right">₹<?php echo number_format($data['total_deductions'], 2); ?></td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="net-pay-box d-flex justify-content-between align-items-center">
        <div>
            <div class="text-uppercase small font-weight-bold" style="opacity: 0.8;">Net Payable</div>
            <h2 class="font-weight-bold m-0">₹<?php echo number_format($data['net_salary'], 2); ?></h2>
        </div>
        <div class="text-right">
            <div class="small italic font-weight-bold">Amount in words:</div>
            <div class="small">Rupees <?php echo ucwords($data['net_salary']); ?> only.</div>
        </div>
    </div>

    <div class="row mt-5 pt-5 text-center">
        <div class="col-6">
            <hr class="mx-5">
            <small>Employer Signature</small>
        </div>
        <div class="col-6">
            <hr class="mx-5">
            <small>Employee Signature</small>
        </div>
    </div>

    <div class="footer text-center">
        <p class="mb-0">This is a computer-generated document and does not require a physical stamp unless specified.</p>
        <p>© <?php echo date('Y'); ?> Service Management System. Support: admin@sms.com</p>
    </div>
</div>

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
