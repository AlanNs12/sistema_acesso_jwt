<nav class="navbar navbar-expand-lg navbar-light bg-dark ">
    <a class="navbar-brand text-light" href="#">
        <img src="/sistema_acesso_jwt/images/logo-circle.png" width="30" height="30" class="d-inline-block align-top" alt="">
        Embassy Of the Philippines
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Alterna navegação">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse " id="navbarNavDropdown">
    
        <div class="d-flex bd-highlight">
          <ul class="navbar-nav">
          <li class="nav-item dropdown">
              <a href="/sistema_acesso_jwt/dashboard.php" class=""><button  class="btn btn-outline-primary" type="submit"> Logout (Sair)</button></a>
              </li>
            <li class="nav-item active">
            <a href="/sistema_acesso_jwt/dashboard.php" class="btn btn-outline-primary my-2 my-sm-0 p-2 bd-highlight">Home</a>
            </li>
            <li class="nav-item dropdown">
          <a class=" dropdown-toggle btn btn-outline-warning my-2 my-sm-0 p-2 bd-highlight" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Options (Opções)
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink my-lg-0">
          <a href="/sistema_acesso_jwt/funcionarios/listar_registros.php" class="dropdown-item">List of records(Consulta de Registros)</a>
          <a href="/sistema_acesso_jwt/funcionarios/listar_funcionarios.php" class="dropdown-item">List of Employee (Lista Funcionários)</a>
          <a href="/sistema_acesso_jwt/funcionarios/registro_hora.php" class="dropdown-item">New register (Novo Registro)</a>
          </div>
                </li>
                <li class="nav-item dropdown">
              <a href="/sistema_acesso_jwt/logout.php" class="ml-auto p-2 bd-highlight"><button  class="btn btn-outline-danger my-2 my-sm-0" type="submit"> Logout (Sair)</button></a>
              </li>
          
          </ul>
        </div>
      </div>
    </nav>