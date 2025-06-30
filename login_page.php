<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Controle de Acesso</title>
    <link rel="stylesheet" href="styles/lumen_bootstrap.min.css">
</head>
<body class="text-center">
        <form class="form-signin" action="login.php" method="POST">
            <img class="mb-4" src="images/logo-dfa.png" alt="" width="120" height="120">
            <h1 class="h3 mb-3 font-weight-normal">Sistema de controle </h1>
            <?php
            session_start();
            if (isset($_SESSION['login_erro'])) {
                echo '<div class="alert alert-danger">' . $_SESSION['login_erro'] . '</div>';
                unset($_SESSION['login_erro']); // Limpa a mensagem de erro da sessão
            }
            ?>
            <label class="sr-only">Email</label>
            <input type="email" name="email" class="form-control" placeholder="Endereço de e-mail" required="">
            <label class="sr-only">Password</label>
            <input type="password" name="senha" class="form-control" placeholder="Senha" required="">
            <div class="checkbox mb-3">
        
            </div>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Entrar</button>
            <p class="mt-5 mb-3 text-muted">© Embassy Philippines</p>
          </form>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
</body>
</html>
