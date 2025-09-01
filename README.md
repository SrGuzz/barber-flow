<img src="Artefatos/logo.png" alt="Descrição da Imagem" width="200" height="200" style="display: block; margin: auto;">

# Barber Flow
O **Barber Flow** é uma solução completa para o controle financeiro e operacional do seu estabelecimento. Com ele, você acompanha de forma detalhada o faturamento diário, semanal e mensal, além de visualizar os ganhos individuais de cada barbeiro. O software permite diferenciar cortes pagos avulsamente daqueles realizados por clientes que aderiram ao plano de mensalidade, garantindo uma visão clara sobre a lucratividade de cada modalidade.  

Além disso, o sistema organiza todas as movimentações financeiras da barbearia, proporcionando relatórios precisos sobre receitas e despesas. Dessa forma, você toma decisões estratégicas com base em dados confiáveis, otimizando os ganhos e garantindo um fluxo de caixa saudável. Com uma interface intuitiva e acessível, sua gestão se torna mais eficiente, permitindo que você foque no que realmente importa: oferecer um atendimento de qualidade aos seus clientes.

## Alunos integrantes da equipe

* Albert Luis Pereira de Jesus
* Arthur Modesto Couto
* Bernardo Carvalho Denucci Mercado
* Gabriel Figueiredo Cayres Burdignon
* Matheus Santos Ribeiro
* Vinicius Augusto Ribeiro Mazzoli

## Professores responsáveis

* João Paulo Carneiro Aramuni
* Danilo de Quadros Maia Filho
* Ramon Lacerda Marques

---

## Instruções de Utilização

Para começar a usar o **Barber Flow**, siga os passos abaixo. Certifique-se de ter os pré-requisitos instalados antes de iniciar o processo.

### Pré-requisitos

Antes de instalar o **Barber Flow**, você precisará dos seguintes softwares:

* **PHP:** O PHP é a linguagem de programação que o Barber Flow utiliza no backend.
* **Laravel:** O Laravel é o framework PHP usado para o desenvolvimento do backend do sistema.
* **Laragon (Recomendado):** O Laragon é um ambiente de desenvolvimento local completo que facilita a instalação e gerenciamento de PHP, MySQL, Nginx/Apache, entre outros. Ele simplifica muito a configuração do ambiente.

### Instalação e Execução

1.  **Clone o Projeto do GitHub:**
    Primeiramente, você precisará obter o código-fonte do Barber Flow. Abra seu terminal ou prompt de comando e execute o seguinte comando para clonar o repositório do GitHub:

    ```bash
    git clone https://github.com/ICEI-PUC-Minas-PMGES-TI/pmg-es-2025-1-ti3-9577100-barber-flow.git
    ```

2.  **Navegue até o Diretório do Projeto:**
    Após clonar o repositório, navegue até a pasta principal do projeto. Dentro do repositório clonado, você encontrará o diretório `Codigo/barber-flow`. Acesse-o com o comando:

    ```bash
    cd Codigo/barber-flow
    ```

3.  **Instale as Dependências do Frontend:**
    O Barber Flow utiliza o `npm` para gerenciar as dependências do frontend. Certifique-se de ter o Node.js e o npm instalados. No diretório `Codigo/barber-flow`, execute:

    ```bash
    npm install
    ```
    Este comando baixará todas as bibliotecas e pacotes JavaScript necessários para a interface do usuário.

4.  **Inicie o Servidor de Desenvolvimento do Frontend:**
    Para compilar e servir os arquivos do frontend, execute o seguinte comando:

    ```bash
    npm run dev
    ```
    Isso iniciará um servidor de desenvolvimento que observa as mudanças nos arquivos do frontend e os recompila automaticamente.

5.  **Inicie o Servidor do Backend (Laravel):**
    Com o PHP e o Laravel configurados (idealmente via Laragon), você pode iniciar o servidor de desenvolvimento do backend. Ainda no diretório `Codigo/barber-flow`, execute:

    ```bash
    php artisan serve
    ```
    Este comando iniciará o servidor PHP em `http://127.0.0.1:8000` (ou uma porta similar), permitindo que o frontend se comunique com o backend.

Ao seguir esses passos, seu sistema **Barber Flow** estará pronto para uso. Se tiver alguma dúvida ou encontrar dificuldades, não hesite em entrar em contato com a equipe de desenvolvimento.
