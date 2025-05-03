<nav class="navbar navbar-expand-lg navbar-light bg-dark ">
    <a class="navbar-brand text-light" href="#">
        <img src="/sistema_acesso_jwt/images/logo-circle.png" width="30" height="30" class="d-inline-block align-top" alt="">
        Embassy Of the Philippines
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Alterna navegação">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse " id="navbarNavDropdown">
      <ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link active" href="dashboard.php">Home</a>
  </li>
  <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Options (Opções)</a>
    <div class="dropdown-menu">
      <a class="dropdown-item" href="/sistema_acesso_jwt/funcionarios/listar_registros.php">List of records(Consulta de Registros)</a>
      <a class="dropdown-item" href="/sistema_acesso_jwt/funcionarios/listar_funcionarios.php">List of Employee (Lista Funcionários)</a>
      <a class="dropdown-item" href="/sistema_acesso_jwt/funcionarios/registro_hora.php">New register (Novo Registro)</a>
      <div class="dropdown-divider"></div>
      <a class="dropdown-item" href="/sistema_acesso_jwt/funcionarios/cadastro_funcionario.php">Register new employee (Cadastrar novo funcionário)</a>
    </div>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="#">Link</a>
  </li>
  <li class="nav-item">
    <a class="nav-link disabled" href="#">Desativado</a>
  </li>
</ul>

          <ul class="navbar-nav">
              <li class="nav-item dropdown">
              <a href="/sistema_acesso_jwt/dashboard.php" class="ml-auto p-2 bd-highlight"><button  class="btn btn-primary my-2 my-sm-0" type="submit">Home</button></a>
              </li>
              
              <li class="nav-item dropdown">
            <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Options (Opções)</button>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink my-lg-0">
          <a href="/sistema_acesso_jwt/funcionarios/listar_registros.php" class="dropdown-item">List of records(Consulta de Registros)</a>
          <a href="/sistema_acesso_jwt/funcionarios/listar_funcionarios.php" class="dropdown-item">List of Employee (Lista Funcionários)</a>
          <a href="/sistema_acesso_jwt/funcionarios/registro_hora.php" class="dropdown-item">New register (Novo Registro)</a>
          <a href="/sistema_acesso_jwt/funcionarios/cadastro_funcionario.php" class="dropdown-item">Register new employee (Cadastrar novo funcionário)</a>
          <a href="/sistema_acesso_jwt/usuarios.php" class="dropdown-item">Register new admin (Cadastrar novo admin)</a>
          </div>
          </li>

          </ul>
          
          <a href="/sistema_acesso_jwt/logout.php" class="ml-auto p-2 bd-highlight"><button class="btn btn-danger my-2 my-sm-0" type="submit"> Logout (Sair)</button></a>
        
</nav>


    