<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Alteração de status {{ config('app.name') }}</title>
</head>
<body>
  <p style="font-family: 'segoe ui'">Olá {{ $order->user->name ?? 'usuário' }},</p>
  <p style="font-family: 'segoe ui'">Estamos entrando em contato para informar que o status da sua ordem de serviço #{{ $order->id ?? '0000' }} foi atualizado.</p>
  <p style="font-family: 'segoe ui'">Status anterior: <b>{{ $oldStatus }}</b></p>
  <p style="font-family: 'segoe ui'">Novo status: <b>{{ $newStatus }}</b></p>
  <p style="font-family: 'segoe ui'">Se você não reconhece essa alteração, por favor entre em contato com nosso suporte.</p>
  <p style="font-family: 'segoe ui'">Atenciosamente,  </p>
  <p style="font-family: 'segoe ui'">Equipe {{ config('app.name') }}</p>
</body>
</html>

