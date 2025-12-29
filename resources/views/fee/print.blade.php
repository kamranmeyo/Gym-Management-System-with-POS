<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Fee Slip</title>
    <style>
        body {
            font-family: monospace;
            width: 280px;
            margin: auto;
        }
        .center { text-align: center; }
        .line { border-bottom: 1px dashed #000; margin: 6px 0; }
        table { width: 100%; }
        td { padding: 2px 0; }
    </style>
</head>
<body>

<div class="center">
    <h3>C4 Fitness Gym</h3>
    <p>Fee Receipt</p>
</div>

<div class="line"></div>

<table>
    <tr>
        <td>Name:</td>
        <td>{{ $member->name }}</td>
    </tr>
    <tr>
        <td>Phone:</td>
        <td>{{ $member->phone }}</td>
    </tr>
    <tr>
        <td>Plan:</td>
        <td>{{ $member->membership_type }}</td>
    </tr>
    <tr>
        <td>Rs.</td>
        <td>{{ $member->fee }}</td>
    </tr>
</table>

<div class="line"></div>

<table>
    <tr>
        <td>Fee Date:</td>
        <td>{{ $member->last_fee_date }}</td>
    </tr>
    <tr>
        <td>Next Due:</td>
        <td>{{ $member->next_fee_due }}</td>
    </tr>
</table>

<div class="line"></div>

<p class="center">
    Thank You 🙏<br>
    Software By: 0301-6228258
</p>

<script>
    window.print();
</script>

</body>
</html>
