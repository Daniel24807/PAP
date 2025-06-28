<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo "Confirmação de E-mail"; ?></title>
    <style>
        body {
            background-color: #fff;
            color: #fff;
            align-items: center;
        }

        .container {
            text-align: center;
            padding: 20px;
            border: 2px solid blue;
            border-radius: 10px;
            background-color: #111;
            max-width: 500px;
            margin-top: 22px;
        }

        h3 {
            color: #fff;
        }

        p {
            color: #ccc;
        }

        a {
            color: blue;
            text-decoration: none;
            font-weight: bold;
            font-size: 15px;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <h3>Conta Confirmada com Sucesso</h3>
        <p>A sua conta foi verificada com sucesso!</p>
        <p>Seja bem-vindo à nossa loja</p>
        <p>Obrigado pela preferência</p>
        <div><a href="?a=login" class="btn">Login</a></div>
    </div>
</body>

</html>
