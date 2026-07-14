# Controle de Recipientes — ADX (Desafio PHP)

Sistema de controle de entrada e saída de recipientes térmicos utilizados no transporte de alimentos, desenvolvido em **CodeIgniter 3** para o processo seletivo de Desenvolvedor PHP Pleno da ADX.

## Stack

- **Backend**: PHP 8.1 + CodeIgniter 3.1.13
- **Banco de dados**: MariaDB 10.11
- **Front-end**: Views server-side do CI3 + Bootstrap 5
- **QR Code**: geração via [`endroid/qr-code`](https://github.com/endroid/qr-code) (servidor) e leitura via câmera com [`html5-qrcode`](https://github.com/mebjas/html5-qrcode) (cliente)
- **Testes**: PHPUnit 10
- **Infraestrutura**: 100% Docker (aplicação + banco), sem dependências instaladas na máquina host além do Docker

## Subindo o ambiente

```bash
docker compose up -d --build
```

Isso sobe três containers:

| Serviço | Descrição | URL |
|---|---|---|
| `app` | PHP 8.1 + Apache (aplicação) | http://localhost:8080 |
| `mysql` | MariaDB 10.11 | `localhost:3306` (host) / `mysql:3306` (rede interna) |
| `adminer` | Inspeção visual do banco (opcional) | http://localhost:8081 |

### Instalar dependências e rodar as migrations

Na primeira vez (ou após mudanças no `composer.json`/migrations):

```bash
# Dependências PHP (Composer) — se ainda não tiver rodado no host
docker exec adx_recipientes_app composer install

# Migrations + seed inicial (usuários, rotas, pontos de entrega, recipientes)
docker exec adx_recipientes_app php public/index.php cli migrate
```

Acesse **http://localhost:8080/login**.

## Usuários de teste (seed)

| Perfil | E-mail | Senha |
|---|---|---|
| Administrador | `admin@adx.com` | `admin123` |
| Operador | `operador1@adx.com` | `operador123` |
| Operador | `operador2@adx.com` | `operador123` |
| Motorista | `motorista1@adx.com` | `motorista123` |
| Motorista | `motorista2@adx.com` | `motorista123` |
| Motorista | `motorista3@adx.com` | `motorista123` |

O seed também cria 2 rotas (com pontos de entrega) e 20 recipientes (`REC-000001` a `REC-000020`) em estoque.

## Rodando os testes automatizados

```bash
docker exec adx_recipientes_app php vendor/bin/phpunit --testdox
```

Cobre a lógica transacional de `Saida_model` e `Entrada_model` (validação de estoque, duplicidade, recipiente inexistente, recálculo de status da saída). Cada teste roda dentro de uma transação com rollback automático — não deixa dados residuais no banco de desenvolvimento.

## API REST

Endpoints JSON autenticados via **HTTP Basic Auth**, usando as mesmas credenciais de um usuário já cadastrado (mesma tabela `usuarios`, mesmas regras de permissão/bloqueio).

Resposta padrão: `{"sucesso": bool, "dados": ..., "mensagem": "..."}`.

### `POST /api/saidas`

```bash
curl -u operador1@adx.com:operador123 \
  -X POST -H "Content-Type: application/json" \
  -d '{
        "motorista_id": 4,
        "rota_id": 1,
        "data_hora_saida": "2026-07-14 08:30:00",
        "observacoes": "Entrega da manha",
        "pontos": [
          {"ponto_entrega_id": 1, "recipientes": ["REC-000001", "REC-000002"]}
        ]
      }' \
  http://localhost:8080/api/saidas
```

### `POST /api/entradas`

```bash
curl -u operador1@adx.com:operador123 \
  -X POST -H "Content-Type: application/json" \
  -d '{
        "motorista_id": 4,
        "data_hora_entrada": "2026-07-14 17:00:00",
        "recipientes": ["REC-000001"]
      }' \
  http://localhost:8080/api/entradas
```

### Consultas

- `GET /api/recipientes` — lista o estoque (aceita `?status=estoque|em_uso|manutencao|inativo`)
- `GET /api/recipientes/{codigo}` — estado atual + histórico completo do recipiente
- `GET /api/saidas/{id}` / `GET /api/entradas/{id}` — detalhe de uma movimentação

## Estrutura do projeto

```
application/     Controllers, Models, Views, Migrations, config (fora do webroot)
system/          Core do CodeIgniter 3.1.13 (oficial, não modificado)
public/          Webroot (index.php, assets)
tests/           Testes PHPUnit (bootstrap standalone, sem servidor HTTP)
docker/app/      Dockerfile + vhost do Apache
docker-compose.yml
```

## Modelo de dados (resumo)

Histórico de movimentações é imutável (`saida_itens` / `entrada_itens`), e o estado atual de cada recipiente fica desnormalizado na própria tabela `recipientes` (atualizado sempre na mesma transação da saída/entrada), evitando reconstruir o histórico inteiro a cada consulta de estoque ou localização atual.

## Checklist do desafio

**Premissas gerais**
- [x] CodeIgniter 3
- [x] MySQL/MariaDB

**Cadastros**
- [x] Login para o ambiente administrador
- [x] Painel web com formulários de cadastro
- [x] Usuários administrador / operador / motorista
- [x] Permissões: administrador (usuários, bloqueio, relatórios), operador (entrada/saída de estoque), motorista (lista de destino e quantidade)
- [x] Cadastro de rotas com pontos de entrega editáveis
- [x] Cadastro de recipientes novos no estoque

**API**
- [x] Endpoints REST para simular saída de recipientes (endereçados, com motorista)
- [x] Endpoints REST para simular retorno de recipientes ao estoque
- [x] JSON + Basic Authentication

**Regras de negócio**
- [x] Saída registra motorista, data/hora, pontos de entrega, quantidade por ponto e identificação de cada recipiente
- [x] Entrada registra o retorno ao estoque e mantém o histórico completo

**Relatórios (com filtro de data DE/ATÉ)**
- [x] Relatório 01 — movimentação por local de destino (recipientes da saída, quantidade saída/retornado/em uso)
- [x] Relatório 02 — devoluções por motorista (recipientes da entrada e quantidade)

**Perguntas que o sistema responde**
- [x] Quantos recipientes estão em estoque? (Dashboard)
- [x] Onde está cada recipiente? (Detalhe do recipiente / listagem)
- [x] Qual motorista realizou a retirada? (Detalhe da saída / histórico do recipiente)
- [x] Quem registrou a devolução? (Detalhe da entrada / histórico do recipiente)
- [x] Histórico completo de um recipiente? (Tela de detalhe do recipiente)

**QR Code**
- [x] Identificador único do recipiente representado por QR Code (gerado sob demanda, referenciando o registro)
- [x] Leitura via câmera nos formulários de saída/entrada, com fallback de digitação manual

**Diferenciais aplicados**
- [x] Docker (stack completa: PHP + Apache + MariaDB)
- [x] Testes automatizados (PHPUnit) na lógica de negócio crítica
- [x] PHP 8

## Notas de decisões técnicas

- **PHP 8.1 via Docker** (não 8.4 do host): é a última versão oficialmente suportada pelo CodeIgniter 3.1.13, evitando ruído de deprecations/incompatibilidades e tornando a avaliação reproduzível com um único `docker compose up`.
- **Nenhuma tabela sofre DELETE físico** (apenas flags de status/ativo) para preservar a integridade do histórico de movimentações.
- **`Recipiente_model::recalcular_status()`** reconstrói o estado atual a partir do histórico imutável — útil como correção manual caso algo grave fora do fluxo normal altere o estado desnormalizado.
