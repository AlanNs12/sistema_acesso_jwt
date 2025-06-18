# Sistema de Controle de Funcionários, Veículos e Encomendas

Este sistema web tem como objetivo gerenciar entradas e saídas de funcionários, veículos e encomendas dentro de uma organização. Ele foi desenvolvido com foco em controle, rastreabilidade e facilidade de uso para porteiros, administradores e colaboradores.

## 📌 Funcionalidades

### 👷‍♂️ Funcionários
- Cadastro de funcionários com nome, cargo e foto.
- Registro de entrada e saída com data e hora.
- Campo de observações editável após o registro.
- Aviso de registros com entrada sem saída.
- Exportação para Excel.
- Controle de status ativo/inativo.

### 🚗 Veículos
- Cadastro de veículos com nome e placa.
- Registro de saída e retorno com condutor, data, hora e observações.
- Controle para impedir nova saída sem retorno anterior.
- Exibição de veículos desativados.
- Exportação de registros.
- Relatórios organizados.

### 📦 Encomendas
- Registro de encomendas recebidas com destinatário.
- Marcação de data e hora da entrega na portaria.
- Marcação da entrega interna (quem recebeu, data e hora).
- Acompanhamento completo pelo painel.

---

## 🗄️ Estrutura do Banco de Dados (PostgreSQL)

```sql
-- Tabela de funcionários
CREATE TABLE funcionarios (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cargo VARCHAR(100),
    foto VARCHAR(255),
    ativo BOOLEAN DEFAULT TRUE
);

-- Registros de entrada e saída de funcionários
CREATE TABLE registros_funcionarios (
    id SERIAL PRIMARY KEY,
    funcionario_id INTEGER REFERENCES funcionarios(id),
    data DATE NOT NULL,
    hora_entrada TIME,
    hora_saida TIME,
    observacoes TEXT
);

-- Usuários do sistema (login/admin)
CREATE TABLE usuarios (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha TEXT NOT NULL,
    admin BOOLEAN DEFAULT FALSE
);

-- Cadastro de veículos
CREATE TABLE veiculos (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    placa VARCHAR(20) UNIQUE NOT NULL,
    ativo BOOLEAN DEFAULT TRUE
);

-- Registros de movimentação de veículos
CREATE TABLE registros_veiculos (
    id SERIAL PRIMARY KEY,
    veiculo_id INTEGER REFERENCES veiculos(id),
    data_saida DATE,
    hora_saida TIME,
    condutor VARCHAR(100),
    data_retorno DATE,
    hora_retorno TIME,
    observacoes TEXT
);


-- Registro de encomendas (opcional)
CREATE TABLE encomendas (
    id SERIAL PRIMARY KEY,
    destinatario VARCHAR(100) NOT NULL,
    data_recebimento DATE,
    hora_recebimento TIME,
    entregue BOOLEAN DEFAULT FALSE,
    data_entrega DATE,
    hora_entrega TIME,
    recebido_por VARCHAR(100)
);
```sql

# Tecnologias Utilizadas
Backend: PHP 7+, PDO para conexão com banco de dados

Frontend: HTML5, CSS3, Bootstrap 4, JavaScript

Banco de Dados: PostgreSQL

Exportação: Geração de planilhas Excel via Content-Disposition

Segurança:

Controle de sessão e autenticação.

Painel com permissões para administradores.
