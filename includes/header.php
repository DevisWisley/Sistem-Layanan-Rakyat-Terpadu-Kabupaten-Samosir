<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Layanan Rakyat Terpadu - Kabupaten Samosir</title>

    <!-- ✅ CDN CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', sans-serif;
        }

        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: #0d6efd;
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 70px;
        }

        .sidebar a {
            color: #fff;
            display: block;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 5px;
        }

        .sidebar a:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .content {
            margin-left: 250px;
            padding: 20px;
        }
    </style>
</head>

<body>