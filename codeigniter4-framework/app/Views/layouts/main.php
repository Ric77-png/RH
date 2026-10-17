<!doctype html>
<html lang="fr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>GESTION-RH</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body class="bg-light">
<nav class="navbar navbar-expand bg-white border-bottom mb-4"><div class="container">
  <a class="navbar-brand" href="/">GESTION-RH</a>
  <div class="ms-auto d-flex gap-3 align-items-center">
  <?php if (session('user')): ?>
    <a href="/dashboard"><?= esc(session('user.name')) ?> (<?= esc(session('user.role')) ?>)</a>
    <a class="btn btn-sm btn-outline-secondary" href="/logout">Se déconnecter</a>
  <?php else: ?><a class="btn btn-sm btn-primary" href="/login">Espace employés</a><?php endif ?>
  </div></div></nav>
<main class="container pb-5">
  <?php if (session('success')): ?><div class="alert alert-success"><?= esc(session('success')) ?></div><?php endif ?>
  <?php if (session('error')): ?><div class="alert alert-danger"><?= esc(session('error')) ?></div><?php endif ?>
  <?php if (session('errors')): ?><div class="alert alert-danger"><?php foreach ((array) session('errors') as $e): ?><div><?= esc($e) ?></div><?php endforeach ?></div><?php endif ?>
  <?= $this->renderSection('content') ?>
</main></body></html>
