<nav class="navbar navbar-expand-lg navbar-dark bg-primary">  <a class="navbar-brand" href="/sistema_acesso_jwt/dashboard.php">
    <img src="/sistema_acesso_jwt/images/logo-circle.png" width="30" height="30" class="d-inline-block align-top" alt="">
    Embassy Of the Philippines
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="/sistema_acesso_jwt/dashboard.php">Início <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownFuncionarios" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Funcionários
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownFuncionarios">
          <a class="dropdown-item" href="/sistema_acesso_jwt/funcionarios/registro_hora.php">Novo Registro</a>
          <a class="dropdown-item" href="/sistema_acesso_jwt/funcionarios/listar_registros.php">Consultar Registros</a>
          <a class="dropdown-item" href="/sistema_acesso_jwt/funcionarios/listar_funcionarios.php">Gerenciar Funcionários</a>
          <a class="dropdown-item" href="/sistema_acesso_jwt/funcionarios/cadastro_funcionario.php">Cadastrar Funcionário</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="/sistema_acesso_jwt/usuarios.php">Gerenciar Admins</a>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownVeiculos" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Veículos
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownVeiculos">
          <a class="dropdown-item" href="/sistema_acesso_jwt/veiculos/registro_veiculos.php">Novo Registro</a>
          <a class="dropdown-item" href="/sistema_acesso_jwt/veiculos/registros_veiculos_agrupados.php">Consultar Registros</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="/sistema_acesso_jwt/veiculos/gerenciar_veiculos.php">Gerenciar Veículos</a>
        </div>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <span class="navbar-text">
              Bem-vindo, <?= $_SESSION['usuario_nome'] ?>
            </span>
        </li>
        <li class="nav-item">
            <a class="nav-link btn btn-danger btn-sm ml-2 text-white" href="/sistema_acesso_jwt/logout.php">Sair</a>
        </li>
    </ul>
  </div>
</nav>