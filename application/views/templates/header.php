<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title . ' - Latis Siswa' : 'Latis Siswa' ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.13.11/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        body { background-color: #f4f6f9; }
        .sidebar {
            min-height: 100vh;
            background-color: #1f2937;
            width: 220px;
        }
        .sidebar a {
            color: #d1d5db;
            display: block;
            padding: 12px 20px;
            text-decoration: none;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: #374151;
            color: #fff;
        }
        .sidebar .brand {
            color: #fff;
            font-weight: bold;
            padding: 18px 20px;
            font-size: 18px;
            border-bottom: 1px solid #374151;
        }
        .main-content {
            flex: 1;
            padding: 24px;
        }
        .wrapper { display: flex; }
        .card { border: none; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
        .foto-thumb { width: 40px; height: 40px; object-fit: cover; border-radius: 4px; }
    </style>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>
<div class="wrapper">
